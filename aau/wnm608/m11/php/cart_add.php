<?php
require_once("functions.php");

$id = $_POST['id'];
$amount = $_POST['amount'];
$color = $_POST['color'];

$cart = getCart();

$found = false;

foreach($cart as &$item) {
    if($item['id'] == $id && $item['color'] == $color) {
        $item['amount'] += $amount;
        $found = true;
        break;
    }
}

if(!$found) {
    $cart[] = [
        "id" => $id,
        "amount" => $amount,
        "color" => $color
    ];
}

setCart($cart);

header("Location: confirmation.php?id=$id");
exit();
