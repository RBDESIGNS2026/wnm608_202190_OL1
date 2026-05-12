<?php
// ============================================
// DYNAMIC SHOP PAGE - product_list_dynamic.php
// Layout only — products are fetched via /data/API.php
// (Original product_list.php is preserved untouched.)
// ============================================

session_start();
include "functions.php";

$page_title = "Neon Kactus - Shop (Dynamic)";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?= $page_title ?></title>
<?php include "parts/meta.php"; ?>
</head>

<body>

<?php include "parts/navbar.php"; ?>

<!-- TITLE -->
<section class="sg-section container" style="margin-bottom: 2rem;">
  <p class="label-accent" style="font-style: italic;">curated collection</p>
  <h1>SHOP</h1>
</section>

<!-- SEARCH -->
<section class="container" style="margin-bottom: 1.25rem;">
  <form id="productSearchForm" onsubmit="return false;" style="display:flex;gap:.5rem;max-width:480px;">
    <input
      type="search"
      id="productSearch"
      name="search"
      placeholder="Search plants…"
      style="flex:1;padding:.6rem .9rem;border:1px solid var(--charcoal);background:var(--ivory);font-family:inherit;font-size:.9rem;"
    >
    <button type="submit" class="btn-pill btn-sm">Search</button>
  </form>
</section>

<!-- FILTERS -->
<section class="container" style="margin-bottom: 1.25rem;">
  <div class="filter-row">
    <button class="pill active" data-filter="category" data-value="">All</button>
    <button class="pill" data-filter="category" data-value="indoor">Indoor</button>
    <button class="pill" data-filter="category" data-value="low-light">Low Light</button>
    <button class="pill" data-filter="category" data-value="easy-care">Easy Care</button>
    <button class="pill" data-filter="category" data-value="rare">Rare</button>
  </div>
</section>

<!-- SORT -->
<section class="container" style="margin-bottom: 2rem;">
  <label style="font-size:.75rem;letter-spacing:.15em;text-transform:uppercase;margin-right:.5rem;">Sort:</label>
  <select class="js-sort" style="padding:.5rem .75rem;border:1px solid var(--charcoal);background:var(--ivory);font-family:inherit;">
    <option value="newest">Newest</option>
    <option value="oldest">Oldest</option>
    <option value="cheapest">Cheapest</option>
    <option value="expensive">Most Expensive</option>
  </select>
</section>

<!-- RESULTS -->
<section class="sg-section container">
  <div class="grid gap" id="productResults">
    <div class="col-12" style="text-align:center;padding:3rem 0;opacity:.5;">Loading…</div>
  </div>
</section>

<?php include "parts/footer.php"; ?>

<!-- JS modules (load order matters: functions -> templates -> page logic) -->
<script src="../js/functions.js"></script>
<script src="../js/templates.js"></script>
<script src="../js/product-list.js"></script>

</body>
</html>
