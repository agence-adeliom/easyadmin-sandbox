import { defineConfig } from 'vite';

// This config is used only for vitest.
// Build is handled by scripts/build.mjs (IIFE format per entry).
export default defineConfig({
  test: {
    environment: 'jsdom',
    globals: true,
    root: '.',
  },
});
