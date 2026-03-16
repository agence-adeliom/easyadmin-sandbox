import Modal from 'bootstrap/js/src/modal';

const SELECTION_CLASS = 'table-primary';
const DATA_PROPS = 'ea-ajax-index-url';

let selection = [];
let field = null;
let button = null;
let columns = null;
let modal = null;
let modalToggle = null;
let url = null;

function isMultiple() {
  return field.multiple;
}

function getRowName(row) {
  if (columns && Array.isArray(columns.columns)) {
    const values = [];
    for (const col of columns.columns) {
      const val = row.querySelector(`td:nth-child(${col})`)?.textContent?.trim();
      if (val) values.push(val);
    }
    const separator = columns.separator || '-';
    return values.join(` ${separator} `);
  }
  return row.querySelector('td:first-child')?.textContent?.trim() || '';
}

function cancel() {
  selection = [];
  modalToggle.hide();
}

function selectValues() {
  if (selection.length) {
    for (const id of selection) {
      const row = modal.querySelector(`[data-id="${id}"]`);
      const name = getRowName(row);

      if (field.dataset.eaWidget === 'ea-autocomplete') {
        const instance = field.tomselect;
        instance.addOption({
          [instance.settings.valueField]: id,
          [instance.settings.labelField]: name || '#' + id,
        });
        instance.refreshOptions(false);
        instance.addItem(id);
        instance.refreshItems();
      }

      if (!isMultiple()) break;
    }
  }
  modalToggle.hide();
}

function selectRow(row) {
  const id = parseInt(row.dataset.id) || row.dataset.id;

  if (selection.indexOf(id) === -1) {
    if (isMultiple()) {
      selection.push(id);
    } else {
      selection = [id];
    }
  } else {
    unselectRow(row);
  }
  manageSelectionDisplay();
}

function unselectRow(row) {
  const id = parseInt(row.dataset.id) || row.dataset.id;
  const idx = selection.indexOf(id);
  if (idx !== -1) {
    selection.splice(idx, 1);
  }
}

function manageSelectionDisplay() {
  const rows = modal.querySelectorAll('tr');
  rows.forEach((row) => {
    row.classList.remove(SELECTION_CLASS);
    const checkbox = row.querySelector('input[type="checkbox"]');
    if (checkbox) checkbox.checked = false;
  });

  for (const id of selection) {
    const row = modal.querySelector(`[data-id="${id}"]`);
    if (row) {
      row.classList.add(SELECTION_CLASS);
      const checkbox = row.querySelector('input[type="checkbox"]');
      if (checkbox) checkbox.checked = true;
    }
  }
}

function addSelectors(container) {
  container.querySelectorAll('tr').forEach((row) => {
    const actionsCell = row.querySelector('.actions');
    if (actionsCell) {
      actionsCell.innerHTML = '<input type="checkbox" style="pointer-events: none" />';
    }
    row.style.cursor = 'pointer';
  });
}

function buildFooter(listButton) {
  const cancelLabel = listButton.dataset.cancelLabel;
  const validateLabel = listButton.dataset.validateLabel;

  return `
    <button class="btn btn-secondary" js-list-cancel>
      <span class="btn-label"><i class="action-icon fa"></i> ${cancelLabel}</span>
    </button>
    <button class="btn btn-primary" js-list-select>
      <span class="btn-label"><i class="action-icon fa"></i> ${validateLabel}</span>
    </button>
  `;
}

function serialize(obj, prefix) {
  const parts = [];
  for (const p in obj) {
    if (obj.hasOwnProperty(p)) {
      const k = prefix ? prefix + '[' + p + ']' : p;
      const v = obj[p];
      parts.push(
        v !== null && typeof v === 'object'
          ? serialize(v, k)
          : encodeURIComponent(k) + '=' + encodeURIComponent(v),
      );
    }
  }
  return parts.join('&');
}

// Re-execute scripts in dynamically loaded HTML
function setInnerHTML(element, htmlContent) {
  element.innerHTML = htmlContent;
  Array.from(element.querySelectorAll('script')).forEach((oldScript) => {
    const newScript = document.createElement('script');
    Array.from(oldScript.attributes).forEach((attr) =>
      newScript.setAttribute(attr.name, attr.value),
    );
    newScript.appendChild(document.createTextNode(oldScript.innerHTML));
    oldScript.parentNode.replaceChild(newScript, oldScript);
  });
}

function populateModalBody(htmlString) {
  const parser = new DOMParser();
  const doc = parser.parseFromString(htmlString, 'text/html');
  const body = doc.querySelector('.content-body');
  if (!body) return;

  const options = [
    { data: 'showFilter', className: 'datagrid-filters' },
    { data: 'showSearch', className: 'datagrid-search' },
  ];

  for (const option of options) {
    const showOption = button.dataset[option.data];
    if (!showOption || showOption === 'false') {
      const el = body.querySelector('.' + option.className);
      if (el) el.remove();
    }
  }

  addSelectors(body);
  modal.querySelector('.modal-body').innerHTML = '';
  modal.querySelector('.modal-body').appendChild(body);
  manageSelectionDisplay();
}

