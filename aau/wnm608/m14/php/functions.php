<?php
// ============================================
// FUNCTIONS - functions.php
// Cart helpers for Module 11
// ============================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Return the current session cart (or empty array).
 */
function getCart() {
    return $_SESSION['cart'] ?? [];
}

/**
 * Replace the session cart with the given array.
 */
function setCart($cart) {
    $_SESSION['cart'] = $cart;
}

/**
 * Empty the session cart.
 */
function resetCart() {
    $_SESSION['cart'] = [];
}

// ----- DB helpers (existing) -----

function makeConn() {
    $conn = new mysqli("localhost", "u957237009_rbrownadmin", "Naiomi831!", "u957237009_nkactusadmin");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function makeQuery($conn, $query) {
    $result = $conn->query($query);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $data = [];

    while ($row = $result->fetch_object()) {
        $data[] = $row;
    }

    return $data;
}
?>
