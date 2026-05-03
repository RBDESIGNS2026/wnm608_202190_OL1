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

// ---- Detect a sensible "order by" column (table schemas vary) ----
function detectOrderColumn($pdo) {
    try {
        $cols = $pdo->query("SHOW COLUMNS FROM products")->fetchAll();
        $names = array_map(fn($c) => $c->Field, $cols);
        foreach (['date_create', 'date_modify', 'created_at', 'updated_at', 'id'] as $candidate) {
            if (in_array($candidate, $names, true)) return $candidate;
        }
    } catch (PDOException $e) {}
    return 'id';
}
$orderCol = detectOrderColumn($pdo);

// ---- Handle DELETE ----
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([(int) $_POST['id']]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ---- Handle SAVE (insert / update) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $id          = $_POST['id'] ?? 'new';
    $title       = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price       = (float) ($_POST['price'] ?? 0);
    $category    = $_POST['category'] ?? '';
    $thumbnail   = $_POST['thumbnail'] ?? '';
    $images      = $_POST['images'] ?? '';
    $quantity    = (int) ($_POST['quantity'] ?? 0);

    if ($id === 'new') {
        $stmt = $pdo->prepare(
            "INSERT INTO products (title, description, price, category, thumbnail, images, quantity)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$title, $description, $price, $category, $thumbnail, $images, $quantity]);
        $newId = $pdo->lastInsertId();
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $newId);
        exit;
    } else {
        $stmt = $pdo->prepare(
            "UPDATE products SET
                title = ?, description = ?, price = ?, category = ?,
                thumbnail = ?, images = ?, quantity = ?
             WHERE id = ?"
        );
        $stmt->execute([$title, $description, $price, $category, $thumbnail, $images, $quantity, (int) $id]);
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . (int) $id);
        exit;
    }
}

// ---- Determine view: list vs edit ----
$id = $_GET['id'] ?? null;
$product = null;

$empty_product = (object) [
    "id" => "new", "title" => "", "description" => "",
    "price" => 0, "category" => "", "thumbnail" => "",
    "images" => "", "quantity" => 0,
];

if ($id !== null) {
    if ($id === 'new') {
        $product = $empty_product;
    } else {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([(int) $id]);
        $product = $stmt->fetch();
        if (!$product) $product = $empty_product;
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
  .container { max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
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
  .product-row {
    display: flex; align-items: center; gap: 1rem;
    padding: 0.75rem; border: 1px solid #eee; border-radius: 4px; margin-bottom: 0.5rem;
  }
  .product-row img { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
  .product-row strong { display: block; font-size: 0.95rem; }
  .product-row small { color: #666; }
  .product-row .grow { flex: 1; }
  .empty { text-align: center; padding: 2rem; color: #666; }
  .err { color: #a00; margin: 1rem 0; font-size: 0.9rem; }
  .image-thumbs { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem; }
  .image-thumbs img { width: 70px; height: 70px; object-fit: cover; border-radius: 4px; }
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
          $rows = $pdo->query("SELECT * FROM products ORDER BY $orderCol DESC")->fetchAll();
      } catch (PDOException $e) {
          $rows = [];
          echo '<div class="err">Could not load products: ' . htmlspecialchars($e->getMessage()) . '</div>';
      }

      echo array_reduce($rows, function ($html, $p) {
          $thumb = htmlspecialchars($p->thumbnail ?? '');
          $title = htmlspecialchars($p->title ?? '');
          $cat   = htmlspecialchars($p->category ?? '');
          $pid   = (int) $p->id;
          return $html .
            '<div class="product-row">' .
              ($thumb ? '<img src="' . $thumb . '" alt="">' : '') .
              '<div class="grow"><strong>' . $title . '</strong>' .
              '<small>' . ($cat ? $cat . ' · ' : '') . '$' . number_format((float)$p->price, 2) . '</small></div>' .
              '<a href="?id=' . $pid . '" class="btn">Edit</a>' .
            '</div>';
      }, '');

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

    <div class="card">
      <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <input type="hidden" name="id" value="<?= htmlspecialchars((string)($product->id ?? 'new')) ?>">

        <div class="form-group">
          <label>Title</label>
          <input type="text" name="title" value="<?= htmlspecialchars($product->title) ?>">
        </div>

        <div class="form-group">
          <label>Price</label>
          <input type="number" step="0.01" name="price" value="<?= htmlspecialchars((string)$product->price) ?>">
        </div>

        <div class="form-group">
          <label>Category</label>
          <input type="text" name="category" value="<?= htmlspecialchars($product->category) ?>">
        </div>

        <div class="form-group">
          <label>Quantity</label>
          <input type="number" min="0" step="1" name="quantity" value="<?= htmlspecialchars((string)$product->quantity) ?>">
        </div>

        <div class="form-group">
          <label>Description</label>
          <textarea name="description" rows="4"><?= htmlspecialchars($product->description) ?></textarea>
        </div>

        <div class="form-group">
          <label>Thumbnail (path or URL)</label>
          <input type="text" name="thumbnail" value="<?= htmlspecialchars($product->thumbnail) ?>">
          <?php if (!empty($product->thumbnail)): ?>
            <div class="image-thumbs"><img src="<?= htmlspecialchars($product->thumbnail) ?>" alt=""></div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label>Images (comma-separated)</label>
          <input type="text" name="images" value="<?= htmlspecialchars($product->images) ?>">
          <?php
          if (!empty($product->images)) {
              $imgs = explode(',', $product->images);
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
