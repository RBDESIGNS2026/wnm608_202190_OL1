<?php
session_start();

function getCart() {
    return $_SESSION['cart'] ?? [];
}

function setCart($cart) {
    $_SESSION['cart'] = $cart;
}

function resetCart() {
    $_SESSION['cart'] = [];
}
