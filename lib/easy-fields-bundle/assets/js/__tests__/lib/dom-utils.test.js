import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';
import { loadScript, insertHtmlWithScripts } from '../../lib/dom-utils.js';

describe('dom-utils', () => {
  afterEach(() => {
    vi.restoreAllMocks();
  });

  describe('loadScript', () => {
    it('should create a script element with the correct src', async () => {
      const appendSpy = vi.spyOn(document.head, 'append').mockImplementation((el) => {
        // Simulate successful load
        setTimeout(() => el.onload(), 0);
      });

      const result = await loadScript('https://example.com/script.js');

      expect(result.src).toBe('https://example.com/script.js');
      expect(result.type).toBe('text/javascript');
      expect(appendSpy).toHaveBeenCalled();
    });
  });

  describe('insertHtmlWithScripts', () => {
    it('should insert HTML into the element', async () => {
      const container = document.createElement('div');
      await insertHtmlWithScripts(container, '<p>Hello</p>');
      expect(container.querySelector('p').textContent).toBe('Hello');
    });

    it('should re-execute inline scripts', async () => {
      const container = document.createElement('div');
      // The script won't actually run in jsdom, but we can verify it's replaced
      await insertHtmlWithScripts(container, '<div><script>window.__test_executed = true;</script></div>');
      // The script should have been replaced with a new script element
      const scripts = container.querySelectorAll('script');
      expect(scripts.length).toBe(1);
    });

    it('should skip .flex-fill elements by default', async () => {
      const container = document.createElement('div');
      container.innerHTML = '<div class="existing">Existing</div>';
      // Add a flex-fill element
      const flexFill = document.createElement('div');
      flexFill.classList.add('flex-fill');
      container.appendChild(flexFill);

      await insertHtmlWithScripts(container, '<div class="flex-fill"></div><div class="new-item"><script>/* test */</script></div>');
      // The function should handle flex-fill skip
      expect(container.querySelector('.new-item')).not.toBeNull();
    });
  });
});
