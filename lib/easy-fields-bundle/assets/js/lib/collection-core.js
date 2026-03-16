/**
 * Replace prototype placeholders with the actual index.
 * @param {string} prototype - HTML prototype string with placeholders
 * @param {string} placeholder - The placeholder string (e.g., '__name__')
 * @param {number} index - The index to replace with
 * @returns {string}
 */
export function replacePrototypePlaceholders(prototype, placeholder, index) {
  const labelRegexp = new RegExp(placeholder + 'label__', 'g');
  const nameRegexp = new RegExp(placeholder, 'g');
  return prototype.replace(labelRegexp, index).replace(nameRegexp, index);
}

/**
 * Remove the empty collection badge and insert the collection wrapper.
 * @param {HTMLElement} badgeParent - Parent element containing the .collection-empty badge
 * @param {boolean} isArray - Whether this is an array (simple) collection
 * @param {Object} emptyHtml - HTML to replace the badge with
 * @param {string} emptyHtml.array - HTML for array collections
 * @param {string} emptyHtml.complex - HTML for complex collections
 */
export function removeEmptyBadge(badgeParent, isArray, emptyHtml) {
  const badge = badgeParent.querySelector('.collection-empty');
  if (badge !== null) {
    badge.parentElement.outerHTML = isArray ? emptyHtml.array : emptyHtml.complex;
  }
}

/**
 * Update first/last CSS classes on collection items.
 * @param {HTMLElement} collection - The collection container
 * @param {string} itemSelector - CSS selector for items (e.g., '.field-collection-item')
 * @param {string} classPrefix - Prefix for first/last classes (e.g., 'field-collection-item')
 */
export function updateFirstLastClasses(collection, itemSelector, classPrefix) {
  if (collection === null) return;

  const items = collection.querySelectorAll(itemSelector);
  const firstClass = `${classPrefix}-first`;
  const lastClass = `${classPrefix}-last`;

  items.forEach((item) => item.classList.remove(firstClass, lastClass));

  if (items[0] !== undefined) {
    items[0].classList.add(firstClass);
  }
  if (items[items.length - 1] !== undefined) {
    items[items.length - 1].classList.add(lastClass);
  }
}

/**
 * Expand (un-collapse) the last accordion item in a collection wrapper.
 * @param {HTMLElement} wrapper - The collection items wrapper
 * @param {string} itemSelector - CSS selector for items
 */
export function expandLastAccordionItem(wrapper, itemSelector) {
  const items = wrapper.querySelectorAll(itemSelector);
  if (items.length === 0) return;

  const lastElement = items[items.length - 1];
  const collapseButton = lastElement.querySelector('.accordion-button');
  if (collapseButton) collapseButton.classList.remove('collapsed');
  const collapseBody = lastElement.querySelector('.accordion-collapse');
  if (collapseBody) collapseBody.classList.add('show');
}

/**
 * Create a click handler for adding items to a collection.
 * @param {Object} config
 * @param {string} config.itemSelector - CSS selector for items
 * @param {string} config.classPrefix - Prefix for first/last CSS classes
 * @param {Object} config.emptyBadgeHtml - HTML for empty badge replacement
 * @param {string} config.emptyBadgeHtml.array - HTML for array collections
 * @param {string} config.emptyBadgeHtml.complex - HTML for complex collections
 * @param {Object} config.insertionSelector - Selectors to find the items wrapper
 * @param {string} config.insertionSelector.array - Selector for array collections
 * @param {string} config.insertionSelector.complex - Selector for complex collections
 * @param {string[]} config.events - Event names to dispatch after adding
 * @param {Function} config.insertHtml - Function to insert HTML (e.g., insertHtmlWithScripts)
 * @param {Function} [config.afterAdd] - Called after item is added (collection, wrapper) => void
 * @param {'button'|'collection'} [config.prototypeSource='collection'] - Where to read data-prototype
 * @returns {Function} Click handler function (call with addButton, collection)
 */
export function createAddHandler(config) {
  const {
    itemSelector,
    classPrefix,
    emptyBadgeHtml,
    insertionSelector,
    events,
    insertHtml,
    afterAdd,
    prototypeSource = 'collection',
  } = config;

  return function attachAddHandler(addButton, collection) {
    addButton.addEventListener('click', function () {
      const isArray = collection.classList.contains('field-array');
      let numItems = parseInt(collection.dataset.numItems);

      // Remove empty badge
      const badgeParent =
        prototypeSource === 'button' ? collection : this.parentElement;
      removeEmptyBadge(badgeParent, isArray, emptyBadgeHtml);

      // Get prototype and placeholder from the right source
      const source = prototypeSource === 'button' ? addButton : collection;
      const placeholder = source.dataset.formTypeNamePlaceholder;
      const prototype = source.dataset.prototype;

      const newItemHtml = replacePrototypePlaceholders(
        prototype,
        placeholder,
        prototypeSource === 'button' ? numItems : ++numItems,
      );

      if (prototypeSource === 'button') {
        collection.dataset.numItems = ++numItems;
      } else {
        collection.dataset.numItems = numItems;
      }

      const selector = isArray
        ? insertionSelector.array
        : insertionSelector.complex;
      const wrapper = collection.querySelector(selector);

      insertHtml(wrapper, newItemHtml).then(() => {
        if (!isArray) {
          updateFirstLastClasses(collection, itemSelector, classPrefix);
          if (afterAdd) afterAdd(collection, wrapper);
          expandLastAccordionItem(wrapper, itemSelector);
        }

        events.forEach((eventName) => {
          document.dispatchEvent(new Event(eventName));
        });
      });
    });
  };
}
