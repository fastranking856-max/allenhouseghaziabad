/**
 * Vercel build: static → public/, PHP → vercel-slim/, whitelist root (api + slim + public only).
 */
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const ROOT = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const SLIM = path.join(ROOT, 'vercel-slim');
const PUBLIC = path.join(ROOT, 'public');
const SKIP = new Set(['node_modules', '.git', 'cache', 'vercel-slim', 'public', 'scripts', 'api']);
const PHP_EXT = new Set(['.php', '.css', '.js', '.json', '.xml', '.txt']);
const KEEP_ROOT = new Set(['api', 'vercel-slim', 'public', 'vercel.json', 'package.json']);

function isVercelBuild() {
  return Boolean(process.env.VERCEL_ENV || process.env.VERCEL === '1' || process.env.VERCEL === 'true' || process.env.CI);
}

function rmDir(dir) {
  if (fs.existsSync(dir)) {
    fs.rmSync(dir, { recursive: true, force: true });
  }
}

function copyPhpTree(srcDir, destDir) {
  if (!fs.existsSync(srcDir)) return;
  for (const ent of fs.readdirSync(srcDir, { withFileTypes: true })) {
    if (SKIP.has(ent.name) || ent.name === 'node_modules') continue;
    const src = path.join(srcDir, ent.name);
    const dest = path.join(destDir, ent.name);
    if (ent.isDirectory()) {
      copyPhpTree(src, dest);
    } else if (ent.isFile()) {
      const ext = path.extname(ent.name).toLowerCase();
      if (PHP_EXT.has(ext) || ent.name === '.htaccess') {
        fs.mkdirSync(destDir, { recursive: true });
        fs.copyFileSync(src, dest);
      }
    }
  }
}

function copyStaticTree(srcDir, destDir) {
  if (!fs.existsSync(srcDir)) return;
  for (const ent of fs.readdirSync(srcDir, { withFileTypes: true })) {
    if (SKIP.has(ent.name) || ent.name === 'node_modules') continue;
    const src = path.join(srcDir, ent.name);
    const dest = path.join(destDir, ent.name);
    if (ent.isDirectory()) {
      copyStaticTree(src, dest);
    } else if (ent.isFile() && !ent.name.endsWith('.php')) {
      fs.mkdirSync(destDir, { recursive: true });
      fs.copyFileSync(src, dest);
    }
  }
}

function dirSize(dir) {
  let bytes = 0;
  if (!fs.existsSync(dir)) return 0;
  const walk = (d) => {
    for (const ent of fs.readdirSync(d, { withFileTypes: true })) {
      const p = path.join(d, ent.name);
      if (ent.isDirectory()) walk(p);
      else if (ent.isFile()) bytes += fs.statSync(p).size;
    }
  };
  walk(dir);
  return bytes;
}

function whitelistRoot() {
  for (const ent of fs.readdirSync(ROOT, { withFileTypes: true })) {
    if (KEEP_ROOT.has(ent.name)) continue;
    if (ent.name.startsWith('.')) continue;
    if (ent.name === 'node_modules') continue;
    const p = path.join(ROOT, ent.name);
    if (ent.isDirectory()) {
      rmDir(p);
      console.log(`removed dir: ${ent.name}`);
    } else {
      fs.unlinkSync(p);
      console.log(`removed file: ${ent.name}`);
    }
  }
}

// 1) Slim PHP tree
rmDir(SLIM);
fs.mkdirSync(SLIM, { recursive: true });
copyPhpTree(ROOT, SLIM);
console.log(`vercel-slim: ${(dirSize(SLIM) / 1024 / 1024).toFixed(2)} MB`);

// 2) Static CDN tree
rmDir(PUBLIC);
fs.mkdirSync(PUBLIC, { recursive: true });
copyStaticTree(ROOT, PUBLIC);
console.log(`public: ${(dirSize(PUBLIC) / 1024 / 1024).toFixed(1)} MB`);

// 3) On Vercel CI: only api/, vercel-slim/, public/ remain at repo root
if (isVercelBuild()) {
  whitelistRoot();
  console.log(`root after prune: ${(dirSize(ROOT) / 1024 / 1024).toFixed(1)} MB`);
} else {
  console.log('prune skipped (local build)');
}
