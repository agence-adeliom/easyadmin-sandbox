import { build } from 'vite';
import path from 'path';
import { fileURLToPath } from 'url';
import { readFileSync, writeFileSync, mkdirSync } from 'fs';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, '..');

const entries = [
  'form-type-collection',
  'form-type-collection-sortable',
  'form-type-association-list',
  'form-type-association-new-ajax',
];

for (const name of entries) {
  await build({
    configFile: false,
    build: {
      lib: {
        entry: path.resolve(root, `assets/js/${name}.js`),
        formats: ['iife'],
        name: name.replace(/-/g, '_'),
        fileName: () => `${name}.js`,
      },
      outDir: path.resolve(root, 'src/Resources/public'),
      emptyOutDir: false,
      minify: true,
    },
  });
}

// Copy CSS (no preprocessing needed)
const cssSrc = path.resolve(root, 'assets/css/form-type-association.css');
const cssDest = path.resolve(root, 'src/Resources/public/form-type-association.css');
mkdirSync(path.dirname(cssDest), { recursive: true });
writeFileSync(cssDest, readFileSync(cssSrc));
console.log('Copied form-type-association.css');
