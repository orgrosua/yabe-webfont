#!/usr/bin/env node

import { execSync } from 'child_process';
import { readFileSync, writeFileSync } from 'fs';
import { dirname, join } from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);
const rootDir = join(__dirname, '..');

function getCurrentDate() {
  const now = new Date();
  const year = now.getFullYear();
  const month = String(now.getMonth() + 1).padStart(2, '0');
  const day = String(now.getDate()).padStart(2, '0');

  return `${year}-${month}-${day}`;
}

function updateVersionInFile(filePath, version, patterns) {
  let content = readFileSync(filePath, 'utf8');

  for (const pattern of patterns) {
    const regex = new RegExp(pattern.search, 'g');

    if (!regex.test(content)) {
      throw new Error(`Could not find version pattern in ${filePath}`);
    }

    content = content.replace(regex, pattern.replace.replace('${version}', version));
  }

  writeFileSync(filePath, content, 'utf8');
}

function updateChangelog(version) {
  const changelogPath = join(rootDir, 'CHANGELOG.md');
  const currentDate = getCurrentDate();
  let content = readFileSync(changelogPath, 'utf8');

  if (!content.includes('## [Unreleased]')) {
    throw new Error('Could not find the Unreleased section in CHANGELOG.md');
  }

  if (content.includes(`## [${version}]`)) {
    throw new Error(`Version ${version} already exists in CHANGELOG.md`);
  }

  content = content.replace(
    '## [Unreleased]',
    `## [Unreleased]\n\n## [${version}] - ${currentDate}`,
  );

  const unreleasedPattern = /\[unreleased\]: https:\/\/github\.com\/orgrosua\/yabe-webfont\/compare\/([0-9]+\.[0-9]+\.[0-9]+)\.\.\.HEAD/;
  const match = content.match(unreleasedPattern);

  if (!match) {
    throw new Error('Could not find the unreleased comparison link in CHANGELOG.md');
  }

  const previousVersion = match[1];

  content = content.replace(
    unreleasedPattern,
    `[unreleased]: https://github.com/orgrosua/yabe-webfont/compare/${version}...HEAD`,
  );

  const lines = content.split('\n');
  const unreleasedLinkIndex = lines.findIndex((line) => line.startsWith('[unreleased]: '));

  if (unreleasedLinkIndex === -1) {
    throw new Error('Could not find the unreleased link position in CHANGELOG.md');
  }

  lines.splice(
    unreleasedLinkIndex + 1,
    0,
    `[${version}]: https://github.com/orgrosua/yabe-webfont/compare/${previousVersion}...${version}`,
  );

  writeFileSync(changelogPath, lines.join('\n'), 'utf8');
}

function executeGitCommands(version) {
  execSync('git add .', { cwd: rootDir, stdio: 'inherit' });
  execSync(`git commit -m "Update VERSION for ${version}"`, { cwd: rootDir, stdio: 'inherit' });
  execSync(`git tag ${version}`, { cwd: rootDir, stdio: 'inherit' });
}

function main() {
  const version = process.argv[2];

  if (!version) {
    console.error('Please provide a version number.');
    console.error('Usage: node deploy/release.js <version>');
    process.exit(1);
  }

  if (!/^\d+\.\d+\.\d+$/.test(version)) {
    console.error('Invalid version format. Use semantic versioning, for example: 2.0.99');
    process.exit(1);
  }

  try {
    updateVersionInFile(join(rootDir, 'readme.txt'), version, [
      {
        search: 'Stable tag: [0-9]+\\.[0-9]+\\.[0-9]+',
        replace: 'Stable tag: ${version}',
      },
    ]);

    updateVersionInFile(join(rootDir, 'constant.php'), version, [
      {
        search: "public const VERSION = '[0-9]+\\.[0-9]+\\.[0-9]+';",
        replace: "public const VERSION = '${version}';",
      },
    ]);

    updateVersionInFile(join(rootDir, 'yabe-webfont.php'), version, [
      {
        search: '\\* Version:\\s+[0-9]+\\.[0-9]+\\.[0-9]+',
        replace: ' * Version:             ${version}',
      },
    ]);

    updateChangelog(version);
    executeGitCommands(version);

    console.log('Release process completed successfully.');
    console.log('Next steps: git push && git push --tags');
  } catch (error) {
    console.error(error.message);
    process.exit(1);
  }
}

main();
