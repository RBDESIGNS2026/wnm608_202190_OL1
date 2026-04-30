<?php
// ============================================
// REMOVE FROM CART - cart_remove.php
// Module 11: remove line by index (id + color combo)
// ============================================

session_start();
include "functions.php";

$key = isset($_GET['key']) ? (int) $_GET['key'] : -1;

$cart = getCart();

if ($key >= 0 && isset($cart[$key])) {
    unset($cart[$key]);
    // Re-index so keys stay sequential
    $cart = array_values($cart);
    setCart($cart);
}

header("Location: confirmation.php");
exit;
?>
