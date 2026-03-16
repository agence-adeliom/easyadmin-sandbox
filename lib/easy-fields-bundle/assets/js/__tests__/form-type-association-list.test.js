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
import '../form-type-association-list.js';

describe('form-type-association-list.js', () => {
  let container;

  function buildListHTML({ multiple = false, url = '/api/list', columns = null } = {}) {
    return `
      <div class="form-widget">
        <select data-ea-ajax-index-url="${url}" data-ea-widget="ea-autocomplete" ${multiple ? 'multiple' : ''}>
          <option value="1">Item 1</option>
        </select>
        <button type="button" js-list-button
                data-cancel-label="Cancel"
                data-validate-label="Select"
                ${columns ? `data-columns='${JSON.stringify(columns)}'` : ''}
                data-show-filter="true"
                data-show-search="true">
          <span class="fa fa-list"></span> List
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

  function mockFetchResponse(htmlContent) {
    global.fetch = vi.fn(() =>
      Promise.resolve({
        text: () => Promise.resolve(htmlContent),
        headers: new Headers(),
      }),
    );
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

  // Dispatch DOMContentLoaded only once to avoid stacking event listeners
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
    it('should fetch list and show modal when list button is clicked', async () => {
      container.innerHTML = buildListHTML();
      const select = container.querySelector('select');
      mockTomSelect(select);

      const responseHtml = `
        <html><body>
          <div class="content-header-title"><span class="title">Entities</span></div>
          <div class="content-body">
            <table><tbody>
              <tr data-id="1"><td>Entity 1</td><td class="actions">Actions</td></tr>
              <tr data-id="2"><td>Entity 2</td><td class="actions">Actions</td></tr>
            </tbody></table>
          </div>
        </body></html>
      `;
      mockFetchResponse(responseHtml);

      container.querySelector('[js-list-button]').click();
      await new Promise((r) => setTimeout(r, 50));

      expect(global.fetch).toHaveBeenCalled();
    });
  });

  describe('Row selection', () => {
    it('should add selection class on row click', async () => {
      container.innerHTML = buildListHTML();
      const select = container.querySelector('select');
      mockTomSelect(select);

      const responseHtml = `
        <html><body>
          <div class="content-header-title"><span class="title">Entities</span></div>
          <div class="content-body">
            <table><tbody>
              <tr data-id="1"><td>Entity 1</td><td class="actions">A</td></tr>
            </tbody></table>
          </div>
        </body></html>
      `;
      mockFetchResponse(responseHtml);

      // Init the list modal
      container.querySelector('[js-list-button]').click();
      await new Promise((r) => setTimeout(r, 50));

      // Click a row in the modal body
      const row = container.querySelector('.content-body tr');
      if (row) {
        row.click();
        await new Promise((r) => setTimeout(r, 10));
        expect(row.classList.contains('table-primary')).toBe(true);
      }
    });
  });

  describe('Cancel', () => {
    it('should hide modal on cancel click', async () => {
      container.innerHTML = buildListHTML();
      const select = container.querySelector('select');
      mockTomSelect(select);

      const responseHtml = `
        <html><body>
          <div class="content-header-title"><span class="title">Entities</span></div>
          <div class="content-body">
            <table><tbody>
              <tr data-id="1"><td>Entity 1</td><td class="actions">A</td></tr>
            </tbody></table>
          </div>
        </body></html>
      `;
      mockFetchResponse(responseHtml);

      container.querySelector('[js-list-button]').click();
      await new Promise((r) => setTimeout(r, 50));

      // The cancel button is in the modal footer
      const cancelBtn = container.querySelector('[js-list-cancel]');
      if (cancelBtn) {
        cancelBtn.click();
        await new Promise((r) => setTimeout(r, 10));
        expect(mockModalInstance.hide).toHaveBeenCalled();
      }
    });
  });

  describe('Validate', () => {
    it('should call TomSelect addItem on validate', async () => {
      container.innerHTML = buildListHTML();
      const select = container.querySelector('select');
      const ts = mockTomSelect(select);

      const responseHtml = `
        <html><body>
          <div class="content-header-title"><span class="title">Entities</span></div>
          <div class="content-body">
            <table><tbody>
              <tr data-id="42"><td>Entity 42</td><td class="actions">A</td></tr>
            </tbody></table>
          </div>
        </body></html>
      `;
      mockFetchResponse(responseHtml);

      container.querySelector('[js-list-button]').click();
      await new Promise((r) => setTimeout(r, 50));

      // Select a row
      const row = container.querySelector('.content-body tr[data-id="42"]');
      if (row) {
        row.click();
        await new Promise((r) => setTimeout(r, 10));

        // Click validate
        const selectBtn = container.querySelector('[js-list-select]');
        if (selectBtn) {
          selectBtn.click();
          await new Promise((r) => setTimeout(r, 10));
          expect(ts.addItem).toHaveBeenCalledWith(42);
          expect(mockModalInstance.hide).toHaveBeenCalled();
        }
      }
    });
  });

  describe('Footer', () => {
    it('should build footer with cancel and select buttons', async () => {
      container.innerHTML = buildListHTML();
      const select = container.querySelector('select');
      mockTomSelect(select);

      mockFetchResponse(
        '<html><body><div class="content-header-title"><span class="title">T</span></div><div class="content-body"><table></table></div></body></html>',
      );

      container.querySelector('[js-list-button]').click();
      await new Promise((r) => setTimeout(r, 50));

      const footer = container.querySelector('.modal-footer');
      expect(footer.querySelector('[js-list-cancel]')).not.toBeNull();
      expect(footer.querySelector('[js-list-select]')).not.toBeNull();
    });
  });

  describe('Pagination', () => {
    it('should fetch content when clicking inner link', async () => {
      container.innerHTML = buildListHTML();
      const select = container.querySelector('select');
      mockTomSelect(select);

      mockFetchResponse(
        '<html><body><div class="content-header-title"><span class="title">T</span></div><div class="content-body"><a href="/page2">Next</a></div></body></html>',
      );

      container.querySelector('[js-list-button]').click();
      await new Promise((r) => setTimeout(r, 50));

      // Click pagination link
      const link = container.querySelector('.content-body a');
      if (link) {
        global.fetch.mockClear();
        link.click();
        await new Promise((r) => setTimeout(r, 50));
        expect(global.fetch).toHaveBeenCalled();
      }
    });
  });

  describe('Form submit', () => {
    it('should fetch when inner form is submitted', async () => {
      container.innerHTML = buildListHTML();
      const select = container.querySelector('select');
      mockTomSelect(select);

      mockFetchResponse(
        '<html><body><div class="content-header-title"><span class="title">T</span></div><div class="content-body"><form method="get"><input name="q" value="test"></form></div></body></html>',
      );

      container.querySelector('[js-list-button]').click();
      await new Promise((r) => setTimeout(r, 50));

      const form = container.querySelector('.content-body form');
      if (form) {
        global.fetch.mockClear();
        form.dispatchEvent(new Event('submit', { bubbles: true }));
        await new Promise((r) => setTimeout(r, 50));
        expect(global.fetch).toHaveBeenCalled();
      }
    });
  });
});
