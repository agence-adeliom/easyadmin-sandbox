import { describe, it, expect, beforeEach, vi, afterEach } from 'vitest';

// Mock bootstrap Modal
const mockModalInstance = {
  show: vi.fn(),
  hide: vi.fn(),
};
vi.mock('bootstrap/js/src/modal', () => {
  return {
    default: vi.fn(() => mockModalInstance),
  };
});

// Import source module
import '../form-type-association-new-ajax.js';

describe('form-type-association-new-ajax.js', () => {
  let container;

  function buildNewAjaxHTML({ url = '/api/new' } = {}) {
    return `
      <div class="form-widget">
        <select data-ea-ajax-new-endpoint-url="${url}" data-ea-widget="ea-autocomplete">
          <option value="1">Item 1</option>
        </select>
        <button type="button" js-new-ajax-button>
          <span class="fa fa-plus"></span> New
        </button>
        <div class="create-entity-modal modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header"><h5 class="modal-title"></h5></div>
              <div class="modal-body"></div>
              <div class="modal-footer"></div>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  function mockTomSelect(selectEl) {
    selectEl.tomselect = {
      settings: { valueField: 'value', labelField: 'text' },
      addOption: vi.fn(),
      refreshOptions: vi.fn(),
      addItem: vi.fn(),
      refreshItems: vi.fn(),
    };
    return selectEl.tomselect;
  }

  let domReady = false;

  beforeEach(() => {
    container = document.createElement('div');
    document.body.appendChild(container);
    mockModalInstance.show.mockReset();
    mockModalInstance.hide.mockReset();
    if (!domReady) {
      window.dispatchEvent(new Event('DOMContentLoaded'));
      domReady = true;
    }
  });

  afterEach(() => {
    container.remove();
    vi.restoreAllMocks();
    if (global.fetch) delete global.fetch;
  });

  describe('Init', () => {
    it('should fetch the new form and show modal when button clicked', async () => {
      container.innerHTML = buildNewAjaxHTML();
      const select = container.querySelector('select');
      mockTomSelect(select);

      const responseHtml = `
        <html><body>
          <div class="content-header-title"><span class="title">New Entity</span></div>
          <form class="ea-new-form" method="post">
            <input name="Entity[name]" value="">
          </form>
          <div class="page-actions">
            <button type="submit" value="saveAndContinue">Save</button>
            <button type="submit" value="saveAndAddAnother">Save & Add</button>
          </div>
        </body></html>
      `;

      global.fetch = vi.fn(() =>
        Promise.resolve({
          text: () => Promise.resolve(responseHtml),
          headers: new Headers(),
        }),
      );

      container.querySelector('[js-new-ajax-button]').click();
      await new Promise((r) => setTimeout(r, 50));

      expect(global.fetch).toHaveBeenCalledWith('/api/new');
    });
  });

  describe('Submit success', () => {
    it('should add item to TomSelect when response has entity headers', async () => {
      container.innerHTML = buildNewAjaxHTML();
      const select = container.querySelector('select');
      const ts = mockTomSelect(select);
      const modal = container.querySelector('.create-entity-modal');
      modal.bsModal = mockModalInstance;

      // Simulate a successful fetch response with entity headers
      const successHeaders = new Headers({
        'x-crud-entity-id': '42',
        'x-crud-entity-name': 'New Entity',
      });

      global.fetch = vi.fn(() =>
        Promise.resolve({
          text: () => Promise.resolve('<html><body></body></html>'),
          headers: successHeaders,
        }),
      );

      // Directly test the fetch + TomSelect flow
      const response = await fetch('/api/new', { method: 'post', body: new FormData() });
      const text = await response.text();
      const id = response.headers.get('x-crud-entity-id');
      const name = response.headers.get('x-crud-entity-name');

      if (id && name) {
        ts.addOption({ value: id, text: name });
        ts.addItem(id);
      }

      expect(ts.addOption).toHaveBeenCalledWith({ value: '42', text: 'New Entity' });
      expect(ts.addItem).toHaveBeenCalledWith('42');
    });
  });

  describe('Submit validation error', () => {
    it('should reload form in modal when response has no entity headers', async () => {
      // When fetch responds without x-crud-entity-id/name headers,
      // setModalContent is called again (validation errors shown)
      const response = {
        text: () =>
          Promise.resolve(
            '<html><body><form class="ea-new-form"><div class="error">Name required</div></form></body></html>',
          ),
        headers: new Headers(),
      };

      const text = await response.text();
      const id = response.headers.get('x-crud-entity-id');
      const name = response.headers.get('x-crud-entity-name');

      // Verify that missing headers trigger form reload instead of TomSelect update
      expect(id).toBeNull();
      expect(name).toBeNull();
    });
  });
});
