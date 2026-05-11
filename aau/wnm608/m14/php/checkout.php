<?php
// ============================================
// CHECKOUT PAGE - checkout.php
// Module 11: cart stores id+amount+color → look up product data for display.
// Place Order POSTs to confirmation.php which resets the cart.
// ============================================

session_start();
include "functions.php";
include "parts/products_data.php";

$page_title = "Neon Kactus - Checkout";

$cart = getCart();

// Calculate totals using product lookup
$subtotal = 0;
$cart_count = 0;
foreach ($cart as $item) {
  $pid = (int) $item['id'];
  if (!isset($products[$pid])) continue;
  $subtotal   += $products[$pid]['price'] * $item['amount'];
  $cart_count += $item['amount'];
}

$shipping = count($cart) > 0 ? 12.00 : 0;
$tax      = round($subtotal * 0.09, 2);
$total    = $subtotal + $shipping + $tax;
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
<form action="confirmation.php" method="POST">
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

        <h3>
          Order Summary (<?= $cart_count ?> item<?= $cart_count !== 1 ? 's' : '' ?>)
        </h3>

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
                    · <?= htmlspecialchars($item['color']) ?>
                  <?php endif; ?>
                </p>
              </div>

              <p class="order-item-price">
                $<?= number_format($product['price'] * $item['amount'], 2) ?>
              </p>

              <a href="cart_remove.php?key=<?= $key ?>" style="color:#c00;text-decoration:none;margin-left:0.5rem;font-size:0.85rem;">✕</a>
            </div>
          <?php endforeach; ?>
        </div>

        <?php else: ?>

        <p style="text-align:center;padding:2rem 0;">
          Your cart is empty.
          <a href="product_list.php">Shop now →</a>
        </p>

        <?php endif; ?>

        <div class="divider"></div>

        <div class="order-totals">
          <div class="order-row">
            <span>Subtotal</span>
            <span>$<?= number_format($subtotal, 2) ?></span>
          </div>

          <div class="order-row">
            <span>Shipping</span>
            <span>$<?= number_format($shipping, 2) ?></span>
          </div>

          <div class="order-row">
            <span>Tax</span>
            <span>$<?= number_format($tax, 2) ?></span>
          </div>
        </div>

        <div class="divider"></div>

        <div class="order-total-row">
          <span>Total</span>
          <span>$<?= number_format($total, 2) ?></span>
        </div>

        <button type="submit" class="btn-primary btn-full" style="display:block;text-align:center;width:100%;">
          Place Order
        </button>

      </div>
    </div>

  </div>
</section>
</form>

<?php include "parts/footer.php"; ?>

</body>
</html>
