<?php
// ============================================
// MODULE 14 - Product Admin (CRUD)
// public/assignment/php/admin/product_admin.php
// ADDITIVE ONLY - does not modify any existing files
// ============================================

// ---- PDO connection ----
function getPDO() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=localhost;dbname=u957237009_nkactusadmin;charset=utf8mb4",
                "u957237009_rbrownadmin",
                "Naiomi831!",
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            die("DB connection failed: " . htmlspecialchars($e->getMessage()));
        }
    }
    return $pdo;
}

$pdo = getPDO();

// ---- Load mock product data as image fallback (matches DB rows by title) ----
// This lets the admin display the real product images even if the DB rows
// were imported without their `image` column populated.
$IMAGE_FALLBACK = [];
$IMAGE_FALLBACK_LIST = [];
$DEFAULT_IMAGE_BY_TITLE = [
    'fiddle leaf fig' => '../images/fiddle-leaf-fig.jpg',
    'monstera deliciosa' => '../images/monstera.jpg',
    'snake plant' => '../images/snake-plant.jpg',
    'golden pothos' => '../images/pothos.jpg',
    'peace lily' => '../images/peace-lily.jpg',
    'bird of paradise' => '../images/bird-of-paradise.jpg',
    'jade plant' => '../images/jade-plant.jpg',
    'zz plant' => '../images/zz-plant.jpg',
    'alocasia polly' => '../images/alocasia.jpg',
    'calathea orbifolia' => '../images/calathea.jpg',
    'pink princess philodendron' => '../images/philodendron.jpg',
    'rubber plant' => '../images/rubber-plant.jpg',
    'ceramic planter' => '../images/hero-plants.jpg',
];
$mockFile = __DIR__ . '/../parts/products_data.php';
if (is_file($mockFile)) {
    include $mockFile; // defines $products
    if (isset($products) && is_array($products)) {
        foreach ($products as $mp) {
            $key = strtolower(trim((string)($mp['name'] ?? '')));
            if ($key !== '') {
                $IMAGE_FALLBACK[$key] = (string)($mp['image'] ?? '');
                $IMAGE_FALLBACK_LIST[] = (string)($mp['image'] ?? '');
            }
        }
    }
}
$IMAGE_FALLBACK = array_merge($DEFAULT_IMAGE_BY_TITLE, $IMAGE_FALLBACK);
if (empty($IMAGE_FALLBACK_LIST)) $IMAGE_FALLBACK_LIST = array_values($DEFAULT_IMAGE_BY_TITLE);
function matchingProductImage($title, $IMAGE_FALLBACK) {
    $key = strtolower(trim((string)$title));
    return ($key !== '' && isset($IMAGE_FALLBACK[$key])) ? $IMAGE_FALLBACK[$key] : '';
}
function fallbackImage($title, $IMAGE_FALLBACK, $IMAGE_FALLBACK_LIST = [], $index = 0) {
    $key = strtolower(trim((string)$title));
    if ($key !== '' && isset($IMAGE_FALLBACK[$key])) return $IMAGE_FALLBACK[$key];
    $count = count($IMAGE_FALLBACK_LIST);
    return $count ? $IMAGE_FALLBACK_LIST[$index % $count] : '';
}
function adminImagePath($src) {
    $src = trim((string)$src);
    if ($src === '') return '';
    if (preg_match('/^https?:\/\//i', $src)) return $src;
    $filename = basename(parse_url($src, PHP_URL_PATH) ?: $src);
    if ($filename !== '' && is_file(__DIR__ . '/../../images/' . $filename)) return '../../images/' . $filename;
    if (strpos($src, 'images/') === 0) return '../../' . $src;
    if (strpos($src, '../images/') === 0) return '../' . $src;
    return $src;
}
function resolveAdminImage($primary, $fallback = '') {
    $primaryPath = adminImagePath($primary);
    if ($primaryPath !== '' && (preg_match('/^https?:\/\//i', $primaryPath) || is_file(__DIR__ . '/' . $primaryPath))) return $primaryPath;
    return adminImagePath($fallback);
}

