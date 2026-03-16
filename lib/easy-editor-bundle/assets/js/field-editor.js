import { insertHtmlWithScripts, evalScript } from '@easy-fields/lib/dom-utils.js';
import {
  createAddHandler,
  updateFirstLastClasses,
  replacePrototypePlaceholders,
  removeEmptyBadge,
} from '@easy-fields/lib/collection-core.js';
import { initSortable, updatePositionInputs } from '@easy-fields/lib/sortable-utils.js';

const ITEM_SELECTOR = '.field-collection-item';
const CLASS_PREFIX = 'field-collection-item';

function updateCollectionItemCssClasses(collection) {
  if (collection === null) return;
  updatePositionInputs(collection, ITEM_SELECTOR);
  updateFirstLastClasses(collection, ITEM_SELECTOR, CLASS_PREFIX);
}

function updateCollectionSortable(collection) {
  if (collection === null) return;

  const container = collection.querySelector(
    '.ea-form-collection-items .accordion > .form-widget-compound > div',
  );
  if (!container) return;

  initSortable(collection, container, '.field-editor-drag-button', function () {
    updateCollectionItemCssClasses(collection);
  });
}

const eaEditorHandler = function (event) {
  document.querySelectorAll('button.field-editor-add-button').forEach((addButton) => {
    const collection = addButton.closest('[data-ea-collection-field]');
    if (!collection || addButton.classList.contains('processed')) {
      return;
    }

    // Editor uses button as prototype source
    addButton.addEventListener('click', function () {
      const isArrayCollection = collection.classList.contains('field-array');
      let numItems = parseInt(collection.dataset.numItems);

      // Remove empty badge
      const badge = collection.querySelector('.collection-empty');
      if (badge !== null) {
        badge.outerHTML = isArrayCollection
          ? '<div class="ea-form-collection-items"></div>'
          : '<div class="ea-form-collection-items"><div class="accordion border-0 shadow-none"><div class="form-widget-compound"><div></div></div></div></div>';
      }

      const placeholder = addButton.dataset.formTypeNamePlaceholder;
      const newItemHtml = replacePrototypePlaceholders(
        addButton.dataset.prototype,
        placeholder,
        numItems,
      );

      collection.dataset.numItems = ++numItems;
      const insertionSelector = isArrayCollection
        ? '.ea-form-collection-items'
        : '.ea-form-collection-items .accordion > .form-widget-compound > div';
      const collectionItemsWrapper = collection.querySelector(insertionSelector);

      insertHtmlWithScripts(collectionItemsWrapper, newItemHtml).then(() => {
        if (!isArrayCollection) {
          updateCollectionItemCssClasses(collection);
          updateCollectionSortable(collection);

          // Find last item in the right context (not a sub-collection item)
          const collectionItems = collectionItemsWrapper.querySelectorAll(ITEM_SELECTOR);
          const formName = this.closest('.ea-edit-form, .ea-new-form')?.getAttribute('name');
          let lastElement = null;
          let idx = collectionItems.length - 1;
          do {
            lastElement = collectionItems[idx];
            idx--;
          } while (
            lastElement &&
            lastElement.closest(`[id^="${formName}_content"]`) !== collectionItemsWrapper &&
            idx >= 0
          );

          if (lastElement) {
            const collapseButton = lastElement.querySelector('.accordion-button');
            if (collapseButton) collapseButton.classList.remove('collapsed');
            const collapseBody = lastElement.querySelector('.accordion-collapse');
            if (collapseBody) collapseBody.classList.add('show');
          }
        }

        // Re-execute inline scripts
        Array.from(collectionItemsWrapper.querySelectorAll('script')).forEach((oldScript) => {
          if (!oldScript.src) {
            evalScript(oldScript.innerHTML);
          }
        });

        document.dispatchEvent(new Event('ea.editor.item-added'));
        document.dispatchEvent(new Event('ea.collection.item-added'));
      });
    });

    addButton.classList.add('processed');

    updateCollectionItemCssClasses(collection);
    updateCollectionSortable(collection);
  });

  document.querySelectorAll('button.field-editor-add-button[disabled]').forEach((addButton) => {
    addButton.disabled = false;
  });

  document.querySelectorAll('button.field-editor-remove-button').forEach((deleteButton) => {
    deleteButton.addEventListener('click', () => {
      const collection = deleteButton.closest('[data-ea-collection-field]');

      deleteButton.closest('.form-group').remove();
      document.dispatchEvent(new Event('ea.editor.item-removed'));

      updateCollectionItemCssClasses(collection);
      updateCollectionSortable(collection);
    });
  });

  if (typeof CKEDITOR !== 'undefined' && CKEDITOR && CKEDITOR.instances) {
    for (const editorKey in CKEDITOR.instances) {
      const editor = CKEDITOR.instances[editorKey];
      editor.setMode('wysiwyg', function () {
        editor.mode = 'source';
      });
    }
  }

  if (event.type === 'DOMContentLoaded') {
    document.dispatchEvent(new Event('ea.editor.item-loaded'));
  }
};

window.addEventListener('DOMContentLoaded', eaEditorHandler);
document.addEventListener('ea.editor.item-added', eaEditorHandler);
document.addEventListener('ea.collection.item-added', eaEditorHandler);
