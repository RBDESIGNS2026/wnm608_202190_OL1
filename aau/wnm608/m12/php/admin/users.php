<?php
$json = file_get_contents(__DIR__ . '/users.json');
$users = json_decode($json, true);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id < 0 || $id >= count($users)) $id = 0;

$user = $users[$id];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $users[$id] = [
    'name'    => $_POST['name'] ?? '',
    'email'   => $_POST['email'] ?? '',
    'type'    => $_POST['type'] ?? '',
    'classes' => $_POST['classes'] ?? '',
  ];
  file_put_contents(__DIR__ . '/users.json', json_encode($users, JSON_PRETTY_PRINT));
  $user = $users[$id];
  $saved = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — User Editor</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, Helvetica, sans-serif; background: #f4f4f4; color: #222; }
    .admin-header {
      background: #1a1a1a; color: #fff; padding: 1rem 2rem;
      display: flex; justify-content: space-between; align-items: center;
    }
    .admin-header h1 { font-size: 1.25rem; font-weight: 600; }
    .admin-header a { color: #aaa; text-decoration: none; font-size: 0.85rem; }
    .admin-header a:hover { color: #fff; }
    .container { max-width: 600px; margin: 2rem auto; padding: 0 1rem; }
    .user-nav { margin-bottom: 1.5rem; display: flex; gap: 0.5rem; flex-wrap: wrap; }
    .user-nav a {
      padding: 0.4rem 0.8rem; background: #ddd; border-radius: 4px;
      text-decoration: none; color: #222; font-size: 0.85rem;
    }
    .user-nav a.active { background: #1a1a1a; color: #fff; }
    .card {
      background: #fff; border-radius: 8px; padding: 2rem;
      box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }
    .form-group { margin-bottom: 1.25rem; }
    label { display: block; font-weight: 600; margin-bottom: 0.3rem; font-size: 0.9rem; }
    input[type="text"], input[type="email"] {
      width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ccc;
      border-radius: 4px; font-size: 0.95rem;
    }
    input:focus { outline: none; border-color: #555; }
    .btn-save {
      background: #1a1a1a; color: #fff; border: none; padding: 0.6rem 1.5rem;
      border-radius: 4px; font-size: 0.95rem; cursor: pointer;
    }
    .btn-save:hover { background: #333; }
    .msg { background: #d4edda; color: #155724; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1rem; }
  </style>
</head>
<body>
  <div class="admin-header">
    <h1>User Editor</h1>
    <a href="../index.php">&larr; Back to Site</a>
  </div>

  <div class="container">
    <?php if (!empty($saved)): ?>
      <div class="msg">Changes saved successfully.</div>
    <?php endif; ?>

    <div class="user-nav">
      <?php foreach ($users as $i => $u): ?>
        <a href="?id=<?= $i ?>" class="<?= $i === $id ? 'active' : '' ?>"><?= htmlspecialchars($u['name']) ?></a>
      <?php endforeach; ?>
    </div>

    <div class="card">
      <form method="POST" action="?id=<?= $id ?>">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
        </div>
        <div class="form-group">
          <label for="type">Type</label>
          <input type="text" id="type" name="type" value="<?= htmlspecialchars($user['type']) ?>">
        </div>
        <div class="form-group">
          <label for="classes">Classes (comma separated)</label>
          <input type="text" id="classes" name="classes" value="<?= htmlspecialchars($user['classes']) ?>">
        </div>
        <button type="submit" class="btn-save">Save Changes</button>
      </form>
    </div>
  </div>
</body>
</html>