// ---- Detect actual columns in the products table ----
function getTableColumns($pdo) {
    try {
        $cols = $pdo->query("SHOW COLUMNS FROM products")->fetchAll();
        return array_map(fn($c) => $c->Field, $cols);
    } catch (PDOException $e) { return []; }
}
$TABLE_COLS = getTableColumns($pdo);

// Map our logical field name -> first matching real DB column (or null if none exists)
function pickCol(array $cols, array $candidates) {
    foreach ($candidates as $c) if (in_array($c, $cols, true)) return $c;
    return null;
}
$COL = [
    'id'          => pickCol($TABLE_COLS, ['id']),
    'title'       => pickCol($TABLE_COLS, ['title', 'name']),
    'description' => pickCol($TABLE_COLS, ['description', 'desc']),
    'price'       => pickCol($TABLE_COLS, ['price']),
    'category'    => pickCol($TABLE_COLS, ['category']),
    'thumbnail'   => pickCol($TABLE_COLS, ['thumbnail', 'image']),
    'images'      => pickCol($TABLE_COLS, ['images']),
    'quantity'    => pickCol($TABLE_COLS, ['quantity', 'qty', 'stock']),
];

// Order by something stable
$orderCol = pickCol($TABLE_COLS, ['date_create', 'date_modify', 'created_at', 'updated_at', 'id']) ?? 'id';

// Fixed category list for dropdown (plus any extras already in DB)
$CATEGORY_OPTIONS = [
    'Indoor Plants', 'Outdoor Plants', 'Succulents', 'Flowering Plants',
    'Vines', 'Low-Light', 'Rare Plants', 'Planters & Pots', 'Accessories',
];

// ---- Handle IMAGE UPLOAD (admin only) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_image']) && !empty($_FILES['new_image']['name'])) {
    $file = $_FILES['new_image'];
    $allowed = ['jpg','jpeg','png','gif','webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $msg = '';
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $msg = 'Upload error (code ' . $file['error'] . ').';
    } elseif (!in_array($ext, $allowed, true)) {
        $msg = 'Only JPG, PNG, GIF, or WEBP allowed.';
    } elseif ($file['size'] > 5 * 1024 * 1024) {
        $msg = 'File too large (max 5MB).';
    } else {
        $safeBase = preg_replace('/[^a-z0-9_-]/i', '-', pathinfo($file['name'], PATHINFO_FILENAME));
        $filename = strtolower($safeBase) . '-' . time() . '.' . $ext;
        $destDir  = __DIR__ . '/../../images';
        if (!is_dir($destDir)) @mkdir($destDir, 0755, true);
        if (move_uploaded_file($file['tmp_name'], $destDir . '/' . $filename)) {
            $msg = 'Uploaded: ' . $filename;
        } else {
            $msg = 'Could not save file. Check /images/ folder permissions.';
        }
    }
    $redirectId = $_POST['id'] ?? '';
    $qs = $redirectId !== '' ? '?id=' . urlencode($redirectId) . '&msg=' . urlencode($msg) : '?msg=' . urlencode($msg);
    header("Location: " . $_SERVER['PHP_SELF'] . $qs);
    exit;
}

// ---- Handle DELETE IMAGE (admin only) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image']) && !empty($_POST['image_file'])) {
    $msg = '';
    // Strip any path — only allow a bare filename inside /images/
    $filename = basename((string)$_POST['image_file']);
    if (!preg_match('/^[A-Za-z0-9._-]+\.(jpg|jpeg|png|gif|webp)$/i', $filename)) {
        $msg = 'Invalid filename.';
    } else {
        $target = __DIR__ . '/../../images/' . $filename;
        if (is_file($target)) {
            if (@unlink($target)) {
                $msg = 'Deleted: ' . $filename;
            } else {
                $msg = 'Could not delete (check folder permissions).';
            }
        } else {
            $msg = 'File not found.';
        }
    }
    $redirectId = $_POST['id'] ?? '';
    $qs = $redirectId !== '' ? '?id=' . urlencode($redirectId) . '&msg=' . urlencode($msg) : '?msg=' . urlencode($msg);
    header("Location: " . $_SERVER['PHP_SELF'] . $qs);
    exit;
}

