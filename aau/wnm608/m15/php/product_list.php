<?php
// ============================================
// SHOP PAGE - product_list.php
// Demonstrates: arrays, foreach loop, dynamic links with GET params, sessions
// ============================================

session_start();
include "functions.php";

$page_title = "Neon Kactus - Shop";

// Include shared product data
include "parts/products_data.php";

// Tag map: which products belong to which filter category
$tag_map = [
  1  => ["indoor"],
  2  => ["indoor", "easy-care"],
  3  => ["indoor", "low-light", "easy-care"],
  4  => ["indoor", "low-light", "easy-care"],
  5  => ["indoor", "low-light", "easy-care"],
  6  => ["indoor", "outdoor"],
  7  => ["indoor", "outdoor", "easy-care"],
  8  => ["indoor", "low-light", "easy-care"],
  9  => ["indoor"],
  10 => ["indoor", "low-light"],
  11 => ["indoor"],
  12 => ["indoor", "easy-care"],
  13 => ["indoor", "outdoor"],
];

// Filter categories: label => slug
$filters = [
  "All"       => "all",
  "Indoor"    => "indoor",
  "Outdoor"   => "outdoor",
  "Low Light" => "low-light",
  "Easy Care" => "easy-care",
];

// Active filter via ?filter=slug
$active = isset($_GET['filter']) ? strtolower(trim($_GET['filter'])) : "all";
if (!in_array($active, $filters, true)) $active = "all";
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

<!-- FILTERS -->
<section class="container" style="margin-bottom: 2rem;">
  <div class="filter-row">
    <?php foreach ($filters as $label => $slug): ?>
    <a href="?filter=<?= $slug ?>" class="pill<?= $active === $slug ? ' active' : '' ?>" style="text-decoration:none;display:inline-block;"><?= $label ?></a>
    <?php endforeach; ?>
  </div>
</section>

<!-- PRODUCT GRID -->
<section class="sg-section container">
  <div class="grid gap">
    <?php foreach ($products as $id => $product): ?>
      <?php
        $tags = $tag_map[$id] ?? [];
        if ($active !== "all" && !in_array($active, $tags, true)) continue;
      ?>
    <div class="col-12 col-md-4">
      <a href="product_item.php?id=<?= $id ?>" class="product-card-link">
        <div class="product-card">
          <div class="product-image-wrapper">
            <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
            <button type="button" class="favorite-btn" onclick="event.preventDefault(); event.stopPropagation(); this.classList.toggle('active'); this.innerHTML = this.classList.contains('active') ? '♥' : '♡';">♡</button>
          </div>
          <div class="product-info">
            <h3 class="product-name"><?= $product['name'] ?></h3>
            <div class="display-flex flex-justify-between flex-align-center">
              <p class="product-price">$<?= $product['price'] ?></p>
              <a href="product_item.php?id=<?= $id ?>" class="btn-pill btn-sm" onclick="event.stopPropagation();">View</a>
            </div>
          </div>
        </div>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include "parts/footer.php"; ?>

</body>
</html>
