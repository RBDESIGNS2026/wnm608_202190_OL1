<?php
// Calculate cart count from session
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$cart_items_nav = $_SESSION['cart'] ?? [];
$cart_count = 0;
$cart_total = 0;
foreach ($cart_items_nav as $item) {
  $cart_count += $item['qty'];
  $cart_total += $item['price'] * $item['qty'];
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
      <a href="checkout.php">Cart (<?= $cart_count ?>) — $<?= number_format($cart_total, 2) ?></a>
      <a href="admin/users.php">Admin</a>
    </nav>
  </div>
</header>
