import Sortable from 'sortablejs';

/**
 * Initialize or re-initialize a Sortable instance on a container.
 * @param {HTMLElement} collection - The collection element (stores .sortable reference)
 * @param {string} containerSelector - CSS selector for the sortable container
 * @param {string} handleSelector - CSS selector for the drag handle
 * @param {Function} onEnd - Callback after drag ends
 */
export function initSortable(collection, containerSelector, handleSelector, onEnd) {
  if (collection === null) return;

  const container =
    typeof containerSelector === 'string'
      ? collection.querySelector(containerSelector)
      : containerSelector;

  if (!container) return;

  if (collection.sortable) {
    collection.sortable.destroy();
    collection.sortable = null;
  }

  collection.sortable = Sortable.create(container, {
    handle: handleSelector,
    direction: 'vertical',
    onEnd: function (evt) {
      onEnd(evt);
    },
  });
}

/**
 * Update position inputs after reordering.
 * Sets [name*="[position]"] input values to match item order.
 * @param {HTMLElement} collection - The collection element
 * @param {string} itemSelector - CSS selector for items
 */
export function updatePositionInputs(collection, itemSelector) {
  if (collection === null) return;

  const items = collection.querySelectorAll(itemSelector);
  items.forEach((item, key) => {
    item.querySelectorAll('[name]').forEach((input) => {
      if (input.name && input.name.includes('[position]')) {
        input.value = key;
      }
    });
  });
}

/**
 * Reindex input names in a sortable collection after reordering.
 * Updates the numeric index in input names like "form[items][0][value]" → "form[items][1][value]".
 * @param {HTMLElement} collection - The collection element
 * @param {string} itemSelector - CSS selector for items matching the correct parent level
 * @param {string} fullName - The full field name prefix (e.g., "form[items]")
 */
export function reindexInputNames(collection, itemSelector, fullName) {
  if (collection === null) return;

  const items = collection.querySelectorAll(itemSelector);
  let hasToIncrement = false;

  items.forEach((item, key) => {
    item.querySelectorAll('[name]').forEach((input) => {
      if (!input.name) return;

      const name = input.name.replace(fullName, '');
      const index = /^\[\d+\]/g.exec(name);
      if (index) {
        if (key === 0) {
          hasToIncrement = true;
        }
        const i = hasToIncrement ? key + 1 : key;
        const child = name.replace(index, '');
        input.name = `${fullName}[${i}]${child}`;
      }
    });
  });
}
