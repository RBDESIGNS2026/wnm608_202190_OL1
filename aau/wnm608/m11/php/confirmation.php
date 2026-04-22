<?php
require_once("functions.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    resetCart();
}

$id = $_GET['id'] ?? null;
$cart = getCart();

$item = null;

foreach($cart as $i) {
    if($i['id'] == $id) {
        $item = $i;
        break;
    }
}
?>

<h2>Added to Cart</h2>

<?php if($item): ?>
<p>
There are now <?= $item['amount'] ?> of product <?= $item['id'] ?> in your cart.
</p>
<?php endif; ?>

<a href="product_list.php">Continue Shopping</a>
<a href="checkout.php">Go to Checkout</a>
