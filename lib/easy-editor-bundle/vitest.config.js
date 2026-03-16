import { defineConfig } from 'vitest/config';
import path from 'path';

// This config is used only for vitest.
// Build is handled by scripts/build.mjs.
export default defineConfig({
  test: {
    environment: 'jsdom',
    globals: true,
    root: '.',
  },
  resolve: {
    alias: {
      '@easy-fields': path.resolve(__dirname, '../easy-fields-bundle/assets/js'),
    },
  },
});
