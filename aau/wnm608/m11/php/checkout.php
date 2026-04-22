<?php require_once("functions.php"); ?>

<?php $cart = getCart(); ?>

<h1>Checkout</h1>

<form method="post" action="confirmation.php">
    <button type="submit">Place Order</button>
</form>
