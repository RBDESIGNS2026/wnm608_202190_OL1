<?php
session_start();

// clear cart after order
unset($_SESSION['cart']);

$page_title = "Order Confirmation";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title><?= $page_title ?></title>
  <?php include "parts/meta.php"; ?>
</head>

<body>

<?php include "parts/navbar.php"; ?>

<section class="container" style="padding: 4rem 0; text-align: center;">
  <h1>Thank You for Your Order!</h1>

  <p style="margin-top: 1rem;">
    Your order has been placed successfully.
  </p>

  <p style="margin-bottom: 2rem;">
    We’re getting everything ready for you.
  </p>

  <a href="product_list.php">
    <button class="btn-primary">Continue Shopping</button>
  </a>
</section>

<?php include "parts/footer.php"; ?>

</body>
</html>