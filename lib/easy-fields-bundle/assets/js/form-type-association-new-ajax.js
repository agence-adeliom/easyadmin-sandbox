import Modal from 'bootstrap/js/src/modal';

function setModalContent(widget, data) {
  const field = widget.querySelector('[data-ea-ajax-new-endpoint-url]');
  const url = field.dataset.eaAjaxNewEndpointUrl;

  const parser = new DOMParser();
  const doc = parser.parseFromString(data, 'text/html');

  const title = doc.querySelector('.content-header-title .title')?.innerHTML || '';
  const formEl = doc.querySelector('.ea-new-form');
  const footerEl = doc.querySelector('.page-actions');

  // Remove "Save and Add Another" button
  const saveAndAdd = footerEl?.querySelector('[value="saveAndAddAnother"]');
  if (saveAndAdd) saveAndAdd.remove();

  const modal = widget.querySelector('.create-entity-modal');
  modal.querySelector('.modal-title').innerHTML = title;
  modal.querySelector('.modal-body').innerHTML = '';
  if (formEl) modal.querySelector('.modal-body').appendChild(formEl);
  modal.querySelector('.modal-footer').innerHTML = '';
  if (footerEl) modal.querySelector('.modal-footer').appendChild(footerEl);

  if (formEl) {
    formEl.addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(this);
      formData.append('ea[newForm][btn]', 'saveAndContinue');
      formData.append('fromModal', 1);
      const method = this.getAttribute('method') || 'post';

      fetch(url, {
        method: method,
        body: formData,
        headers: { 'X-Requested-With': 'AssociationField' },
      })
        .then((response) =>
          response.text().then((text) => ({
            text,
            headers: response.headers,
          })),
        )
        .then(({ text, headers }) => {
          const id = headers.get('x-crud-entity-id');
          const name = headers.get('x-crud-entity-name');

          if (id && name) {
            const instance = field.tomselect;
            instance.addOption({
              [instance.settings.valueField]: id,
              [instance.settings.labelField]: name,
            });
            instance.refreshOptions(false);
            instance.addItem(id);
            instance.refreshItems();
            modal.bsModal.hide();
          } else {
            setModalContent(widget, text);
          }
        });
    });
  }
}

function handleAddClickButton(addButton) {
  const parent = addButton.closest('.form-widget');
  const modal = parent.querySelector('.create-entity-modal');
  modal.bsModal = new Modal(modal);

  const field = parent.querySelector('[data-ea-ajax-new-endpoint-url]');
  const url = field.dataset.eaAjaxNewEndpointUrl;

  const iconElement = addButton.querySelector('.fa');
  const originalClass = iconElement?.className || '';
  if (iconElement) {
    iconElement.className = 'fa fa-spinner fa-spin pr-1';
  }
  addButton.disabled = true;

  if (url) {
    fetch(url)
      .then((response) => response.text())
      .then((data) => {
        setModalContent(parent, data);
        modal.bsModal.show();

        addButton.disabled = false;
        if (iconElement) iconElement.className = originalClass;
      });
  }
}

window.addEventListener('DOMContentLoaded', function () {
  document.body.addEventListener('click', (e) => {
    const btn = e.target.closest('[js-new-ajax-button]');
    if (btn) {
      e.preventDefault();
      handleAddClickButton(btn);
    }
  });
});