// ---- Handle DELETE ----
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([(int) $_POST['id']]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ---- Handle SAVE (insert / update) — only writes columns that actually exist ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $id = $_POST['id'] ?? 'new';

    // Logical field => submitted value
    $incoming = [
        'title'       => trim((string)($_POST['title'] ?? '')),
        'description' => (string)($_POST['description'] ?? ''),
        'price'       => (float)($_POST['price'] ?? 0),
        'category'    => (string)($_POST['category'] ?? ''),
        'thumbnail'   => (string)($_POST['thumbnail'] ?? ''),
        'images'      => (string)($_POST['images'] ?? ''),
        'quantity'    => (int)($_POST['quantity'] ?? 0),
    ];

    // Build column list for whatever columns this table actually has
    $setCols = []; $setVals = [];
    foreach ($incoming as $logical => $val) {
        $real = $COL[$logical] ?? null;
        if ($real) { $setCols[] = $real; $setVals[] = $val; }
    }

    try {
        if ($id === 'new') {
            $placeholders = implode(',', array_fill(0, count($setCols), '?'));
            $sql = "INSERT INTO products (" . implode(',', array_map(fn($c)=>"`$c`", $setCols)) . ") VALUES ($placeholders)";
            $pdo->prepare($sql)->execute($setVals);
            $newId = $pdo->lastInsertId();
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $newId);
            exit;
        } else {
            $assign = implode(',', array_map(fn($c) => "`$c` = ?", $setCols));
            $sql = "UPDATE products SET $assign WHERE `{$COL['id']}` = ?";
            $setVals[] = (int)$id;
            $pdo->prepare($sql)->execute($setVals);
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . (int)$id);
            exit;
        }
    } catch (PDOException $e) {
        die('<pre style="padding:2rem;font-family:monospace;color:#a00;">Save failed: '
            . htmlspecialchars($e->getMessage()) . '<br><br>SQL: ' . htmlspecialchars($sql)
            . '<br><br><a href="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">&larr; Back</a></pre>');
    }
}

// ---- Helper: read a logical field from a DB row using the column map ----
function rowVal($row, $logical, $COL, $default = '') {
    if (!$row) return $default;
    $real = $COL[$logical] ?? null;
    if (!$real) return $default;
    return $row->$real ?? $default;
}

// ---- Determine view: list vs edit ----
$id = $_GET['id'] ?? null;
$product = null;

