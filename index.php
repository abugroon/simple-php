<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="bootstrap.css">
  <title>Document</title>
</head>
<body>
<div class="container mt-5">

  <?php
  require('function.php');

  $table_name = 'users';

  $message = false;
  if (isset($_POST['submit'])) {

    $data['name'] = $_POST['name'];
    $data['email'] = $_POST['email'];
    // for test and check form data
    //varDump($data);

    if ($_POST['submit'] == "insert") {
      // for save data in db pass table name and data
      $insert = insertInto($table_name, $data);
      if ($insert) {
        $message = 'Insert success';
      }
    }


    if ($_POST['submit'] == "update") {
      //for update must send user id
      $user_id = $_POST['user_id'];
      // for save data in db pass table name and data
      $update = editbyId($table_name, $user_id, $data);
      if ($update) {
        $message = 'Update success';
      }


    }
  }
  if (isset($_POST['delete']) && $_POST['delete'] == "delete") {

    $delete = deletebyId($table_name, $_POST['user_id']);
    if ($delete) {
      $message = 'Delete success';

    }
  }

  if ($message) {
    echo "
        <div class='alert alert-success'>
            $message
        </div>
      ";
  }
  //select data from table name

  $users = getAllData($table_name);
  //varDump($users);
  $data = '';
  foreach ($users as $user) {
    $data .= "<tr>
              <td>{$user['name']}</td>
              <td>{$user['email']}</td>
              <td>
                  
                <form action='' method='post'>
                <input type='hidden' value='{$user['id']}' name='user_id'>
                  <input type='submit' name='delete' value='delete' class='btn btn-danger'>
                </form>
              </td>
            </tr>";
  }

  ?>

  <!--showing selected data-->
  <div class="row">
    <div class="col-12">
      <table class="table table-striped">
        <thead>
        <tr>
          <th>name</th>
          <th>email</th>
          <th>options</th>
        </tr>
        </thead>
        <tbody>
        <?php echo $data; ?>
        </tbody>
      </table>
    </div>
    <div class="col-12">
      <div class="d-flex justify-content-center">
        <form action="" method="post">
          <label for="name">name</label>
          <input type="text" name="name" required class="form-control">
          <label for="name">email</label>
          <input type="email" name="email" required class="form-control">
          <input type="submit" name="submit" value="insert" class="btn btn-primary mt-2">
        </form>

      </div>
    </div>

  </div>
</div>


</body>
</html>