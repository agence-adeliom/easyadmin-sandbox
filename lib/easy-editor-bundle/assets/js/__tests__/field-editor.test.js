import { describe, it, expect, beforeEach, vi, afterEach } from 'vitest';

// Mock sortablejs before importing source
const mockSortableInstance = { destroy: vi.fn() };
vi.mock('sortablejs', () => ({
  default: {
    create: vi.fn(() => mockSortableInstance),
  },
}));

// Import the source file — registers DOMContentLoaded + ea.editor.item-added + ea.collection.item-added handlers
import '../field-editor.js';

function buildEditorHTML({ numItems = 0, prototype = '', placeholder = '__name__', formName = 'Entity' } = {}) {
  let itemsHtml = '';
  for (let i = 0; i < numItems; i++) {
    itemsHtml += `
      <div class="field-collection-item field-collection-item-complex border rounded mb-4">
        <div class="accordion-item">
          <h2 class="accordion-header d-flex p-0">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${formName}_content_${i}-contents">
              <i class="fas fw fa-chevron-right form-collection-item-collapse-marker"></i>
              <span class="accordion-title">Block ${i}</span>
            </button>
            <div class="accordion-actions d-flex">
              <button type="button" class="btn btn-link btn-link-danger field-editor-remove-button"><i class="far fa-trash-alt"></i></button>
              <button type="button" class="btn btn-link btn-link-secondary field-editor-drag-button" style="cursor:move;"><i class="fas fa-arrows-alt-v"></i></button>
            </div>
          </h2>
          <div id="${formName}_content_${i}-contents" class="accordion-collapse collapse border-top">
            <div class="accordion-body">
              <input name="${formName}[content][${i}][value]" value="block${i}">
              <input name="${formName}[content][${i}][position]" value="${i}">
            </div>
          </div>
        </div>
      </div>`;
  }

  return `
    <form name="${formName}" class="ea-edit-form">
      <div data-ea-collection-field="true" data-num-items="${numItems}" data-entry-is-complex="true">
        <div class="row">
          <div class="col-8">
            <div class="ea-form-collection-items editor-collection">
              <div class="accordion border-0 shadow-none">
                <div class="form-widget-compound">
                  <div>
                    ${itemsHtml}
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-4">
            <div class="field-editor-blocks">
              <div class="card">
                <h5 class="card-header">Text Block</h5>
                <div class="card-body">
                  <button type="button" class="btn btn-primary field-editor-add-button field-editor-choose-button"
                          data-block-type="text"
                          data-prototype='${prototype}'
                          data-num-items='${numItems}'
                          data-form-type-name-placeholder='${placeholder}'>
                    <span>Add</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  `;
}

