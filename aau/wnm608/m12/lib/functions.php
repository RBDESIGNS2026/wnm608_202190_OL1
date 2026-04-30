<?php
// ============================================
// /lib/functions.php
// Shared helpers for the dynamic product API
// (Adds new helpers — does NOT replace php/functions.php)
// ============================================

/**
 * Load the shared product catalog (mock DB).
 * Returns an indexed array of products with normalized fields:
 *   id, title, description, category, price, image, date
 */
function loadProducts() {
    // products_data.php defines $products keyed by id
    $path = __DIR__ . "/../php/parts/products_data.php";
    if (!file_exists($path)) return [];
    include $path;

    $list = [];
    $i = 0;
    foreach ($products as $id => $p) {
        // Derive a simple category from care/light so filters have something to bite on
        $care  = strtolower($p['care']  ?? '');
        $light = strtolower($p['light'] ?? '');

        $category = 'indoor';
        if (strpos($light, 'low') !== false)    $category = 'low-light';
        elseif (strpos($care, 'easy') !== false) $category = 'easy-care';
        elseif (strpos($care, 'advanced') !== false) $category = 'rare';

        $list[] = [
            "id"          => (int)$id,
            "title"       => $p['name']  ?? '',
            "latin"       => $p['latin'] ?? '',
            "description" => $p['desc']  ?? '',
            "category"    => $category,
            "price"       => (float)($p['price'] ?? 0),
            "image"       => $p['image'] ?? '',
            // Synthetic "date" so newest/oldest sort is stable & meaningful
            "date"        => date('Y-m-d', strtotime("-{$i} day")),
        ];
        $i++;
    }
    return $list;
}

/**
 * Whitelist a column name so user input can never inject arbitrary SQL/array keys.
 */
function safeColumn($col, $allowed, $default) {
    return in_array($col, $allowed, true) ? $col : $default;
}

/**
 * Whitelist sort direction.
 */
function safeDirection($dir) {
    $dir = strtoupper((string)$dir);
    return ($dir === 'ASC') ? 'ASC' : 'DESC';
}
