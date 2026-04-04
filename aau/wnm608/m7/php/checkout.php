<?php
// ============================================
// CHECKOUT PAGE - checkout.php
// Demonstrates: sessions, arrays, variables, calculations, PHP output
// ============================================

session_start();

$page_title = "Neon Kactus - Checkout";

// Get cart items from session
$cart_items = $_SESSION['cart'] ?? [];

// Calculate totals using PHP
$subtotal = 0;
foreach ($cart_items as $item) {
  $subtotal += $item['price'] * $item['qty'];
}
$shipping = count($cart_items) > 0 ? 12.00 : 0;
$tax = round($subtotal * 0.09, 2);
$total = $subtotal + $shipping + $tax;
$cart_count = 0;
foreach ($cart_items as $item) {
  $cart_count += $item['qty'];
}
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
  <p class="label-accent" style="font-style: italic;">secure checkout</p>
  <h1>CHECKOUT</h1>
</section>

<!-- CHECKOUT CONTENT -->
<section class="container" style="padding-bottom: 4rem;">
  <div class="grid gap">
    <!-- FORM SIDE -->
    <div class="col-12 col-md-6">
      <div class="card">
        <h3>Shipping Information</h3>
        <div class="form-row">
          <div class="grid gap">
            <div class="col-6">
              <label>First Name</label>
              <input type="text" class="input-hotdog">
            </div>
            <div class="col-6">
              <label>Last Name</label>
              <input type="text" class="input-hotdog">
            </div>
          </div>
        </div>
        <div class="form-row">
          <label>Address</label>
          <input type="text" class="input-hotdog">
        </div>
        <div class="form-row">
          <div class="grid gap">
            <div class="col-4">
              <label>City</label>
              <input type="text" class="input-hotdog">
            </div>
            <div class="col-4">
              <label>State</label>
              <input type="text" class="input-hotdog">
            </div>
            <div class="col-4">
              <label>Zip</label>
              <input type="text" class="input-hotdog">
            </div>
          </div>
        </div>
        <div class="form-row">
          <label>Email</label>
          <input type="email" class="input-hotdog">
        </div>
      </div>

      <div class="card">
        <h3>Payment</h3>
        <div class="form-row">
          <label>Card Number</label>
          <input type="text" placeholder="•••• •••• •••• ••••" class="input-hotdog">
        </div>
        <div class="form-row">
          <div class="grid gap">
            <div class="col-6">
              <label>Expiry</label>
              <input type="text" placeholder="MM / YY" class="input-hotdog">
            </div>
            <div class="col-6">
              <label>CVC</label>
              <input type="text" placeholder="•••" class="input-hotdog">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ORDER SUMMARY -->
    <div class="col-12 col-md-6">
      <div class="card order-summary">
        <h3>Order Summary (<?= $cart_count ?> item<?= $cart_count !== 1 ? 's' : '' ?>)</h3>

        <?php if (count($cart_items) > 0): ?>
        <div class="order-items">
          <?php foreach ($cart_items as $id => $item): ?>
          <div class="order-item">
            <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="order-item-img">
            <div class="order-item-info">
              <p class="order-item-name"><?= $item['name'] ?></p>
              <p class="order-item-qty">Qty: <?= $item['qty'] ?></p>
            </div>
            <p class="order-item-price">$<?= number_format($item['price'] * $item['qty'], 2) ?></p>
            <a href="cart_remove.php?id=<?= $id ?>" style="color: #c00; text-decoration: none; margin-left: 0.5rem; font-size: 0.85rem;">✕</a>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="body-text" style="text-align: center; padding: 2rem 0;">Your cart is empty. <a href="product_list.php">Shop now →</a></p>
        <?php endif; ?>

        <div class="divider"></div>

        <div class="order-totals">
          <div class="order-row">
            <span class="order-label">Subtotal</span>
            <span class="order-value">$<?= number_format($subtotal, 2) ?></span>
          </div>
          <div class="order-row">
            <span class="order-label">Shipping</span>
            <span class="order-value">$<?= number_format($shipping, 2) ?></span>
          </div>
          <div class="order-row">
            <span class="order-label">Tax</span>
            <span class="order-value">$<?= number_format($tax, 2) ?></span>
          </div>
        </div>

        <div class="divider"></div>

        <div class="order-total-row">
          <span>Total</span>
          <span>$<?= number_format($total, 2) ?></span>
        </div>

        <div class="promo-row">
          <input type="text" placeholder="Promo Code" class="input-hotdog promo-input">
          <button class="btn-outline promo-btn">Apply</button>
        </div>

        <button class="btn-primary btn-full"<?= count($cart_items) === 0 ? ' disabled' : '' ?>>Place Order</button>
      </div>
    </div>
  </div>
</section>

<?php include "parts/footer.php"; ?>

</body>
</html>