describe('field-editor.js', () => {
  let container;
  let Sortable;

  beforeEach(async () => {
    container = document.createElement('div');
    document.body.appendChild(container);
    document.querySelectorAll('.processed').forEach(el => el.classList.remove('processed'));

    const sortableModule = await import('sortablejs');
    Sortable = sortableModule.default;
    Sortable.create.mockClear();
    mockSortableInstance.destroy.mockClear();
  });

  afterEach(() => {
    container.remove();
    vi.restoreAllMocks();
    delete window.CKEDITOR;
  });

  describe('Add item', () => {
    it('should add a new block item on button click', async () => {
      const prototype = '<div class=&quot;field-collection-item field-collection-item-complex border rounded mb-4&quot;><div class=&quot;accordion-item&quot;><h2 class=&quot;accordion-header d-flex p-0&quot;><button class=&quot;accordion-button collapsed&quot; type=&quot;button&quot;>New</button><div class=&quot;accordion-actions d-flex&quot;><button type=&quot;button&quot; class=&quot;btn btn-link btn-link-danger field-editor-remove-button&quot;><i class=&quot;far fa-trash-alt&quot;></i></button><button type=&quot;button&quot; class=&quot;btn btn-link btn-link-secondary field-editor-drag-button&quot;><i class=&quot;fas fa-arrows-alt-v&quot;></i></button></div></h2><div class=&quot;accordion-collapse collapse border-top&quot;><div class=&quot;accordion-body&quot;><input name=&quot;Entity[content][__name__][value]&quot;><input name=&quot;Entity[content][__name__][position]&quot; value=&quot;0&quot;></div></div></div></div>';

      container.innerHTML = buildEditorHTML({
        numItems: 1,
        prototype,
        placeholder: '__name__',
        formName: 'Entity',
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      container.querySelector('.field-editor-add-button').click();
      await new Promise(r => setTimeout(r, 50));

      const items = container.querySelectorAll('.field-collection-item');
      expect(items.length).toBe(2);
    });

    it('should increment data-num-items', async () => {
      const prototype = '<div class=&quot;field-collection-item&quot;><div class=&quot;accordion-item&quot;><h2><button class=&quot;accordion-button collapsed&quot;>New</button></h2><div class=&quot;accordion-collapse collapse&quot;><div class=&quot;accordion-body&quot;><input name=&quot;Entity[content][__name__][value]&quot;></div></div></div></div>';

      container.innerHTML = buildEditorHTML({
        numItems: 0,
        prototype,
        placeholder: '__name__',
        formName: 'Entity',
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const collection = container.querySelector('[data-ea-collection-field]');
      expect(collection.dataset.numItems).toBe('0');

      container.querySelector('.field-editor-add-button').click();
      await new Promise(r => setTimeout(r, 50));

      expect(collection.dataset.numItems).toBe('1');
    });
  });

  describe('Delete item', () => {
    it('should remove the parent .form-group on delete click', async () => {
      // Editor uses .form-group as the closest parent to remove
      const editorHtml = `
        <form name="Entity" class="ea-edit-form">
          <div data-ea-collection-field="true" data-num-items="2">
            <div class="ea-form-collection-items editor-collection">
              <div class="accordion border-0 shadow-none">
                <div class="form-widget-compound"><div>
                  <div class="form-group">
                    <div class="field-collection-item">
                      <button type="button" class="field-editor-remove-button">Delete</button>
                      <input name="Entity[content][0][position]" value="0">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="field-collection-item">
                      <button type="button" class="field-editor-remove-button">Delete</button>
                      <input name="Entity[content][1][position]" value="1">
                    </div>
                  </div>
                </div></div>
              </div>
            </div>
          </div>
        </form>
      `;
      container.innerHTML = editorHtml;

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      container.querySelectorAll('.field-editor-remove-button')[0].click();
      await new Promise(r => setTimeout(r, 0));

      const items = container.querySelectorAll('.field-collection-item');
      expect(items.length).toBe(1);
    });

    it('should dispatch ea.editor.item-removed after delete', async () => {
      const editorHtml = `
        <form name="Entity" class="ea-edit-form">
          <div data-ea-collection-field="true" data-num-items="1">
            <div class="ea-form-collection-items editor-collection">
              <div class="accordion border-0 shadow-none">
                <div class="form-widget-compound"><div>
                  <div class="form-group">
                    <div class="field-collection-item">
                      <button type="button" class="field-editor-remove-button">Delete</button>
                    </div>
                  </div>
                </div></div>
              </div>
            </div>
          </div>
        </form>
      `;
      container.innerHTML = editorHtml;

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const spy = vi.fn();
      document.addEventListener('ea.editor.item-removed', spy);

      container.querySelector('.field-editor-remove-button').click();
      await new Promise(r => setTimeout(r, 0));

      expect(spy).toHaveBeenCalledTimes(1);
      document.removeEventListener('ea.editor.item-removed', spy);
    });
  });

  describe('Events', () => {
    it('should dispatch both ea.editor.item-added and ea.collection.item-added after add', async () => {
      const prototype = '<div class=&quot;field-collection-item&quot;><div class=&quot;accordion-item&quot;><h2><button class=&quot;accordion-button collapsed&quot;>New</button></h2><div class=&quot;accordion-collapse collapse&quot;><div class=&quot;accordion-body&quot;><input name=&quot;Entity[content][__name__][value]&quot;></div></div></div></div>';

      container.innerHTML = buildEditorHTML({
        numItems: 0,
        prototype,
        placeholder: '__name__',
        formName: 'Entity',
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const editorSpy = vi.fn();
      const collectionSpy = vi.fn();
      document.addEventListener('ea.editor.item-added', editorSpy);
      document.addEventListener('ea.collection.item-added', collectionSpy);

      container.querySelector('.field-editor-add-button').click();
      await new Promise(r => setTimeout(r, 50));

      expect(editorSpy).toHaveBeenCalled();
      expect(collectionSpy).toHaveBeenCalled();

      document.removeEventListener('ea.editor.item-added', editorSpy);
      document.removeEventListener('ea.collection.item-added', collectionSpy);
    });

    it('should dispatch ea.editor.item-loaded on DOMContentLoaded', async () => {
      container.innerHTML = buildEditorHTML({ numItems: 0 });

      const spy = vi.fn();
      document.addEventListener('ea.editor.item-loaded', spy);

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      expect(spy).toHaveBeenCalled();
      document.removeEventListener('ea.editor.item-loaded', spy);
    });
  });

  describe('CKEditor reset', () => {
    it('should call setMode on CKEDITOR instances when present', async () => {
      const mockSetMode = vi.fn();
      window.CKEDITOR = {
        instances: {
          editor1: { setMode: mockSetMode, mode: 'source' },
        },
      };

      container.innerHTML = buildEditorHTML({ numItems: 0 });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      expect(mockSetMode).toHaveBeenCalledWith('wysiwyg', expect.any(Function));
    });

    it('should not throw when CKEDITOR is not defined', async () => {
      delete window.CKEDITOR;
      container.innerHTML = buildEditorHTML({ numItems: 0 });

      expect(() => {
        window.dispatchEvent(new Event('DOMContentLoaded'));
      }).not.toThrow();
    });
  });

  describe('CSS first/last classes', () => {
    it('should add first/last classes to editor items', async () => {
      container.innerHTML = buildEditorHTML({ numItems: 3, formName: 'Entity' });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const items = container.querySelectorAll('.field-collection-item');
      expect(items[0].classList.contains('field-collection-item-first')).toBe(true);
      expect(items[2].classList.contains('field-collection-item-last')).toBe(true);
    });
  });

  describe('Position inputs', () => {
    it('should update position inputs based on item order', async () => {
      container.innerHTML = buildEditorHTML({ numItems: 3, formName: 'Entity' });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const items = container.querySelectorAll('.field-collection-item');
      items.forEach((item, idx) => {
        const posInput = item.querySelector('[name*="[position]"]');
        if (posInput) {
          expect(posInput.value).toBe(String(idx));
        }
      });
    });
  });

  describe('Sortable init', () => {
    it('should have the sortable container present in editor DOM', async () => {
      container.innerHTML = buildEditorHTML({ numItems: 2, formName: 'Entity' });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      // Verify the sortable container selector matches the DOM
      const collection = container.querySelector('[data-ea-collection-field]');
      const sortableContainer = collection.querySelector(
        '.ea-form-collection-items .accordion > .form-widget-compound > div',
      );
      expect(sortableContainer).not.toBeNull();

      // Verify drag handles are present
      const dragButtons = collection.querySelectorAll('.field-editor-drag-button');
      expect(dragButtons.length).toBe(2);
    });
  });

  describe('Processed guard', () => {
    it('should mark add button as processed', async () => {
      const prototype = '<div class=&quot;field-collection-item&quot;><input name=&quot;Entity[content][__name__][value]&quot;></div>';
      container.innerHTML = buildEditorHTML({
        numItems: 0,
        prototype,
        placeholder: '__name__',
        formName: 'Entity',
      });

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      const addButton = container.querySelector('.field-editor-add-button');
      expect(addButton.classList.contains('processed')).toBe(true);
    });
  });

  describe('Empty collection badge', () => {
    it('should replace .collection-empty when adding to empty editor', async () => {
      const prototype = '<div class=&quot;field-collection-item&quot;><div class=&quot;accordion-item&quot;><h2><button class=&quot;accordion-button collapsed&quot;>New</button></h2><div class=&quot;accordion-collapse collapse&quot;><div class=&quot;accordion-body&quot;><input name=&quot;Entity[content][__name__][value]&quot;></div></div></div></div>';

      container.innerHTML = `
        <form name="Entity" class="ea-edit-form">
          <div data-ea-collection-field="true" data-num-items="0">
            <div class="row">
              <div class="col-8">
                <div class="ea-form-collection-items editor-collection">
                  <span class="collection-empty">Empty</span>
                </div>
              </div>
              <div class="col-4">
                <button type="button" class="btn btn-primary field-editor-add-button"
                        data-prototype='${prototype}'
                        data-num-items='0'
                        data-form-type-name-placeholder='__name__'>
                  <span>Add</span>
                </button>
              </div>
            </div>
          </div>
        </form>
      `;

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      container.querySelector('.field-editor-add-button').click();
      await new Promise(r => setTimeout(r, 50));

      expect(container.querySelector('.collection-empty')).toBeNull();
      expect(container.querySelector('.ea-form-collection-items')).not.toBeNull();
    });
  });

  describe('Enable disabled buttons', () => {
    it('should enable disabled add buttons on init', async () => {
      container.innerHTML = buildEditorHTML({ numItems: 0 });
      const addButton = container.querySelector('.field-editor-add-button');
      addButton.disabled = true;

      window.dispatchEvent(new Event('DOMContentLoaded'));
      await new Promise(r => setTimeout(r, 0));

      expect(addButton.disabled).toBe(false);
    });
  });
});