if ($id !== null) {
    if ($id === 'new') {
        $product = (object)[];
    } else {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE `{$COL['id']}` = ?");
        $stmt->execute([(int) $id]);
        $product = $stmt->fetch();
        if (!$product) $product = (object)[];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Product Editor</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: Arial, Helvetica, sans-serif; background: #f4f4f4; color: #222; }
  .admin-header {
    background: #1a1a1a; color: #fff; padding: 1rem 2rem;
    display: flex; justify-content: space-between; align-items: center;
  }
  .admin-header h1 { font-size: 1.25rem; font-weight: 600; }
  .admin-nav { display: flex; gap: 1rem; align-items: center; }
  .admin-nav a { color: #aaa; text-decoration: none; font-size: 0.85rem; }
  .admin-nav a:hover, .admin-nav a.active { color: #fff; }
  .container { max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem; }
  .container.narrow { max-width: 800px; }
  .section-head {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 1.25rem;
  }
  .section-head h2 { font-size: 1.1rem; }
  .card {
    background: #fff; border-radius: 8px; padding: 2rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1); margin-bottom: 1.25rem;
  }
  .form-group { margin-bottom: 1.25rem; }
  label, .form-label { display: block; font-weight: 600; margin-bottom: 0.3rem; font-size: 0.9rem; }
  input[type="text"], input[type="email"], input[type="number"], textarea, .form-control {
    width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ccc;
    border-radius: 4px; font-size: 0.95rem; font-family: inherit;
  }
  input:focus, textarea:focus { outline: none; border-color: #555; }
  .btn {
    background: #1a1a1a; color: #fff; border: none; padding: 0.6rem 1.25rem;
    border-radius: 4px; font-size: 0.9rem; cursor: pointer; text-decoration: none;
    display: inline-block;
  }
  .btn:hover { background: #333; }
  .btn-danger { background: #a00; }
  .btn-danger:hover { background: #c00; }
  .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.25rem;
  }
  .product-card {
    background: #fff; border: 1px solid #eee; border-radius: 6px;
    overflow: hidden; display: flex; flex-direction: column;
    transition: box-shadow .15s, transform .15s;
  }
  .product-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); transform: translateY(-2px); }
  .product-card .img-wrap {
    width: 100%; aspect-ratio: 1; background: #f4f4f4; overflow: hidden;
  }
  .product-card .img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .product-card .img-wrap .no-img {
    width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
    color: #aaa; font-size: 0.8rem; letter-spacing: .05em; text-transform: uppercase;
  }
  .product-card .info { padding: 0.85rem 1rem 1rem; display: flex; flex-direction: column; gap: 0.4rem; flex: 1; }
  .product-card .info strong { font-size: 0.95rem; line-height: 1.3; color: #1a1a1a; }
  .product-card .info .cat { font-size: 0.75rem; color: #888; text-transform: uppercase; letter-spacing: .05em; }
  .product-card .info .price { font-size: 1rem; font-weight: 600; color: #1a1a1a; margin-top: auto; }
  .product-card .info .btn { margin-top: 0.5rem; text-align: center; font-size: 0.8rem; padding: 0.5rem; }
  .empty { text-align: center; padding: 2rem; color: #666; }
  .err { color: #a00; margin: 1rem 0; font-size: 0.9rem; }
  .image-thumbs { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem; }
  .image-thumbs img { width: 70px; height: 70px; object-fit: cover; border-radius: 4px; }
  .image-library {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
    gap: 0.5rem; padding: 0.75rem; background: #fafafa; border: 1px solid #eee; border-radius: 4px;
  }
  .image-library .lib-item { position: relative; }
  .image-library img {
    width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 4px;
    cursor: pointer; border: 2px solid transparent; transition: border-color .15s;
    display: block;
  }
  .image-library img:hover { border-color: #1a1a1a; }
  .image-library .del-img {
    position: absolute; top: 4px; right: 4px;
    width: 22px; height: 22px; border-radius: 50%; border: none;
    background: rgba(170,0,0,0.9); color: #fff; font-size: 14px; font-weight: bold;
    line-height: 1; cursor: pointer; display: flex; align-items: center; justify-content: center;
    padding: 0;
  }
  .image-library .del-img:hover { background: #c00; }
  .back-link { font-size: 0.85rem; color: #666; text-decoration: none; }
  .back-link:hover { color: #222; }
</style>
</head>
<body>
  <div class="admin-header">
    <h1>Admin Panel</h1>
    <div class="admin-nav">
      <a href="product_admin.php" class="active">Products</a>
      <a href="users.php">Users</a>
      <a href="../index.php">&larr; Back to Site</a>
    </div>
  </div>

  <div class="container">

  <?php if ($product === null): ?>
    <!-- ============ PRODUCT LIST ============ -->
    <div class="section-head">
      <h2>Product Editor</h2>
      <a href="?id=new" class="btn">+ Add New Product</a>
    </div>

    <div class="card">
      <?php
      try {
          $rows = $pdo->query("SELECT * FROM products ORDER BY `$orderCol` DESC")->fetchAll();
      } catch (PDOException $e) {
          $rows = [];
          echo '<div class="err">Could not load products: ' . htmlspecialchars($e->getMessage()) . '</div>';
      }

      echo '<div class="product-grid">';
      echo array_reduce($rows, function ($html, $p) use ($COL, $IMAGE_FALLBACK, $IMAGE_FALLBACK_LIST) {
          static $fallbackIndex = 0;
          $rawTitle = (string) rowVal($p, 'title', $COL);
          $matchingImage = matchingProductImage($rawTitle, $IMAGE_FALLBACK);
          $thumb = resolveAdminImage(
              $matchingImage !== '' ? $matchingImage : (string) rowVal($p, 'thumbnail', $COL),
              fallbackImage($rawTitle, $IMAGE_FALLBACK, $IMAGE_FALLBACK_LIST, $fallbackIndex)
          );
          $fallbackIndex++;
          $thumb = htmlspecialchars($thumb);
          $title = htmlspecialchars($rawTitle !== '' ? $rawTitle : 'Untitled product');
          $cat   = htmlspecialchars((string) rowVal($p, 'category', $COL));
          $price = (float) rowVal($p, 'price', $COL, 0);
          $pid   = (int) ($p->{$COL['id']} ?? 0);
          $img   = $thumb
              ? '<img src="' . $thumb . '" alt="' . $title . '" onerror="this.parentNode.innerHTML=\'<div class=&quot;no-img&quot;>No image</div>\'">'
              : '<div class="no-img">No image</div>';
          return $html .
            '<a href="?id=' . $pid . '" class="product-card" style="text-decoration:none;color:inherit;">' .
              '<div class="img-wrap">' . $img . '</div>' .
              '<div class="info">' .
                '<strong>' . $title . '</strong>' .
                ($cat ? '<span class="cat">' . $cat . '</span>' : '') .
                '<span class="price">$' . number_format($price, 2) . '</span>' .
                '<span class="btn">Edit</span>' .
              '</div>' .
            '</a>';
      }, '');
      echo '</div>';

      if (empty($rows)) {
          echo '<div class="empty">No products yet. Click "Add New Product" to begin.</div>';
      }
      ?>
    </div>

  <?php else: ?>
    <!-- ============ EDIT / NEW FORM ============ -->
    <div class="section-head">
      <h2><?= ($id === 'new') ? 'Add New Product' : 'Edit Product #' . (int)$id ?></h2>
      <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="back-link">&larr; Back to product list</a>
    </div>

    <?php if (!empty($_GET['msg'])): ?>
      <div class="card" style="padding:0.75rem 1rem;background:#eef7ee;border:1px solid #cfe5cf;color:#155724;">
        <?= htmlspecialchars($_GET['msg']) ?>
      </div>
    <?php endif; ?>

    <div class="card" style="padding:1rem 1.5rem;">
      <form method="POST" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>"
            style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
        <input type="hidden" name="id" value="<?= htmlspecialchars((string)($product->{$COL['id']} ?? 'new')) ?>">
        <strong style="font-size:0.9rem;">Upload new image:</strong>
        <input type="file" name="new_image" accept="image/*" required>
        <button type="submit" name="upload_image" value="1" class="btn">Upload to Library</button>
        <small style="color:#666;">Saves to /images/ (max 5MB)</small>
      </form>
    </div>

    <div class="card">
      <?php
        $pid_val   = (string)($product->{$COL['id']} ?? 'new');
        $v_title   = (string) rowVal($product, 'title', $COL);
        $v_price   = (string) rowVal($product, 'price', $COL, 0);
        $v_cat     = (string) rowVal($product, 'category', $COL);
        $v_qty     = (string) rowVal($product, 'quantity', $COL, 0);
        $v_desc    = (string) rowVal($product, 'description', $COL);
        $matchingImage = matchingProductImage($v_title, $IMAGE_FALLBACK);
        $v_thumb   = resolveAdminImage(
            $matchingImage !== '' ? $matchingImage : (string) rowVal($product, 'thumbnail', $COL),
            fallbackImage($v_title, $IMAGE_FALLBACK, $IMAGE_FALLBACK_LIST, max(0, (int)$pid_val - 1))
        );
        $v_images  = (string) rowVal($product, 'images', $COL);

        // Merge fixed options with any extras already in DB so existing values aren't lost
        try {
            $existingCats = $pdo->query("SELECT DISTINCT `{$COL['category']}` AS c FROM products WHERE `{$COL['category']}` IS NOT NULL AND `{$COL['category']}` <> ''")->fetchAll();
            foreach ($existingCats as $ec) {
                $cVal = trim((string)$ec->c);
                if ($cVal !== '' && !in_array($cVal, $CATEGORY_OPTIONS, true)) $CATEGORY_OPTIONS[] = $cVal;
            }
        } catch (PDOException $e) {}
      ?>
      <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <input type="hidden" name="id" value="<?= htmlspecialchars($pid_val) ?>">

        <div class="form-group">
          <label>Title</label>
          <input type="text" name="title" value="<?= htmlspecialchars($v_title) ?>" required>
        </div>

        <div class="form-group">
          <label>Price</label>
          <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($v_price) ?>">
        </div>

        <div class="form-group">
          <label>Category</label>
          <select name="category" class="form-control">
            <option value="">— Select a category —</option>
            <?php foreach ($CATEGORY_OPTIONS as $opt): ?>
              <option value="<?= htmlspecialchars($opt) ?>" <?= (strcasecmp($opt, $v_cat) === 0) ? 'selected' : '' ?>>
                <?= htmlspecialchars($opt) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label>Quantity</label>
          <input type="number" min="0" step="1" name="quantity" value="<?= htmlspecialchars($v_qty) ?>">
        </div>

        <div class="form-group">
          <label>Description</label>
          <textarea name="description" rows="4"><?= htmlspecialchars($v_desc) ?></textarea>
        </div>

        <div class="form-group">
          <label>Thumbnail (path or URL)</label>
          <input type="text" id="thumbnail-input" name="thumbnail" value="<?= htmlspecialchars($v_thumb) ?>">
          <?php if (!empty($v_thumb)): ?>
            <div class="image-thumbs"><img src="<?= htmlspecialchars($v_thumb) ?>" alt=""></div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label>Images (comma-separated)</label>
          <input type="text" id="images-input" name="images" value="<?= htmlspecialchars($v_images) ?>">
          <?php
          if (!empty($v_images)) {
              $imgs = explode(',', $v_images);
              echo '<div class="image-thumbs">';
              echo array_reduce($imgs, function ($html, $src) {
                  $src = trim($src);
                  if ($src === '') return $html;
                  return $html . '<img src="' . htmlspecialchars($src) . '" alt="">';
              }, '');
              echo '</div>';
          }
          ?>
        </div>

        <div class="form-group">
          <label>Image Library <small style="font-weight:400;color:#666;">— click to set thumbnail · shift-click to add to images</small></label>
          <div class="image-library">
            <?php
            $imgDir = __DIR__ . '/../../images';
            $imgWebPath = '../../images/';
            $files = is_dir($imgDir) ? array_values(array_filter(scandir($imgDir), function($f) {
                return preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $f);
            })) : [];
            foreach ($files as $f) {
                $src = $imgWebPath . $f;
                echo '<div class="lib-item">'
                   . '<img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($f) . '" '
                   . 'title="' . htmlspecialchars($f) . '" data-src="' . htmlspecialchars($src) . '" '
                   . 'class="lib-img">'
                   . '<button type="button" class="del-img" data-file="' . htmlspecialchars($f) . '" title="Delete image">&times;</button>'
                   . '</div>';
            }
            if (empty($files)) echo '<small style="color:#666;">No images found in /images/ folder.</small>';
            ?>
          </div>
        </div>
        <script>
          document.querySelectorAll('.lib-img').forEach(function(img) {
            img.addEventListener('click', function(e) {
              var src = this.dataset.src;
              if (e.shiftKey) {
                var input = document.getElementById('images-input');
                var current = input.value.trim();
                input.value = current ? current + ',' + src : src;
              } else {
                document.getElementById('thumbnail-input').value = src;
              }
            });
          });
          document.querySelectorAll('.del-img').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
              e.preventDefault(); e.stopPropagation();
              var file = this.dataset.file;
              if (!confirm('Delete image "' + file + '" from the library? This cannot be undone.')) return;
              var f = document.createElement('form');
              f.method = 'POST';
              f.action = <?= json_encode($_SERVER['PHP_SELF']) ?>;
              f.innerHTML =
                '<input type="hidden" name="delete_image" value="1">' +
                '<input type="hidden" name="image_file" value="' + file.replace(/"/g,'&quot;') + '">' +
                '<input type="hidden" name="id" value="<?= htmlspecialchars($pid_val) ?>">';
              document.body.appendChild(f); f.submit();
            });
          });
        </script>

        <button type="submit" name="save" value="1" class="btn">Save Changes</button>
      </form>
    </div>

    <?php if ($id !== 'new'): ?>
      <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>"
            onsubmit="return confirm('Delete this product? This cannot be undone.');">
        <input type="hidden" name="id" value="<?= (int)$id ?>">
        <button type="submit" name="delete" value="1" class="btn btn-danger">Delete Product</button>
      </form>
    <?php endif; ?>
  <?php endif; ?>

  </div>
</body>
</html>
