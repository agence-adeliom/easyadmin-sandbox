import { describe, it, expect, vi } from 'vitest';
import {
  replacePrototypePlaceholders,
  removeEmptyBadge,
  updateFirstLastClasses,
  expandLastAccordionItem,
  createAddHandler,
} from '../../lib/collection-core.js';

describe('collection-core', () => {
  describe('replacePrototypePlaceholders', () => {
    it('should replace label and name placeholders with index', () => {
      const prototype =
        '<input name="form[items][__name__][value]"><label>__name__label__</label>';
      const result = replacePrototypePlaceholders(prototype, '__name__', 5);
      expect(result).toBe('<input name="form[items][5][value]"><label>5</label>');
    });

    it('should handle multiple occurrences', () => {
      const prototype = '__name__ and __name__ and __name__label__';
      const result = replacePrototypePlaceholders(prototype, '__name__', 3);
      expect(result).toBe('3 and 3 and 3');
    });
  });

  describe('removeEmptyBadge', () => {
    it('should replace badge parent with array wrapper', () => {
      const container = document.createElement('div');
      container.innerHTML = '<div><span class="collection-empty">Empty</span></div>';

      removeEmptyBadge(container, true, {
        array: '<div class="ea-form-collection-items"></div>',
        complex:
          '<div class="ea-form-collection-items"><div class="accordion"><div class="form-widget-compound"></div></div></div>',
      });

      expect(container.querySelector('.collection-empty')).toBeNull();
      expect(container.querySelector('.ea-form-collection-items')).not.toBeNull();
    });

    it('should replace badge parent with complex wrapper', () => {
      const container = document.createElement('div');
      container.innerHTML = '<div><span class="collection-empty">Empty</span></div>';

      removeEmptyBadge(container, false, {
        array: '<div class="ea-form-collection-items"></div>',
        complex:
          '<div class="ea-form-collection-items"><div class="accordion"><div class="form-widget-compound"></div></div></div>',
      });

      expect(container.querySelector('.accordion')).not.toBeNull();
    });

    it('should do nothing if no badge exists', () => {
      const container = document.createElement('div');
      container.innerHTML = '<div>No badge here</div>';

      removeEmptyBadge(container, true, {
        array: '<div class="ea-form-collection-items"></div>',
        complex: '',
      });

      expect(container.innerHTML).toContain('No badge here');
    });
  });

  describe('updateFirstLastClasses', () => {
    it('should add first/last classes correctly', () => {
      const container = document.createElement('div');
      container.innerHTML = `
        <div class="item">A</div>
        <div class="item">B</div>
        <div class="item">C</div>
      `;

      updateFirstLastClasses(container, '.item', 'item');

      const items = container.querySelectorAll('.item');
      expect(items[0].classList.contains('item-first')).toBe(true);
      expect(items[1].classList.contains('item-first')).toBe(false);
      expect(items[1].classList.contains('item-last')).toBe(false);
      expect(items[2].classList.contains('item-last')).toBe(true);
    });

    it('should handle single item', () => {
      const container = document.createElement('div');
      container.innerHTML = '<div class="item">Only</div>';

      updateFirstLastClasses(container, '.item', 'item');

      const item = container.querySelector('.item');
      expect(item.classList.contains('item-first')).toBe(true);
      expect(item.classList.contains('item-last')).toBe(true);
    });

    it('should handle null collection', () => {
      expect(() => updateFirstLastClasses(null, '.item', 'item')).not.toThrow();
    });

    it('should remove previous first/last classes', () => {
      const container = document.createElement('div');
      container.innerHTML = `
        <div class="item item-first item-last">A</div>
        <div class="item">B</div>
      `;

      updateFirstLastClasses(container, '.item', 'item');

      const items = container.querySelectorAll('.item');
      expect(items[0].classList.contains('item-first')).toBe(true);
      expect(items[0].classList.contains('item-last')).toBe(false);
      expect(items[1].classList.contains('item-last')).toBe(true);
    });
  });

  describe('expandLastAccordionItem', () => {
    it('should uncollapse the last item', () => {
      const container = document.createElement('div');
      container.innerHTML = `
        <div class="item"><button class="accordion-button collapsed">First</button><div class="accordion-collapse collapse">Body1</div></div>
        <div class="item"><button class="accordion-button collapsed">Last</button><div class="accordion-collapse collapse">Body2</div></div>
      `;

      expandLastAccordionItem(container, '.item');

      const items = container.querySelectorAll('.item');
      // First item should remain collapsed
      expect(items[0].querySelector('.accordion-button').classList.contains('collapsed')).toBe(
        true,
      );
      // Last item should be expanded
      expect(items[1].querySelector('.accordion-button').classList.contains('collapsed')).toBe(
        false,
      );
      expect(items[1].querySelector('.accordion-collapse').classList.contains('show')).toBe(true);
    });

    it('should handle empty wrapper', () => {
      const container = document.createElement('div');
      expect(() => expandLastAccordionItem(container, '.item')).not.toThrow();
    });
  });

  describe('createAddHandler', () => {
    it('should return a function that attaches a click handler', () => {
      const insertHtml = vi.fn(() => Promise.resolve());
      const handler = createAddHandler({
        itemSelector: '.field-collection-item',
        classPrefix: 'field-collection-item',
        emptyBadgeHtml: {
          array: '<div class="ea-form-collection-items"></div>',
          complex: '',
        },
        insertionSelector: {
          array: '.ea-form-collection-items',
          complex: '.ea-form-collection-items .accordion > .form-widget-compound',
        },
        events: ['ea.collection.item-added'],
        insertHtml,
      });

      expect(typeof handler).toBe('function');
    });

    it('should dispatch events after adding', async () => {
      const insertHtml = vi.fn(() => Promise.resolve());
      const handler = createAddHandler({
        itemSelector: '.field-collection-item',
        classPrefix: 'field-collection-item',
        emptyBadgeHtml: {
          array: '<div class="ea-form-collection-items"></div>',
          complex: '',
        },
        insertionSelector: {
          array: '.ea-form-collection-items',
          complex: '',
        },
        events: ['ea.collection.item-added'],
        insertHtml,
      });

      const container = document.createElement('div');
      container.innerHTML = `
        <div data-ea-collection-field="true" class="field-array"
             data-num-items="0"
             data-form-type-name-placeholder="__name__"
             data-prototype="<div class=&quot;field-collection-item&quot;>__name__</div>">
          <div class="ea-form-collection-items"></div>
          <div>
            <button type="button" class="add-btn">Add</button>
          </div>
        </div>
      `;
      document.body.appendChild(container);

      const addButton = container.querySelector('.add-btn');
      const collection = container.querySelector('[data-ea-collection-field]');
      handler(addButton, collection);

      const eventSpy = vi.fn();
      document.addEventListener('ea.collection.item-added', eventSpy);

      addButton.click();
      await new Promise((r) => setTimeout(r, 10));

      expect(insertHtml).toHaveBeenCalled();
      expect(eventSpy).toHaveBeenCalled();

      document.removeEventListener('ea.collection.item-added', eventSpy);
      container.remove();
    });
  });
});
