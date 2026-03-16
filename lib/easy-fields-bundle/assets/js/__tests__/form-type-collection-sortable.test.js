import { describe, it, expect, beforeEach, vi, afterEach } from 'vitest';

// Mock sortablejs before importing the source module
vi.mock('sortablejs', () => ({
  default: {
    create: vi.fn(() => ({ destroy: vi.fn() })),
  },
}));

// Mock jquery (source does: window.$ = window.jQuery = require('jquery'))
vi.mock('jquery', () => {
  const jq = vi.fn();
  return { default: jq };
});

// Import the source file — registers DOMContentLoaded + ea.collection.item-added handlers
import '../form-type-collection-sortable.js';

function buildSortableCollectionHTML({ numItems = 0, prototype = '', placeholder = '__name__', formTypeId = 'form_items', parentId = 'form', fullName = 'form[items]' } = {}) {
  let itemsHtml = '';
  for (let i = 0; i < numItems; i++) {
    itemsHtml += `
      <div class="form-group">
        <div class="field-sortable_collection-item" data-form-type-parent-id="${parentId}">
          <div class="accordion-item">
            <h2><button class="accordion-button collapsed">Item ${i}</button></h2>
            <div class="accordion-collapse collapse">
              <div class="accordion-body">
                <input name="${fullName}[${i}][value]" value="item${i}">
                <input name="${fullName}[${i}][position]" value="${i}">
              </div>
            </div>
          </div>
          <button type="button" class="field-sortable_collection-delete-button">Delete</button>
        </div>
      </div>`;
  }

  return `
    <div data-ea-collection-field="true"
         data-num-items="${numItems}"
         data-form-type-name-placeholder="${placeholder}"
         data-form-type-id="${formTypeId}"
         data-form-type-parent-id="${parentId}"
         data-ea-collection-field-full-name="${fullName}"
         data-prototype="${prototype}">
      <div class="ea-form-collection-items">
        <div class="accordion">
          <div class="form-widget-compound" id="${formTypeId}">
            ${itemsHtml}
          </div>
        </div>
      </div>
      <div>
        <button type="button" class="field-sortable_collection-add-button" drag-handler="${formTypeId}">Add</button>
      </div>
    </div>
  `;
}

