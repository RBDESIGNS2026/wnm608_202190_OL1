// ============================================
// /js/product-list.js
// Search + Filter + Sort logic for the dynamic shop page
// ============================================

(function () {
  var resultsEl = document.getElementById("productResults");
  var searchForm = document.getElementById("productSearchForm");
  var searchInput = document.getElementById("productSearch");
  var sortSelect = document.querySelector(".js-sort");

  if (!resultsEl) return;

  function showResults(data) {
    if (data && data.result && data.result.length) {
      resultsEl.innerHTML = renderProducts(data.result);
    } else {
      resultsEl.innerHTML =
        '<div class="col-12" style="text-align:center;padding:3rem 0;opacity:.7;">' +
          '<p>No results found.</p>' +
        '</div>';
    }
  }

  function load(body) {
    resultsEl.innerHTML =
      '<div class="col-12" style="text-align:center;padding:3rem 0;opacity:.5;">Loading…</div>';
    query(body).then(showResults).catch(function () {
      resultsEl.innerHTML =
        '<div class="col-12" style="text-align:center;padding:3rem 0;opacity:.7;">' +
          '<p>Could not load products.</p>' +
        '</div>';
    });
  }

  // ----- Default page load -----
  load({ type: "products_all" });

  // ----- Search -----
  if (searchForm) {
    searchForm.addEventListener("submit", function (e) {
      e.preventDefault();
      load({ type: "productSearch", search: searchInput.value });
    });
    // Live search as user types
    searchInput.addEventListener("input", function () {
      load({ type: "productSearch", search: searchInput.value });
    });
  }

  // ----- Filters (single delegated listener) -----
  document.addEventListener("click", function (e) {
    var btn = e.target.closest("[data-filter]");
    if (!btn) return;

    // Toggle active visual state
    document.querySelectorAll("[data-filter]").forEach(function (b) {
      b.classList.remove("active");
    });
    btn.classList.add("active");

    var filter = btn.getAttribute("data-filter");
    var value  = btn.getAttribute("data-value") || "";

    if (value === "") {
      load({ type: "products_all" });
    } else {
      load({ type: "product_filter", column: filter, value: value });
    }
  });

  // ----- Sort -----
  if (sortSelect) {
    sortSelect.addEventListener("change", function () {
      var v = sortSelect.value;
      var column = "date", direction = "DESC";
      if (v === "newest")        { column = "date";  direction = "DESC"; }
      else if (v === "oldest")   { column = "date";  direction = "ASC";  }
      else if (v === "cheapest") { column = "price"; direction = "ASC";  }
      else if (v === "expensive"){ column = "price"; direction = "DESC"; }
      load({ type: "product_sort", column: column, direction: direction });
    });
  }
})();
