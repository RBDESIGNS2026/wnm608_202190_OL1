<?php
// Load users from JSON
$users = json_decode(file_get_contents('../data/users.json'));

// UPDATE DATA (POST)
if(isset($_POST['user-name'])) {
  if(isset($_GET['action']) && $_GET['action'] === 'create') {
    // Create new user
    $empty_user = new stdClass();
    $empty_user->name = $_POST['user-name'];
    $empty_user->email = $_POST['user-email'];
    $empty_user->type = $_POST['user-type'];
    $empty_user->classes = array_map('trim', explode(",", $_POST['user-classes']));
    $id = count($users);
    $users[] = $empty_user;
    file_put_contents('../data/users.json', json_encode($users));
    header("location:{$_SERVER['PHP_SELF']}?id={$id}");
    exit;
  } else if(isset($_GET['id'])) {
    $users[$_GET['id']]->name = $_POST['user-name'];
    $users[$_GET['id']]->email = $_POST['user-email'];
    $users[$_GET['id']]->type = $_POST['user-type'];
    $users[$_GET['id']]->classes = array_map('trim', explode(",", $_POST['user-classes']));
    file_put_contents('../data/users.json', json_encode($users));
  }
}

// DELETE
if(isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
  array_splice($users, $_GET['id'], 1);
  file_put_contents('../data/users.json', json_encode($users));
  header("location:{$_SERVER['PHP_SELF']}");
  exit;
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
    body { font-family: Arial, Helvetica, sans-serif; background: #eee; color: #222; }

    /* Top Nav */
    .admin-nav {
      background: #333; color: #fff; display: flex; align-items: center;
      padding: 0 1.5rem; height: 48px;
    }
    .admin-nav .brand { font-weight: 600; font-size: 1rem; margin-right: auto; }
    .admin-nav a {
      color: #ccc; text-decoration: none; font-size: 0.9rem;
      padding: 0.5rem 1rem;
    }
    .admin-nav a:hover, .admin-nav a.active { color: #fff; }

    /* Container */
    .container { max-width: 750px; margin: 2rem auto; padding: 0 1rem; }

    /* Card */
    .card {
      background: #fff; border-radius: 8px; padding: 2rem;
      box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }

    /* Top row: Back / Delete */
    .top-row { display: flex; justify-content: space-between; margin-bottom: 1.5rem; }
    .top-row a { text-decoration: none; font-size: 0.9rem; font-weight: 600; }
    .top-row .back-link { color: #222; }
    .top-row .delete-link { color: #c00; }
    .top-row a:hover { text-decoration: underline; }

    /* Two-column layout */
    .two-col { display: flex; gap: 2rem; }
    .col-info { flex: 1; }
    .col-form { flex: 1; }

    .col-info h2 { font-size: 1.4rem; margin-bottom: 1rem; }
    .col-info p { margin-bottom: 0.4rem; font-size: 0.95rem; }
    .col-info strong { font-weight: 600; }

    .col-form h3 { font-size: 1.1rem; margin-bottom: 1rem; }
    .col-form label { display: block; font-size: 0.8rem; color: #888; margin-top: 0.75rem; margin-bottom: 0.2rem; }
    .col-form input[type="text"],
    .col-form input[type="email"] {
      width: 100%; padding: 0.4rem 0.5rem; border: none; border-bottom: 1px solid #ccc;
      font-size: 0.95rem; outline: none;
    }
    .col-form input:focus { border-bottom-color: #555; }
    .col-form input[type="submit"] {
      margin-top: 1.5rem; background: #fff; color: #222; border: 1px solid #ccc;
      padding: 0.5rem 1.5rem; border-radius: 4px; font-size: 0.9rem; cursor: pointer;
      display: block; margin-left: auto; margin-right: auto;
    }
    .col-form input[type="submit"]:hover { background: #f4f4f4; }

    /* User list page */
    .user-list { list-style: none; }
    .user-list li {
      background: #fff; padding: 1rem; margin-bottom: 0.5rem; border-radius: 6px;
      display: flex; justify-content: space-between; align-items: center;
      box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .user-list a { text-decoration: none; color: #222; font-weight: 600; }
    .user-list a:hover { text-decoration: underline; }
    .btn-delete-list { color: #c00; text-decoration: none; font-size: 0.85rem; }
    .btn-delete-list:hover { text-decoration: underline; }

    /* New user form */
    .new-form label { display: block; font-weight: 600; margin-top: 1rem; margin-bottom: 0.3rem; font-size: 0.9rem; }
    .new-form input[type="text"],
    .new-form input[type="email"] {
      width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ccc;
      border-radius: 4px; font-size: 0.95rem;
    }
    .new-form input[type="submit"] {
      margin-top: 1.5rem; background: #333; color: #fff; border: none;
      padding: 0.6rem 1.5rem; border-radius: 4px; font-size: 0.95rem; cursor: pointer;
    }
    .new-form input[type="submit"]:hover { background: #555; }
  </style>
</head>
<body>
  <div class="admin-nav">
    <span class="brand">User Admin</span>
    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="<?php echo !isset($_GET['id']) ? 'active' : ''; ?>">User List</a>
    <a href="?id=new" class="<?php echo (isset($_GET['id']) && $_GET['id'] === 'new') ? 'active' : ''; ?>">Add New User</a>
  </div>

  <div class="container">
<?php
$id = $_GET['id'] ?? null;

if($id === 'new') {
  // ADD NEW USER FORM
  echo '<div class="card new-form">';
  echo '<h2>Add New User</h2>';
  echo '<form method="POST" action="?action=create">';
  echo '<label>Name</label><input type="text" name="user-name" required>';
  echo '<label>Email</label><input type="email" name="user-email" required>';
  echo '<label>Type</label><input type="text" name="user-type" required>';
  echo '<label>Classes (comma separated)</label><input type="text" name="user-classes">';
  echo '<input type="submit" value="Create User">';
  echo '</form></div>';

} elseif(isset($users[$id])) {
  // EDIT USER — two-column layout
  $u = $users[$id];
  $classes = implode(", ", $u->classes);
  ?>
  <div class="card">
    <div class="top-row">
      <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="back-link">Back</a>
      <a href="?action=delete&id=<?php echo $id; ?>" class="delete-link" onclick="return confirm('Delete this user?')">Delete</a>
    </div>
    <div class="two-col">
      <div class="col-info">
        <h2><?php echo htmlspecialchars($u->name); ?></h2>
        <p><strong>Type</strong> <?php echo htmlspecialchars($u->type); ?></p>
        <p><strong>Email</strong> <?php echo htmlspecialchars($u->email); ?></p>
        <p><strong>Classes</strong> <?php echo htmlspecialchars($classes); ?></p>
      </div>
      <div class="col-form">
        <h3>Edit User</h3>
        <form method="POST" action="?id=<?php echo $id; ?>">
          <label>Name</label>
          <input type="text" name="user-name" value="<?php echo htmlspecialchars($u->name); ?>">
          <label>Type</label>
          <input type="text" name="user-type" value="<?php echo htmlspecialchars($u->type); ?>">
          <label>Email</label>
          <input type="email" name="user-email" value="<?php echo htmlspecialchars($u->email); ?>">
          <label>Classes</label>
          <input type="text" name="user-classes" value="<?php echo htmlspecialchars($classes); ?>">
          <input type="submit" value="Save Changes">
        </form>
      </div>
    </div>
  </div>
  <?php
} else {
  // USER LIST
  echo '<ul class="user-list">';
  foreach($users as $i => $u) {
    echo '<li>';
    echo '<a href="?id=' . $i . '">' . htmlspecialchars($u->name) . '</a>';
    echo ' <a href="?action=delete&id=' . $i . '" class="btn-delete-list" onclick="return confirm(\'Delete this user?\')">Delete</a>';
    echo '</li>';
  }
  echo '</ul>';
}
?>
  </div>
</body>
</html>
