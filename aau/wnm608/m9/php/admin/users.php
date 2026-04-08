<?php
// DEBUG
print_r($_GET);
print_r($_POST);

// Load users from JSON
$users = json_decode(file_get_contents('../data/users.json'));

// UPDATE DATA (POST)
if(isset($_POST['user-name'])) {
  if(isset($_GET['action']) && $_GET['action'] === 'create') {
    // handled in switch below
  } else {
    $users[$_GET['id']]->name = $_POST['user-name'];
    $users[$_GET['id']]->email = $_POST['user-email'];
    $users[$_GET['id']]->type = $_POST['user-type'];
    $users[$_GET['id']]->classes = explode(",", $_POST['user-classes']);
    file_put_contents('../data/users.json', json_encode($users));
  }
}

// SWITCH LOGIC (CRUD)
if(isset($_GET['action'])) {
  switch($_GET['action']) {
    case 'update':
      break;
    case 'create':
      $empty_user = new stdClass();
      $empty_user->name = $_POST['user-name'];
      $empty_user->email = $_POST['user-email'];
      $empty_user->type = $_POST['user-type'];
      $empty_user->classes = explode(",", $_POST['user-classes']);
      $id = count($users);
      $users[] = $empty_user;
      file_put_contents('../data/users.json', json_encode($users));
      header("location:{$_SERVER['PHP_SELF']}?id={$id}");
      exit;
      break;
    case 'delete':
      array_splice($users, $_GET['id'], 1);
      file_put_contents('../data/users.json', json_encode($users));
      header("location:{$_SERVER['PHP_SELF']}");
      exit;
      break;
  }
}

// DISPLAY FUNCTION
function showUserPage($user, $isNew = false) {
  $classes = implode(", ", $user->classes);
  if($isNew) {
    $formAction = "?action=create";
  } else {
    $formAction = "";
  }
  echo <<<HTML
  <h2>{$user->name}</h2>
  <form method="POST" action="{$formAction}">
    <label>Name</label>
    <input type="text" name="user-name" value="{$user->name}">

    <label>Email</label>
    <input type="text" name="user-email" value="{$user->email}">

    <label>Type</label>
    <input type="text" name="user-type" value="{$user->type}">

    <label>Classes</label>
    <input type="text" name="user-classes" value="{$classes}">

    <input type="submit" value="Save Changes">
  </form>
HTML;
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
    h2 { margin-bottom: 1rem; }
    form { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
    label { display: block; font-weight: 600; margin-top: 1rem; margin-bottom: 0.3rem; font-size: 0.9rem; }
    input[type="text"] {
      width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ccc;
      border-radius: 4px; font-size: 0.95rem;
    }
    input[type="text"]:focus { outline: none; border-color: #555; }
    input[type="submit"] {
      margin-top: 1.5rem; background: #1a1a1a; color: #fff; border: none;
      padding: 0.6rem 1.5rem; border-radius: 4px; font-size: 0.95rem; cursor: pointer;
    }
    input[type="submit"]:hover { background: #333; }
    .user-list { list-style: none; }
    .user-list li {
      background: #fff; padding: 1rem; margin-bottom: 0.5rem; border-radius: 6px;
      display: flex; justify-content: space-between; align-items: center;
      box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .user-list a { text-decoration: none; color: #222; font-weight: 600; }
    .user-list a:hover { text-decoration: underline; }
    .btn-delete { color: #c00; text-decoration: none; font-size: 0.85rem; }
    .btn-delete:hover { text-decoration: underline; }
    .btn-new {
      display: inline-block; margin-bottom: 1.5rem; padding: 0.5rem 1rem;
      background: #1a1a1a; color: #fff; text-decoration: none; border-radius: 4px;
      font-size: 0.9rem;
    }
    .btn-new:hover { background: #333; }
  </style>
</head>
<body>
  <div class="admin-header">
    <h1>User Editor</h1>
    <a href="../index.php">&larr; Back to Site</a>
  </div>

  <div class="container">
<?php
// DISPLAY LOGIC
$id = $_GET['id'] ?? null;

if($id === 'new') {
  $empty_user = new stdClass();
  $empty_user->name = "";
  $empty_user->email = "";
  $empty_user->type = "";
  $empty_user->classes = [];
  showUserPage($empty_user, true);
} elseif(isset($users[$id])) {
  showUserPage($users[$id]);
} else {
  // show list of users
  echo '<a href="?id=new" class="btn-new">+ Add New User</a>';
  echo '<ul class="user-list">';
  foreach($users as $i => $u) {
    echo '<li>';
    echo '<a href="?id=' . $i . '">' . htmlspecialchars($u->name) . '</a>';
    echo ' <a href="?action=delete&id=' . $i . '" class="btn-delete" onclick="return confirm(\'Delete this user?\')">Delete</a>';
    echo '</li>';
  }
  echo '</ul>';
}
?>
  </div>
</body>
</html>
