/**
 * Load a remote script by creating a <script> tag.
 * @param {string} src - URL of the script to load
 * @returns {Promise<HTMLScriptElement>}
 */
export function loadScript(src) {
  return new Promise(function (resolve, reject) {
    const script = document.createElement('script');
    script.src = src;
    script.type = 'text/javascript';
    script.onload = () => resolve(script);
    script.onerror = () => reject(new Error(`Script load error for ${src}`));
    document.head.append(script);
  });
}


/**
 * Evaluate inline script content.
 * @param {string} content - JavaScript code to eval
 * @returns {Promise<void>}
 */
export function evalScript(content) {
  return new Promise(function (resolve) {
    eval(content);
    resolve();
  });
}

/**
 * Insert HTML into an element, loading remote scripts first and re-executing inline scripts.
 * This is NOT a simple innerHTML — it handles script execution that innerHTML blocks.
 *
 * @param {HTMLElement} element - Container to append HTML into
 * @param {string} html - HTML string to insert
 * @param {Object} [options]
 * @param {boolean} [options.skipFlexFill=false] - If true, don't skip .flex-fill elements
 * @returns {Promise<void>}
 */
export function insertHtmlWithScripts(element, html, options = {}) {
  const { skipFlexFill = false } = options;
  const tmp = document.createElement('div');
  tmp.innerHTML = html;

  // Load all remote scripts first
  const remote = [];
  Array.from(tmp.querySelectorAll('script')).forEach((oldScript) => {
    if (oldScript.src) {
      remote.push(loadScript(oldScript.src));
    }
  });

  return Promise.all(remote).then(() => {
    element.insertAdjacentHTML('beforeend', html);
    let lastElement = element.lastElementChild;

    // Skip .flex-fill elements to find the actual inserted content
    if (!skipFlexFill && lastElement && lastElement.classList.contains('flex-fill')) {
      lastElement = lastElement.previousElementSibling;
    }

    if (lastElement) {
      // Re-execute inline scripts (innerHTML doesn't execute scripts)
      Array.from(lastElement.querySelectorAll('script')).forEach((oldScript) => {
        if (!oldScript.src) {
          const newScript = document.createElement('script');
          Array.from(oldScript.attributes).forEach((attr) =>
            newScript.setAttribute(attr.name, attr.value),
          );
          newScript.appendChild(document.createTextNode(oldScript.innerHTML));
          oldScript.parentNode.replaceChild(newScript, oldScript);
        }
      });
    }
  });
}
