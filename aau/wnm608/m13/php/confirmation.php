<?php
// ============================================
// CONFIRMATION PAGE - confirmation.php
// Module 11:
//   - GET request: show cart contents (id → product data lookup)
//   - POST request: order placed → reset cart, show thank-you
// ============================================

session_start();
include "functions.php";
include "parts/products_data.php";

$order_placed = ($_SERVER['REQUEST_METHOD'] === 'POST');

if ($order_placed) {
    resetCart();
}

$cart = getCart();

$page_title = $order_placed ? "Neon Kactus - Order Confirmed" : "Neon Kactus - Your Cart";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?= $page_title ?></title>
<?php include "parts/meta.php"; ?>
</head>

<body>

<?php include "parts/navbar.php"; ?>

<?php if ($order_placed): ?>

<section class="container" style="padding: 4rem 0; text-align: center;">
  <div style="max-width: 900px; margin: 0 auto; text-align: center;">
    <p class="label-accent" style="font-style: italic; text-align: center;">order placed</p>
    <h1 style="text-align: center; margin-left: auto; margin-right: auto;">THANK YOU FOR YOUR ORDER!</h1>
    <p style="margin-top: 1rem; text-align: center;">Your order has been placed successfully.</p>
    <p style="margin-bottom: 2rem; text-align: center;">We're getting everything ready for you.</p>
    <a href="product_list.php" class="btn-primary" style="text-decoration:none;display:inline-block;">Continue Shopping</a>
  </div>
</section>

<?php else: ?>

<!-- CART REVIEW -->
<section class="sg-section container" style="margin-bottom: 2rem;">
  <p class="label-accent" style="font-style: italic;">review your order</p>
  <h1>YOUR CART</h1>
</section>

<section class="container" style="padding-bottom: 4rem;">
  <div class="card order-summary">

    <h3>Order Summary</h3>

    <?php if (count($cart) > 0): ?>

    <div class="order-items">
      <?php foreach ($cart as $key => $item): ?>
        <?php
          $pid = (int) $item['id'];
          $product = $products[$pid] ?? null;
          if (!$product) continue;
        ?>
        <div class="order-item">
          <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="order-item-img">

          <div class="order-item-info">
            <p class="order-item-name"><?= $product['name'] ?></p>
            <p class="order-item-qty">
              Qty: <?= (int) $item['amount'] ?>
              <?php if (!empty($item['color'])): ?>
                · Color: <?= htmlspecialchars($item['color']) ?>
              <?php endif; ?>
            </p>
          </div>

          <a href="cart_remove.php?key=<?= $key ?>" style="color:#c00;text-decoration:none;margin-left:0.5rem;font-size:0.85rem;">✕</a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="divider"></div>

    <a href="checkout.php" class="btn-primary btn-full" style="display:block;text-align:center;text-decoration:none;">
      Proceed to Checkout
    </a>

    <?php else: ?>

    <div style="text-align:center;padding:3rem 1rem;">
      <p style="font-size:1rem;letter-spacing:.05em;margin-bottom:1.5rem;color:var(--charcoal);">
        Your cart is currently empty.
      </p>
      <a href="product_list.php" class="btn-primary" style="text-decoration:none;display:inline-block;">
        Continue Shopping
      </a>
    </div>

    <?php endif; ?>

  </div>
</section>

<?php endif; ?>

<?php include "parts/footer.php"; ?>

</body>
</html>
