<?php
// ============================================
// REMOVE FROM CART - cart_remove.php
// Demonstrates: sessions, unset, redirect
// ============================================

session_start();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (isset($_SESSION['cart'][$id])) {
  unset($_SESSION['cart'][$id]);
}

header("Location: checkout.php");
exit;
