import { build } from 'vite';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, '..');

// Build JS entry
await build({
  configFile: false,
  build: {
    lib: {
      entry: path.resolve(root, 'assets/js/field-editor.js'),
      formats: ['iife'],
      name: 'field_editor',
      fileName: () => 'field-editor.js',
    },
    outDir: path.resolve(root, 'src/Resources/public'),
    emptyOutDir: false,
    minify: true,
  },
  resolve: {
    alias: {
      '@easy-fields': path.resolve(root, '../easy-fields-bundle/assets/js'),
    },
  },
});

// Build SCSS
await build({
  configFile: false,
  build: {
    rollupOptions: {
      input: path.resolve(root, 'assets/scss/easy-editor.scss'),
      output: {
        assetFileNames: '[name][extname]',
      },
    },
    outDir: path.resolve(root, 'src/Resources/public'),
    emptyOutDir: false,
    minify: true,
  },
});
