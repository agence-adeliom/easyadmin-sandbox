import { describe, it, expect, vi, beforeEach } from 'vitest';

vi.mock('sortablejs', () => ({
  default: {
    create: vi.fn(() => ({ destroy: vi.fn() })),
  },
}));

import { initSortable, updatePositionInputs, reindexInputNames } from '../../lib/sortable-utils.js';
import Sortable from 'sortablejs';

describe('sortable-utils', () => {
  beforeEach(() => {
    Sortable.create.mockClear();
  });

  describe('initSortable', () => {
    it('should call Sortable.create with correct params', () => {
      const collection = document.createElement('div');
      const container = document.createElement('div');
      container.classList.add('sortable-container');
      collection.appendChild(container);

      const onEnd = vi.fn();
      initSortable(collection, '.sortable-container', '.drag-handle', onEnd);

      expect(Sortable.create).toHaveBeenCalledTimes(1);
      const [el, options] = Sortable.create.mock.calls[0];
      expect(el).toBe(container);
      expect(options.handle).toBe('.drag-handle');
      expect(options.direction).toBe('vertical');
    });

    it('should destroy previous sortable instance', () => {
      const collection = document.createElement('div');
      const container = document.createElement('div');
      container.classList.add('sortable-container');
      collection.appendChild(container);

      const mockDestroy = vi.fn();
      collection.sortable = { destroy: mockDestroy };

      initSortable(collection, '.sortable-container', '.handle', vi.fn());

      expect(mockDestroy).toHaveBeenCalled();
    });

    it('should handle null collection', () => {
      expect(() => initSortable(null, '.container', '.handle', vi.fn())).not.toThrow();
    });

    it('should handle missing container', () => {
      const collection = document.createElement('div');
      initSortable(collection, '.nonexistent', '.handle', vi.fn());
      expect(Sortable.create).not.toHaveBeenCalled();
    });
  });

  describe('updatePositionInputs', () => {
    it('should set position input values based on item order', () => {
      const collection = document.createElement('div');
      collection.innerHTML = `
        <div class="item"><input name="form[items][0][position]" value="5"></div>
        <div class="item"><input name="form[items][1][position]" value="3"></div>
        <div class="item"><input name="form[items][2][position]" value="7"></div>
      `;

      updatePositionInputs(collection, '.item');

      const inputs = collection.querySelectorAll('input');
      expect(inputs[0].value).toBe('0');
      expect(inputs[1].value).toBe('1');
      expect(inputs[2].value).toBe('2');
    });

    it('should only update inputs with [position] in name', () => {
      const collection = document.createElement('div');
      collection.innerHTML = `
        <div class="item">
          <input name="form[items][0][value]" value="hello">
          <input name="form[items][0][position]" value="9">
        </div>
      `;

      updatePositionInputs(collection, '.item');

      expect(collection.querySelector('[name*="[value]"]').value).toBe('hello');
      expect(collection.querySelector('[name*="[position]"]').value).toBe('0');
    });

    it('should handle null collection', () => {
      expect(() => updatePositionInputs(null, '.item')).not.toThrow();
    });
  });

  describe('reindexInputNames', () => {
    it('should reindex input names sequentially', () => {
      const collection = document.createElement('div');
      collection.innerHTML = `
        <div class="item"><input name="form[items][5][value]" value="a"><input name="form[items][5][position]" value="0"></div>
        <div class="item"><input name="form[items][3][value]" value="b"><input name="form[items][3][position]" value="1"></div>
        <div class="item"><input name="form[items][8][value]" value="c"><input name="form[items][8][position]" value="2"></div>
      `;

      reindexInputNames(collection, '.item', 'form[items]');

      const inputs = collection.querySelectorAll('[name*="[value]"]');
      // hasToIncrement = true because first item starts with index matching key 0
      // So indices become 1, 2, 3
      expect(inputs[0].name).toBe('form[items][1][value]');
      expect(inputs[1].name).toBe('form[items][2][value]');
      expect(inputs[2].name).toBe('form[items][3][value]');
    });

    it('should handle null collection', () => {
      expect(() => reindexInputNames(null, '.item', 'form[items]')).not.toThrow();
    });
  });
});
