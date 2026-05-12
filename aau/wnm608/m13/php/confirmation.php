<?php
// ============================================
// CONFIRMATION PAGE - confirmation.php
// Module 14:
//   - GET request: show cart contents (id → product data lookup)
//   - POST request: order placed → snapshot order, reset cart, show full receipt
// ============================================

session_start();
include "functions.php";
include "parts/products_data.php";

$order_placed = ($_SERVER['REQUEST_METHOD'] === 'POST');

// Capture totals BEFORE we reset the cart so we can show a real receipt.
if ($order_placed) {
    $snapshot_cart = getCart();
    $snap_subtotal = 0;
    $snap_count    = 0;
    $snap_items    = [];
    foreach ($snapshot_cart as $item) {
        $pid = (int) ($item['id'] ?? 0);
        if (!isset($products[$pid])) continue;
        $line_total      = $products[$pid]['price'] * (int) $item['amount'];
        $snap_subtotal  += $line_total;
        $snap_count     += (int) $item['amount'];
        $snap_items[]    = [
            'name'   => $products[$pid]['name'],
            'image'  => $products[$pid]['image'],
            'price'  => $products[$pid]['price'],
            'amount' => (int) $item['amount'],
            'color'  => $item['color'] ?? '',
            'total'  => $line_total,
        ];
    }
    $snap_shipping = count($snapshot_cart) > 0 ? 12.00 : 0;
    $snap_tax      = round($snap_subtotal * 0.09, 2);
    $snap_total    = $snap_subtotal + $snap_shipping + $snap_tax;
    $order_number  = 'NK-' . strtoupper(substr(md5(uniqid('', true)), 0, 8));
    $order_date    = date('F j, Y');
    $eta_date      = date('F j, Y', strtotime('+5 days'));

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

<!-- ORDER CONFIRMED RECEIPT -->
<section class="container" style="padding: 4rem 0 2rem;">
  <div style="max-width: 720px; margin: 0 auto; text-align: center;">
    <div class="confirm-check">✓</div>
    <p class="label-accent" style="font-style: italic;">order confirmed</p>
    <h1 style="margin-bottom: 1rem;">Thank You for Your Order</h1>
    <p style="margin: 0 auto 0.5rem;">A confirmation email is on its way.</p>
    <p style="margin: 0 auto 2rem; color:#777; font-size:0.9rem;">
      Order <strong><?= $order_number ?></strong> &middot; Placed <?= $order_date ?>
    </p>
  </div>
</section>

<section class="container" style="padding-bottom: 4rem;">
  <div class="grid gap" style="max-width: 900px; margin: 0 auto;">

    <!-- ORDER ITEMS -->
    <div class="col-12 col-md-7">
      <div class="card">
        <h3>Your Items (<?= $snap_count ?>)</h3>
        <div class="order-items">
          <?php foreach ($snap_items as $it): ?>
            <div class="order-item">
              <img src="<?= $it['image'] ?>" alt="<?= $it['name'] ?>" class="order-item-img">
              <div class="order-item-info">
                <p class="order-item-name"><?= $it['name'] ?></p>
                <p class="order-item-qty">
                  Qty: <?= $it['amount'] ?>
                  <?php if (!empty($it['color'])): ?> &middot; <?= htmlspecialchars($it['color']) ?><?php endif; ?>
                </p>
              </div>
              <p class="order-item-price">$<?= number_format($it['total'], 2) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="card">
        <h3>Shipping</h3>
        <p style="margin:0; color:#555;">
          Estimated delivery: <strong><?= $eta_date ?></strong><br>
          Standard shipping &middot; Tracking link will be emailed when your order ships.
        </p>
      </div>
    </div>

    <!-- SUMMARY -->
    <div class="col-12 col-md-5">
      <div class="card order-summary">
        <h3>Order Summary</h3>
        <div class="order-totals">
          <div class="order-row"><span>Subtotal</span><span>$<?= number_format($snap_subtotal, 2) ?></span></div>
          <div class="order-row"><span>Shipping</span><span>$<?= number_format($snap_shipping, 2) ?></span></div>
          <div class="order-row"><span>Tax</span><span>$<?= number_format($snap_tax, 2) ?></span></div>
        </div>
        <div class="divider"></div>
        <div class="order-total-row">
          <span>Total Paid</span>
          <span>$<?= number_format($snap_total, 2) ?></span>
        </div>

        <a href="product_list.php" class="btn-primary btn-full" style="display:block;text-align:center;text-decoration:none;margin-bottom:0.75rem;">
          Continue Shopping
        </a>
        <a href="index.php" class="btn-outline btn-full" style="display:block;text-align:center;text-decoration:none;">
          Back to Home
        </a>
      </div>
    </div>

  </div>
</section>

<!-- KEEP SHOPPING UPSELL -->
<section class="sg-section container" style="margin-top:1rem;">
  <div class="display-flex flex-justify-between flex-align-center" style="margin-bottom: 2rem;">
    <div>
      <p class="label-accent" style="font-style: italic;">you might also love</p>
      <h2>Keep Exploring</h2>
    </div>
    <a href="product_list.php" class="view-all-link">Shop All →</a>
  </div>
  <div class="grid gap">
    <?php
      $upsell = array_slice($products, 0, 4, true);
      foreach ($upsell as $uid => $up):
    ?>
      <div class="col-12 col-md-3">
        <div class="product-card">
          <div class="product-image-wrapper">
            <a href="product_item.php?id=<?= $uid ?>"><img src="<?= $up['image'] ?>" alt="<?= $up['name'] ?>"></a>
          </div>
          <div class="product-info">
            <h3 class="product-name"><?= $up['name'] ?></h3>
            <div class="display-flex flex-justify-between flex-align-center">
              <p class="product-price">$<?= $up['price'] ?></p>
              <a href="cart_add.php?id=<?= $uid ?>&redirect=product_list.php" class="btn-pill btn-sm">Add</a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
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
              <?php if (!empty($item['color'])): ?> &middot; Color: <?= htmlspecialchars($item['color']) ?><?php endif; ?>
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

    <div style="text-align:center;padding:3rem 1rem;display:flex;flex-direction:column;align-items:center;gap:1.5rem;">
      <p style="font-size:1rem;letter-spacing:.05em;margin:0;color:var(--charcoal);max-width:none;">
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

<style>
.confirm-check {
  width: 72px; height: 72px;
  border-radius: 50%;
  background: var(--sage);
  color: #fff;
  font-size: 2.25rem;
  line-height: 72px;
  margin: 0 auto 1.5rem;
  font-weight: 700;
}
</style>

</body>
</html>
