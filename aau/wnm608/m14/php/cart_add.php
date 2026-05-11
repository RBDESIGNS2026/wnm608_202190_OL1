<?php
// ============================================
// ADD TO CART - cart_add.php
// Module 11: POST id + amount + color, merge by (id + color)
// ============================================

session_start();
include "functions.php";
include "parts/products_data.php";

// Read POST data
$id     = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$amount = isset($_POST['amount']) ? (int) $_POST['amount'] : 1;
$color  = isset($_POST['color']) ? trim($_POST['color']) : '';

if ($amount < 1) {
    $amount = 1;
}

// Only proceed if the product exists
if ($id > 0 && isset($products[$id])) {

    $cart = getCart();
    $found = false;

    // Look for an existing line with the same id + same color
    foreach ($cart as $key => $item) {
        if ((int) $item['id'] === $id && $item['color'] === $color) {
            $cart[$key]['amount'] += $amount;
            $found = true;
            break;
        }
    }

    // Different color (or new item) → create a new line
    if (!$found) {
        $cart[] = [
            'id'     => $id,
            'amount' => $amount,
            'color'  => $color,
        ];
    }

    // Planter option: 'trainer' (free, default) or 'ceramic' (+$45 add-on, product id 13)
    // Ceramic planter is added as its own line so cart still stores only id + amount + color
    $planter_option = $_POST['planter_option'] ?? 'trainer';
    if ($planter_option === 'ceramic' && isset($products[13])) {
        $planter_found = false;
        foreach ($cart as $key => $item) {
            if ((int) $item['id'] === 13 && $item['color'] === $color) {
                $cart[$key]['amount'] += $amount;
                $planter_found = true;
                break;
            }
        }
        if (!$planter_found) {
            $cart[] = [
                'id'     => 13,
                'amount' => $amount,
                'color'  => $color,
            ];
        }
    }

    setCart($cart);
}

// Redirect to confirmation per Module 11 flow
header("Location: confirmation.php");
exit;
?>
