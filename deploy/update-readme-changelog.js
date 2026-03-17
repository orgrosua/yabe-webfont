#!/usr/bin/env node

import { readFileSync, writeFileSync } from 'fs';
import { dirname, join } from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);
const rootDir = join(__dirname, '..');
const changelogUrl = 'https://github.com/orgrosua/yabe-webfont/blob/master/CHANGELOG.md';

function parseChangelog(changelogContent) {
  const entries = [];
  const lines = changelogContent.split('\n');
  let currentEntry = null;
  let currentSection = null;

  for (const line of lines) {
    const versionMatch = line.match(/^## \[(.+?)\] - (.+)$/);

    if (versionMatch) {
      if (currentEntry) {
        entries.push(currentEntry);
      }

      currentEntry = {
        version: versionMatch[1],
        date: versionMatch[2],
        sections: [],
      };
      currentSection = null;
      continue;
    }

    if (!currentEntry) {
      continue;
    }

    if (/^\[.+?\]: https?:\/\//.test(line)) {
      break;
    }

    const sectionMatch = line.match(/^### (Added|Changed|Fixed|Removed)$/);

    if (sectionMatch) {
      currentSection = {
        title: sectionMatch[1],
        items: [],
      };
      currentEntry.sections.push(currentSection);
      continue;
    }

    if (line.startsWith('- ')) {
      if (!currentSection) {
        currentSection = {
          title: 'Changed',
          items: [],
        };
        currentEntry.sections.push(currentSection);
      }

      currentSection.items.push(line.slice(2).trim());
    }
  }

  if (currentEntry) {
    entries.push(currentEntry);
  }

  return entries;
}

function formatForReadme(entries) {
  return entries
    .map((entry) => {
      const lines = [`= ${entry.version} - ${entry.date} =`, ''];

      for (const section of entry.sections) {
        lines.push(`**${section.title}**`);

        for (const item of section.items) {
          lines.push(`* ${item}`);
        }

        lines.push('');
      }

      while (lines[lines.length - 1] === '') {
        lines.pop();
      }

      return lines.join('\n');
    })
    .join('\n\n');
}

function updateReadmeWithChangelog(readmeContent, changelogText) {
  const changelogMatch = readmeContent.match(/== Changelog ==/);

  if (!changelogMatch || changelogMatch.index === undefined) {
    throw new Error('Could not find the changelog section in readme.txt');
  }

  const beforeChangelog = readmeContent.slice(0, changelogMatch.index).trimEnd();

  return `${beforeChangelog}\n\n== Changelog ==\n\n${changelogText}\n\n[See changelog for all versions.](${changelogUrl})\n`;
}

function main() {
  const changelogPath = join(rootDir, 'CHANGELOG.md');
  const readmePath = join(rootDir, 'readme.txt');
  const changelogContent = readFileSync(changelogPath, 'utf8');
  const readmeContent = readFileSync(readmePath, 'utf8');
  const entries = parseChangelog(changelogContent);
  const readmeChangelog = formatForReadme(entries);
  const updatedReadme = updateReadmeWithChangelog(readmeContent, readmeChangelog);

  writeFileSync(readmePath, updatedReadme, 'utf8');
}

main();
