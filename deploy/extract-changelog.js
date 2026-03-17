#!/usr/bin/env node

import { readFileSync } from 'fs';
import { dirname, join } from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);
const rootDir = join(__dirname, '..');

function extractChangelogForVersion(version) {
  const changelogPath = join(rootDir, 'CHANGELOG.md');
  const content = readFileSync(changelogPath, 'utf8');
  const lines = content.split('\n');

  let startIndex = -1;
  let endIndex = -1;

  for (let index = 0; index < lines.length; index += 1) {
    const line = lines[index];

    if (line === `## [${version}]` || line.startsWith(`## [${version}] - `)) {
      startIndex = index;
      continue;
    }

    if (startIndex !== -1 && /^## \[[0-9.]+\](?: - .+)?$/.test(line)) {
      endIndex = index;
      break;
    }
  }

  if (startIndex === -1) {
    throw new Error(`Version ${version} not found in CHANGELOG.md`);
  }

  if (endIndex === -1) {
    endIndex = lines.length;
  }

  return lines.slice(startIndex + 1, endIndex).join('\n').trim();
}

function main() {
  const version = process.argv[2];

  if (!version) {
    console.error('Please provide a version number.');
    console.error('Usage: node deploy/extract-changelog.js <version>');
    process.exit(1);
  }

  try {
    console.log(extractChangelogForVersion(version));
  } catch (error) {
    console.error(error.message);
    process.exit(1);
  }
}

if (import.meta.url === `file://${process.argv[1]}`) {
  main();
}

export { extractChangelogForVersion };