describe('form-type-collection-sortable.js', () => {
  let container;
  let Sortable;

  beforeEach(async () => {
    container = document.createElement('div');
    document.body.appendChild(container);
    // Reset processed state
    document.querySelectorAll('.processed').forEach(el => el.classList.remove('processed'));
    // Suppress console.log from source (line 146)
    vi.spyOn(console, 'log').mockImplementation(() => {});

    const sortableModule = await import('sortablejs');
    Sortable = sortableModule.default;
    Sortable.create.mockClear();
  });

  afterEach(() => {
    container.remove();
    vi.restoreAllMocks();
  });

  describe('Add item', () => {
    it('should add a new item on button click', async () => {
      const prototype = '<div class=&quot;form-group&quot;><div class=&quot;field-sortable_collection-item&quot; data-form-type-parent-id=&quot;form&quot;><div class=&quot;accordion-item&quot;><h2><button class=&quot;accordion-button collapsed&quot;>New</button></h2><div class=&quot;accordion-collapse collapse&quot;><div class=&quot;accordion-body&quot;><input name=&quot;form[items][__name__][value]&quot;><input name=&quot;form[items][__name__][position]&quot;></div></div></div></div></div>';
      container.innerHTML = buildSortableCollectionHTML({
        numItems: 1,
        prototype,
        placeholder: '__name__',
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      container.querySelector('.field-sortable_collection-add-button').click();
      await new Promise(r => setTimeout(r, 50));

      const items = container.querySelectorAll('.field-sortable_collection-item');
      expect(items.length).toBe(2);
    });

    it('should increment data-num-items', async () => {
      const prototype = '<div class=&quot;form-group&quot;><div class=&quot;field-sortable_collection-item&quot; data-form-type-parent-id=&quot;form&quot;><div class=&quot;accordion-item&quot;><h2><button class=&quot;accordion-button collapsed&quot;>New</button></h2><div class=&quot;accordion-collapse collapse&quot;><div class=&quot;accordion-body&quot;><input name=&quot;form[items][__name__][value]&quot;></div></div></div></div></div>';
      container.innerHTML = buildSortableCollectionHTML({
        numItems: 0,
        prototype,
        placeholder: '__name__',
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const collection = container.querySelector('[data-ea-collection-field]');
      expect(collection.dataset.numItems).toBe('0');

      container.querySelector('.field-sortable_collection-add-button').click();
      await new Promise(r => setTimeout(r, 50));

      expect(collection.dataset.numItems).toBe('1');
    });
  });

  describe('Delete item', () => {
    it('should remove the parent .form-group on delete click', async () => {
      container.innerHTML = buildSortableCollectionHTML({ numItems: 2 });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      container.querySelectorAll('.field-sortable_collection-delete-button')[0].click();
      await new Promise(r => setTimeout(r, 0));

      const items = container.querySelectorAll('.field-sortable_collection-item');
      expect(items.length).toBe(1);
    });

    it('should dispatch ea.collection.item-removed after delete', async () => {
      container.innerHTML = buildSortableCollectionHTML({ numItems: 1 });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const spy = vi.fn();
      document.addEventListener('ea.collection.item-removed', spy);

      container.querySelector('.field-sortable_collection-delete-button').click();
      await new Promise(r => setTimeout(r, 0));

      expect(spy).toHaveBeenCalledTimes(1);
      document.removeEventListener('ea.collection.item-removed', spy);
    });
  });

  describe('CSS first/last classes', () => {
    it('should add first/last classes to sortable collection items', async () => {
      container.innerHTML = buildSortableCollectionHTML({ numItems: 3 });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const items = container.querySelectorAll('.field-sortable_collection-item');
      expect(items[0].classList.contains('field-sortable_collection-item-first')).toBe(true);
      expect(items[2].classList.contains('field-sortable_collection-item-last')).toBe(true);
      expect(items[1].classList.contains('field-sortable_collection-item-first')).toBe(false);
    });
  });

  describe('Sortable init', () => {
    it('should call Sortable.create with the correct container', async () => {
      container.innerHTML = buildSortableCollectionHTML({ numItems: 2 });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      expect(Sortable.create).toHaveBeenCalled();
      const callArgs = Sortable.create.mock.calls[0];
      expect(callArgs[0].id).toBe('form_items');
      expect(callArgs[1].handle).toBe('[drag-handler="form_items"]');
      expect(callArgs[1].direction).toBe('vertical');
    });
  });

  describe('Position reindex', () => {
    it('should reindex input names after updateCollectionItemCssClasses', async () => {
      container.innerHTML = buildSortableCollectionHTML({
        numItems: 3,
        fullName: 'form[items]',
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const items = container.querySelectorAll('.field-sortable_collection-item');
      // The source uses hasToIncrement logic: if the first item starts at index 0,
      // it increments by 1 (so indices become 1, 2, 3...). This matches the source behavior.
      items.forEach((item, idx) => {
        const valueInput = item.querySelector('[name*="[value]"]');
        expect(valueInput).not.toBeNull();
        // After reindex, names follow sequential order (1-based when hasToIncrement is true)
        expect(valueInput.name).toContain(`[${idx + 1}]`);
      });
    });
  });

  describe('Nested levels', () => {
    it('should set data-level on collection based on parent collections', async () => {
      container.innerHTML = `
        <div data-ea-collection-field="true" data-num-items="1" data-form-type-name-placeholder="__name__" data-form-type-id="outer" data-form-type-parent-id="root" data-ea-collection-field-full-name="form[outer]" data-prototype="">
          <div class="ea-form-collection-items">
            <div class="accordion"><div class="form-widget-compound" id="outer">
              <div class="form-group">
                <div class="field-sortable_collection-item" data-form-type-parent-id="root">
                  ${buildSortableCollectionHTML({ numItems: 1, formTypeId: 'inner', parentId: 'outer' })}
                </div>
              </div>
            </div></div>
          </div>
          <button type="button" class="field-sortable_collection-add-button">Add Outer</button>
        </div>
      `;

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const innerCollection = container.querySelector('[data-form-type-id="inner"]')?.closest('[data-ea-collection-field]');
      if (innerCollection) {
        expect(parseInt(innerCollection.dataset.level)).toBeGreaterThanOrEqual(1);
      }
    });
  });

  describe('Processed guard', () => {
    it('should mark button as processed after init', async () => {
      const prototype = '<div class=&quot;form-group&quot;><div class=&quot;field-sortable_collection-item&quot; data-form-type-parent-id=&quot;form&quot;><input name=&quot;form[items][__name__][value]&quot;></div></div>';
      container.innerHTML = buildSortableCollectionHTML({
        numItems: 0,
        prototype,
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const addButton = container.querySelector('.field-sortable_collection-add-button');
      expect(addButton.classList.contains('processed')).toBe(true);
    });

    it('should enable disabled add buttons', async () => {
      container.innerHTML = buildSortableCollectionHTML({ numItems: 0 });
      const addButton = container.querySelector('.field-sortable_collection-add-button');
      addButton.disabled = true;

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      expect(addButton.disabled).toBe(false);
    });
  });

  describe('Empty collection badge', () => {
    it('should replace .collection-empty when adding to empty sortable collection', async () => {
      const prototype = '<div class=&quot;form-group&quot;><div class=&quot;field-sortable_collection-item&quot; data-form-type-parent-id=&quot;form&quot;><div class=&quot;accordion-item&quot;><h2><button class=&quot;accordion-button collapsed&quot;>New</button></h2><div class=&quot;accordion-collapse collapse&quot;><div class=&quot;accordion-body&quot;><input name=&quot;form[items][__name__][value]&quot;></div></div></div></div></div>';

      container.innerHTML = `
        <div data-ea-collection-field="true"
             data-num-items="0"
             data-form-type-name-placeholder="__name__"
             data-form-type-id="form_items"
             data-form-type-parent-id="form"
             data-ea-collection-field-full-name="form[items]"
             data-prototype="${prototype}">
          <div>
            <div><span class="collection-empty">Empty</span></div>
            <button type="button" class="field-sortable_collection-add-button">Add</button>
          </div>
        </div>
      `;

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      container.querySelector('.field-sortable_collection-add-button').click();
      await new Promise(r => setTimeout(r, 50));

      expect(container.querySelector('.collection-empty')).toBeNull();
      expect(container.querySelector('.ea-form-collection-items')).not.toBeNull();
    });
  });

  describe('Event dispatch', () => {
    it('should dispatch ea.collection.item-added after adding', async () => {
      const prototype = '<div class=&quot;form-group&quot;><div class=&quot;field-sortable_collection-item&quot; data-form-type-parent-id=&quot;form&quot;><div class=&quot;accordion-item&quot;><h2><button class=&quot;accordion-button collapsed&quot;>New</button></h2><div class=&quot;accordion-collapse collapse&quot;><div class=&quot;accordion-body&quot;><input name=&quot;form[items][__name__][value]&quot;></div></div></div></div></div>';
      container.innerHTML = buildSortableCollectionHTML({
        numItems: 0,
        prototype,
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const spy = vi.fn();
      document.addEventListener('ea.collection.item-added', spy);

      container.querySelector('.field-sortable_collection-add-button').click();
      await new Promise(r => setTimeout(r, 50));

      expect(spy).toHaveBeenCalled();
      document.removeEventListener('ea.collection.item-added', spy);
    });
  });

  describe('Accordion expand', () => {
    it('should expand the last added item', async () => {
      const prototype = '<div class=&quot;form-group&quot;><div class=&quot;field-sortable_collection-item&quot; data-form-type-parent-id=&quot;form&quot;><div class=&quot;accordion-item&quot;><h2><button class=&quot;accordion-button collapsed&quot;>New</button></h2><div class=&quot;accordion-collapse collapse&quot;><div class=&quot;accordion-body&quot;><input name=&quot;form[items][__name__][value]&quot;></div></div></div></div></div>';
      container.innerHTML = buildSortableCollectionHTML({
        numItems: 1,
        prototype,
        placeholder: '__name__',
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      container.querySelector('.field-sortable_collection-add-button').click();
      await new Promise(r => setTimeout(r, 50));

      const items = container.querySelectorAll('.field-sortable_collection-item');
      const lastItem = items[items.length - 1];
      const collapseButton = lastItem.querySelector('.accordion-button');
      const collapseBody = lastItem.querySelector('.accordion-collapse');

      expect(collapseButton.classList.contains('collapsed')).toBe(false);
      expect(collapseBody.classList.contains('show')).toBe(true);
    });
  });
});
