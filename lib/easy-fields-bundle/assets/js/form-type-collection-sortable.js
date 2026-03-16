import { insertHtmlWithScripts } from './lib/dom-utils.js';
import {
  createAddHandler,
  updateFirstLastClasses,
} from './lib/collection-core.js';
import { initSortable, reindexInputNames } from './lib/sortable-utils.js';

const ITEM_SELECTOR = '.field-sortable_collection-item';
const CLASS_PREFIX = 'field-sortable_collection-item';

function queryParentsSelectorAll(selector, elm) {
  const result = [];
  for (let parent = elm.parentElement; parent != null; parent = parent.parentElement) {
    if (!selector || (parent.matches && parent.matches(selector))) {
      result.push(parent);
    }
  }
  return result;
}

function updateCollectionItemCssClasses(collection) {
  if (collection === null) return;

  const parentId = collection.dataset.formTypeParentId;
  const itemSelectorWithParent = `${ITEM_SELECTOR}[data-form-type-parent-id="${parentId}"]`;
  const fullName = collection.dataset.eaCollectionFieldFullName;

  reindexInputNames(collection, itemSelectorWithParent, fullName);
  updateFirstLastClasses(collection, itemSelectorWithParent, CLASS_PREFIX);
}

function updateCollectionSortable(collection) {
  if (collection === null) return;

  const formTypeId = collection.dataset.formTypeId;
  const container = document.getElementById(formTypeId);
  if (!container) return;

  initSortable(collection, container, `[drag-handler="${formTypeId}"]`, function () {
    updateCollectionItemCssClasses(collection);
  });
}

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
  afterAdd: (collection) => {
    updateCollectionItemCssClasses(collection);
    updateCollectionSortable(collection);
  },
});

const eaSortableCollectionHandler = function () {
  document
    .querySelectorAll('button.field-sortable_collection-add-button:not(.processed)')
    .forEach((addButton) => {
      const collection = addButton.closest('[data-ea-collection-field]');

      if (!collection || addButton.classList.contains('processed')) {
        return;
      }

      collection.dataset.level = queryParentsSelectorAll(
        '[data-ea-collection-field]',
        addButton,
      ).length;

      handleAdd(addButton, collection);
      updateCollectionItemCssClasses(collection);
      updateCollectionSortable(collection);

      addButton.classList.add('processed');
    });

  document
    .querySelectorAll('button.field-sortable_collection-add-button[disabled]')
    .forEach((addButton) => {
      addButton.disabled = false;
    });

  document
    .querySelectorAll('button.field-sortable_collection-delete-button')
    .forEach((deleteButton) => {
      deleteButton.addEventListener('click', () => {
        const collection = deleteButton.closest('[data-ea-collection-field]');

        deleteButton.closest('.form-group').remove();
        document.dispatchEvent(new Event('ea.collection.item-removed'));

        updateCollectionItemCssClasses(collection);
        updateCollectionSortable(collection);
      });
    });
};

window.addEventListener('DOMContentLoaded', eaSortableCollectionHandler);
document.addEventListener('ea.collection.item-added', eaSortableCollectionHandler);
