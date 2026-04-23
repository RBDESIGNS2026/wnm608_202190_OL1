<?php
// Calculate cart count from session (Module 11 schema: id + amount + color)
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// Pull product data for price lookup
$nav_products = [];
if (file_exists(__DIR__ . "/products_data.php")) {
  include __DIR__ . "/products_data.php";
  $nav_products = $products ?? [];
}
$cart_items_nav = $_SESSION['cart'] ?? [];
$cart_count = 0;
$cart_total = 0;
foreach ($cart_items_nav as $item) {
  $pid = (int) ($item['id'] ?? 0);
  $amt = (int) ($item['amount'] ?? 0);
  $cart_count += $amt;
  if (isset($nav_products[$pid])) {
    $cart_total += $nav_products[$pid]['price'] * $amt;
  }
}
?>
<!-- HEADER -->
<header class="top-bar container">
  <div class="display-flex flex-justify-between flex-align-center">
    <a href="index.php" class="site-title">NEON KACTUS®</a>
    <nav class="top-nav">
      <a href="index.php">Home</a>
      <a href="product_list.php">Shop</a>
      <a href="product_item.php?id=1">Product</a>
      <a href="confirmation.php">
        Cart<?php if ($cart_count > 0): ?> (<?= $cart_count ?>) — $<?= number_format($cart_total, 2) ?><?php endif; ?>
      </a>
    </nav>
  </div>
</header>