function handleListClickButton(listButton) {
  const iconElement = listButton.querySelector('.fa');
  const originalClass = iconElement?.className || '';
  if (iconElement) {
    iconElement.className = 'fa fa-spinner fa-spin pr-1';
  }
  listButton.disabled = true;

  let fetchUrl = url;
  const filtersAttr = listButton.dataset.filters;
  if (filtersAttr) {
    let filters;
    try {
      filters = JSON.parse(filtersAttr);
    } catch {
      filters = filtersAttr;
    }
    if (filters) {
      fetchUrl += '&' + serialize({ filters });
    }
  }

  if (fetchUrl) {
    fetch(fetchUrl)
      .then((response) => response.text())
      .then((data) => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');
        const title = doc.querySelector('.content-header-title .title')?.innerHTML || '';
        const footer = buildFooter(listButton);

        modal.querySelector('.modal-footer').innerHTML = footer;
        modal.querySelector('.modal-title').innerHTML = title;

        populateModalBody(data);

        listButton.disabled = false;
        if (iconElement) iconElement.className = originalClass;

        modalToggle.show();
      });
  }
}

function handleInnerLink(e, link) {
  const destUrl = link.getAttribute('href');
  const isModal = link.dataset.modal;

  if (!isModal) {
    e.preventDefault();

    fetch(destUrl)
      .then((response) => response.text())
      .then((data) => {
        populateModalBody(data);
      });
  } else {
    e.preventDefault();
    e.stopPropagation();

    const filterModal = document.querySelector(link.dataset.modal);
    const filterModalBody = filterModal.querySelector('.modal-body');

    new Modal(filterModal, { backdrop: false, keyboard: true }).show();
    filterModalBody.innerHTML =
      '<div class="fa-3x px-3 py-3 text-muted text-center"><i class="fas fa-circle-notch fa-spin"></i></div>';

    fetch(link.getAttribute('href'))
      .then((response) => response.text())
      .then((response) => {
        setInnerHTML(filterModalBody, response);

        // Fix close button (remove data-dismiss, use data-bs-dismiss)
        const header = filterModal.querySelector('.modal-header');
        if (header) {
          header.innerHTML = header.innerHTML.replace(/data-dismiss="modal"/g, '');
        }

        const applyButton = filterModal.querySelector('#modal-apply-button');
        if (applyButton) {
          applyButton.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            filterModal
              .querySelectorAll('.filter-checkbox:not(:checked)')
              .forEach((f) => f.closest('.filter-field')?.remove());
            const form = filterModalBody.querySelector('form');
            if (form) handleInnerFormSubmit(form);
          });
        }

        const clearButton = filterModal.querySelector('#modal-clear-button');
        if (clearButton) {
          clearButton.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            filterModal
              .querySelectorAll('.filter-field')
              .forEach((f) => f.remove());
            const form = filterModalBody.querySelector('form');
            if (form) handleInnerFormSubmit(form);
          });
        }

        const form = filterModal.querySelector('form');
        if (form) {
          form.addEventListener('submit', (e) => {
            e.preventDefault();
            e.stopPropagation();
            handleInnerFormSubmit(form);
          });
        }
      });
  }
}

function handleInnerFormSubmit(form) {
  const method = form.getAttribute('method') || 'get';
  const query = new URLSearchParams(new FormData(form)).toString();

  fetch(url + '&' + query, {
    method: method,
    cache: 'no-store',
  })
    .then((response) => response.text())
    .then((data) => {
      populateModalBody(data);
    });
}

function init(listButton) {
  const parent = listButton.closest('.form-widget');
  button = listButton;
  field = parent.querySelector('[data-ea-ajax-index-url]');
  modal = parent.querySelector('.create-entity-modal');
  modalToggle = new Modal(modal);
  url = field.dataset.eaAjaxIndexUrl;
  columns = listButton.dataset.columns ? JSON.parse(listButton.dataset.columns) : null;

  const val = field.value;
  selection = [];
  if (field.multiple && field.selectedOptions) {
    for (const opt of field.selectedOptions) {
      selection.push(parseInt(opt.value));
    }
  } else if (val) {
    selection = [val];
  }

  handleListClickButton(listButton);
}

window.addEventListener('DOMContentLoaded', function () {
  const modalBodySelector = '.create-entity-modal .content-body';

  document.body.addEventListener('click', (e) => {
    const listBtn = e.target.closest('[js-list-button]');
    if (listBtn) {
      e.preventDefault();
      init(listBtn);
      return;
    }

    const link = e.target.closest(`${modalBodySelector} a`);
    if (link) {
      handleInnerLink(e, link);
      return;
    }

    const row = e.target.closest(`${modalBodySelector} tr`);
    if (row) {
      e.preventDefault();
      selectRow(row);
      return;
    }

    const cancelBtn = e.target.closest('[js-list-cancel]');
    if (cancelBtn) {
      e.preventDefault();
      cancel();
      return;
    }

    const selectBtn = e.target.closest('[js-list-select]');
    if (selectBtn) {
      e.preventDefault();
      selectValues();
      return;
    }
  });

  document.body.addEventListener('submit', (e) => {
    const form = e.target.closest(`${modalBodySelector} form`);
    if (form) {
      e.preventDefault();
      e.stopPropagation();
      handleInnerFormSubmit(form);
    }
  });
});
