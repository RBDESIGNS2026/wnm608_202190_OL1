<?php
// ============================================
// /data/API.php
// JSON API for dynamic product list
// Reads JSON from php://input, returns JSON
// ============================================

header('Content-Type: application/json');

require_once __DIR__ . "/../lib/functions.php";

// 1. Read raw input
$raw   = file_get_contents("php://input");
$input = json_decode($raw, true);

if (!is_array($input)) {
    die(json_encode(["error" => "Invalid request"]));
}

$type = $input['type'] ?? '';
$products = loadProducts();

$output = ["result" => []];

switch ($type) {

    // ---------- Default: all products, newest first, limit 12 ----------
    case 'products_all':
        usort($products, function($a, $b) {
            return strcmp($b['date'], $a['date']);
        });
        $output['result'] = array_slice($products, 0, 12);
        break;

    // ---------- Search by title/description/category ----------
    case 'productSearch':
        $search = strtolower(trim((string)($input['search'] ?? '')));
        if ($search === '') {
            $output['result'] = $products;
        } else {
            $output['result'] = array_values(array_filter($products, function($p) use ($search) {
                return strpos(strtolower($p['title']), $search) !== false
                    || strpos(strtolower($p['description']), $search) !== false
                    || strpos(strtolower($p['category']), $search) !== false;
            }));
        }
        break;

    // ---------- Filter by column = value ----------
    case 'product_filter':
        $allowed = ['category', 'care', 'light'];
        $column  = safeColumn($input['column'] ?? '', $allowed, 'category');
        $value   = strtolower(trim((string)($input['value'] ?? '')));

        if ($value === '') {
            $output['result'] = $products;
        } else {
            $output['result'] = array_values(array_filter($products, function($p) use ($column, $value) {
                return isset($p[$column]) && strtolower((string)$p[$column]) === $value;
            }));
        }
        break;

    // ---------- Sort by column / direction ----------
    case 'product_sort':
        $allowed   = ['price', 'date', 'title'];
        $column    = safeColumn($input['column'] ?? '', $allowed, 'date');
        $direction = safeDirection($input['direction'] ?? 'DESC');

        usort($products, function($a, $b) use ($column, $direction) {
            $va = $a[$column] ?? '';
            $vb = $b[$column] ?? '';
            if (is_numeric($va) && is_numeric($vb)) {
                $cmp = ($va <=> $vb);
            } else {
                $cmp = strcmp((string)$va, (string)$vb);
            }
            return $direction === 'ASC' ? $cmp : -$cmp;
        });
        $output['result'] = $products;
        break;

    default:
        die(json_encode(["error" => "Invalid request"]));
}

echo json_encode($output, JSON_NUMERIC_CHECK);
