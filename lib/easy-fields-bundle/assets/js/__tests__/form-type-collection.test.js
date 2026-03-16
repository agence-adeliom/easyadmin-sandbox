import { describe, it, expect, beforeEach, vi, afterEach } from 'vitest';

// Import the source file — registers DOMContentLoaded + ea.collection.item-added handlers
import '../form-type-collection.js';

/**
 * Tests for form-type-collection.js
 * Safety-net tests verifying DOM behavior BEFORE any refactoring.
 */

function buildCollectionHTML({ isArray = false, numItems = 0, prototype = '', placeholder = '__name__', isEmpty = false } = {}) {
  const fieldClass = isArray ? 'field-array' : '';
  const emptyBadge = isEmpty ? '<span class="collection-empty">Empty</span>' : '';

  let itemsHtml = '';
  if (!isEmpty && numItems > 0) {
    for (let i = 0; i < numItems; i++) {
      if (isArray) {
        itemsHtml += `<div class="field-collection-item" data-index="${i}"><input name="form[items][${i}][value]" value="item${i}"><button type="button" class="field-collection-delete-button">Delete</button></div>`;
      } else {
        itemsHtml += `<div class="field-collection-item" data-index="${i}"><div class="accordion-item"><h2><button class="accordion-button collapsed" data-bs-toggle="collapse">Item ${i}</button></h2><div class="accordion-collapse collapse"><div class="accordion-body"><input name="form[items][${i}][value]" value="item${i}"></div></div></div><button type="button" class="field-collection-delete-button">Delete</button></div>`;
      }
    }
  }

  const wrapperStart = isArray
    ? '<div class="ea-form-collection-items">'
    : '<div class="ea-form-collection-items"><div class="accordion"><div class="form-widget-compound">';
  const wrapperEnd = isArray
    ? '</div>'
    : '</div></div></div>';

  const itemsSection = isEmpty
    ? ''
    : `${wrapperStart}${itemsHtml}${wrapperEnd}`;

  // The button and the empty badge must share the same parent
  // because the source does: this.parentElement.querySelector('.collection-empty')
  return `
    <div data-ea-collection-field="true" class="${fieldClass}"
         data-num-items="${numItems}"
         data-form-type-name-placeholder="${placeholder}"
         data-prototype="${prototype}">
      ${itemsSection}
      <div>
        ${isEmpty ? `<div>${emptyBadge}</div>` : ''}
        <button type="button" class="field-collection-add-button">Add</button>
      </div>
    </div>
  `;
}

