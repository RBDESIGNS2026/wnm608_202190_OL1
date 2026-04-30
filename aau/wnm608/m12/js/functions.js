// ============================================
// /js/functions.js
// Reusable helpers: query() + templator()
// ============================================

/**
 * POST JSON to the API and return parsed JSON.
 * @param {object} body  e.g. { type: "productSearch", search: "fern" }
 * @returns {Promise<object>}
 */
function query(body) {
  return fetch("../data/API.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body)
  }).then(function (res) {
    return res.json();
  });
}

/**
 * Curried templator.
 * Pass a template function (item -> htmlString),
 * get back a renderer that accepts data (single item or array)
 * and returns the joined HTML string.
 */
function templator(templateFn) {
  return function (data) {
    if (!data) return "";
    if (!Array.isArray(data)) data = [data];
    return data.reduce(function (html, item) {
      return html + templateFn(item);
    }, "");
  };
}
