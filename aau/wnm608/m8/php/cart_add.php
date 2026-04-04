<?php
// ============================================
// ADD TO CART - cart_add.php
// Demonstrates: sessions, $_GET, arrays, redirect
// ============================================

session_start();
include "parts/products_data.php";

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Get the product ID from the URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Check if the product exists
if (isset($products[$id])) {
  // If already in cart, increase quantity
  if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += 1;
  } else {
    // Add new item to cart
    $_SESSION['cart'][$id] = [
      "name"  => $products[$id]['name'],
      "price" => $products[$id]['price'],
      "image" => $products[$id]['image'],
      "qty"   => 1,
    ];
  }
}

// Redirect back to the page the user came from, or to checkout
$redirect = $_GET['redirect'] ?? 'product_list.php';
header("Location: " . $redirect);
exit;