describe('form-type-collection.js', () => {
  let container;

  beforeEach(() => {
    container = document.createElement('div');
    document.body.appendChild(container);
    // Reset processed state from previous tests
    document.querySelectorAll('.processed').forEach(el => el.classList.remove('processed'));
  });

  afterEach(() => {
    container.remove();
    vi.restoreAllMocks();
  });

  describe('Add item', () => {
    it('should add a new item to an array collection on button click', async () => {
      const prototype = '<div class=&quot;field-collection-item&quot;><input name=&quot;form[items][__name__][value]&quot;></div>';
      container.innerHTML = buildCollectionHTML({
        isArray: true,
        numItems: 1,
        prototype,
        placeholder: '__name__',
      });

      // Trigger the handler (simulates DOMContentLoaded or ea.collection.item-added)
      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const addButton = container.querySelector('.field-collection-add-button');
      addButton.click();
      await new Promise(r => setTimeout(r, 50));

      const items = container.querySelectorAll('.field-collection-item');
      expect(items.length).toBe(2);
    });

    it('should replace placeholder with index in new item', async () => {
      const prototype = '<div class=&quot;field-collection-item&quot;><input name=&quot;form[items][__name__][value]&quot;></div>';
      container.innerHTML = buildCollectionHTML({
        isArray: true,
        numItems: 0,
        prototype,
        placeholder: '__name__',
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const addButton = container.querySelector('.field-collection-add-button');
      const collection = container.querySelector('[data-ea-collection-field]');
      addButton.click();
      await new Promise(r => setTimeout(r, 50));

      const input = container.querySelector('.field-collection-item input');
      expect(input).not.toBeNull();
      expect(input.name).toBe('form[items][1][value]');
      expect(collection.dataset.numItems).toBe('1');
    });

    it('should increment data-num-items after add', async () => {
      const prototype = '<div class=&quot;field-collection-item&quot;><input name=&quot;form[items][__name__][value]&quot;></div>';
      container.innerHTML = buildCollectionHTML({
        isArray: true,
        numItems: 2,
        prototype,
        placeholder: '__name__',
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const collection = container.querySelector('[data-ea-collection-field]');
      expect(collection.dataset.numItems).toBe('2');

      container.querySelector('.field-collection-add-button').click();
      await new Promise(r => setTimeout(r, 50));
      expect(collection.dataset.numItems).toBe('3');
    });
  });

  describe('Delete item', () => {
    it('should remove the parent .field-collection-item on delete click', async () => {
      container.innerHTML = buildCollectionHTML({
        isArray: true,
        numItems: 2,
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const deleteButtons = container.querySelectorAll('.field-collection-delete-button');
      expect(deleteButtons.length).toBe(2);

      deleteButtons[0].click();
      await new Promise(r => setTimeout(r, 0));

      const items = container.querySelectorAll('.field-collection-item');
      expect(items.length).toBe(1);
    });

    it('should dispatch ea.collection.item-removed after delete', async () => {
      container.innerHTML = buildCollectionHTML({
        isArray: true,
        numItems: 1,
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const eventSpy = vi.fn();
      document.addEventListener('ea.collection.item-removed', eventSpy);

      container.querySelector('.field-collection-delete-button').click();
      await new Promise(r => setTimeout(r, 0));

      expect(eventSpy).toHaveBeenCalledTimes(1);
      document.removeEventListener('ea.collection.item-removed', eventSpy);
    });
  });

  describe('CSS first/last classes', () => {
    it('should add first and last classes to collection items', async () => {
      container.innerHTML = buildCollectionHTML({
        isArray: true,
        numItems: 3,
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const items = container.querySelectorAll('.field-collection-item');
      expect(items[0].classList.contains('field-collection-item-first')).toBe(true);
      expect(items[2].classList.contains('field-collection-item-last')).toBe(true);
      expect(items[1].classList.contains('field-collection-item-first')).toBe(false);
      expect(items[1].classList.contains('field-collection-item-last')).toBe(false);
    });

    it('should update first/last classes after deletion', async () => {
      container.innerHTML = buildCollectionHTML({
        isArray: true,
        numItems: 3,
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      container.querySelectorAll('.field-collection-delete-button')[0].click();
      await new Promise(r => setTimeout(r, 0));

      const items = container.querySelectorAll('.field-collection-item');
      expect(items.length).toBe(2);
      expect(items[0].classList.contains('field-collection-item-first')).toBe(true);
      expect(items[1].classList.contains('field-collection-item-last')).toBe(true);
    });
  });

  describe('Empty collection badge', () => {
    it('should replace .collection-empty with wrapper when adding to empty collection', async () => {
      const prototype = '<div class=&quot;field-collection-item&quot;><input name=&quot;form[items][__name__][value]&quot;></div>';
      container.innerHTML = buildCollectionHTML({
        isArray: true,
        numItems: 0,
        prototype,
        isEmpty: true,
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      container.querySelector('.field-collection-add-button').click();
      await new Promise(r => setTimeout(r, 50));

      expect(container.querySelector('.collection-empty')).toBeNull();
      expect(container.querySelector('.ea-form-collection-items')).not.toBeNull();
    });
  });

  describe('Event dispatch', () => {
    it('should dispatch ea.collection.item-added after adding an item', async () => {
      const prototype = '<div class=&quot;field-collection-item&quot;><input name=&quot;form[items][__name__][value]&quot;></div>';
      container.innerHTML = buildCollectionHTML({
        isArray: true,
        numItems: 0,
        prototype,
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const eventSpy = vi.fn();
      document.addEventListener('ea.collection.item-added', eventSpy);

      container.querySelector('.field-collection-add-button').click();
      await new Promise(r => setTimeout(r, 50));

      expect(eventSpy).toHaveBeenCalled();
      document.removeEventListener('ea.collection.item-added', eventSpy);
    });
  });

  describe('Accordion expand for complex collections', () => {
    it('should expand the last added item in a complex collection', async () => {
      const prototype = '<div class=&quot;field-collection-item&quot;><div class=&quot;accordion-item&quot;><h2><button class=&quot;accordion-button collapsed&quot;>New</button></h2><div class=&quot;accordion-collapse collapse&quot;><div class=&quot;accordion-body&quot;><input name=&quot;form[items][__name__][value]&quot;></div></div></div></div>';
      container.innerHTML = buildCollectionHTML({
        isArray: false,
        numItems: 1,
        prototype,
        placeholder: '__name__',
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      container.querySelector('.field-collection-add-button').click();
      await new Promise(r => setTimeout(r, 50));

      const items = container.querySelectorAll('.field-collection-item');
      const lastItem = items[items.length - 1];
      const collapseButton = lastItem.querySelector('.accordion-button');
      const collapseBody = lastItem.querySelector('.accordion-collapse');

      expect(collapseButton.classList.contains('collapsed')).toBe(false);
      expect(collapseBody.classList.contains('show')).toBe(true);
    });
  });

  describe('Processed guard', () => {
    it('should mark collection as processed after first init', async () => {
      const prototype = '<div class=&quot;field-collection-item&quot;><input name=&quot;form[items][__name__][value]&quot;></div>';
      container.innerHTML = buildCollectionHTML({
        isArray: true,
        numItems: 0,
        prototype,
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const collection = container.querySelector('[data-ea-collection-field]');
      expect(collection.classList.contains('processed')).toBe(true);
    });

    it('should not re-bind add button when already processed', async () => {
      const prototype = '<div class=&quot;field-collection-item&quot;><input name=&quot;form[items][__name__][value]&quot;></div>';
      container.innerHTML = buildCollectionHTML({
        isArray: true,
        numItems: 0,
        prototype,
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      // Trigger again — should skip because collection is already processed
      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      // Click should only add 1 item (not 2 if listener was bound twice)
      container.querySelector('.field-collection-add-button').click();
      await new Promise(r => setTimeout(r, 50));

      const items = container.querySelectorAll('.field-collection-item');
      expect(items.length).toBe(1);
    });
  });
});
