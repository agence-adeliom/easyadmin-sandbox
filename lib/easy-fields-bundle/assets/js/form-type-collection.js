import { insertHtmlWithScripts } from './lib/dom-utils.js';
import {
  createAddHandler,
  updateFirstLastClasses,
} from './lib/collection-core.js';

const ITEM_SELECTOR = '.field-collection-item';
const CLASS_PREFIX = 'field-collection-item';

const handleAdd = createAddHandler({
  itemSelector: ITEM_SELECTOR,
  classPrefix: CLASS_PREFIX,
  emptyBadgeHtml: {
    array: '<div class="ea-form-collection-items"></div>',
    complex:
      '<div class="ea-form-collection-items"><div class="accordion"><div class="form-widget-compound"></div></div></div>',
  },
  insertionSelector: {
    array: '.ea-form-collection-items',
    complex: '.ea-form-collection-items .accordion > .form-widget-compound',
  },
  events: ['ea.collection.item-added'],
  insertHtml: insertHtmlWithScripts,
});

const eaCollectionHandler = function () {
  document
    .querySelectorAll('button.field-collection-add-button')
    .forEach((addButton) => {
      const collection = addButton.closest('[data-ea-collection-field]');

      if (!collection || collection.classList.contains('processed')) {
        return;
      }

      handleAdd(addButton, collection);
      updateFirstLastClasses(collection, ITEM_SELECTOR, CLASS_PREFIX);

      collection.classList.add('processed');
    });

  document
    .querySelectorAll('button.field-collection-delete-button')
    .forEach((deleteButton) => {
      deleteButton.addEventListener('click', () => {
        const collection = deleteButton.closest('[data-ea-collection-field]');
        const item = deleteButton.closest(ITEM_SELECTOR);

        item.remove();
        document.dispatchEvent(new Event('ea.collection.item-removed'));

        updateFirstLastClasses(collection, ITEM_SELECTOR, CLASS_PREFIX);
      });
    });
};

window.addEventListener('DOMContentLoaded', eaCollectionHandler);
document.addEventListener('ea.collection.item-added', eaCollectionHandler);
