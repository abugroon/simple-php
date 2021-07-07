<?php
#######################################
##
##    CONNECTION TO SERVER
##
#######################################
$db_name = 'testdb';
$con = new PDO("mysql:host=localhost;dbname=$db_name", 'root', '');
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

///////////////////////////////////////
//
//    LOGIN FUNCTION 
//
///////////////////////////////////////

function loginCheck($tablename, $username, $password)
{
  try {
    global $con;
    $query = $con->prepare("SELECT * FROM $tablename WHERE `username` = ? AND `password` = ?");
    $query->execute(array($username, $password));
  } catch (Exception $exc) {
    echo $exc->getTraceAsString();
  }
  $count = $query->rowCount();
  if ($count > 0) {
    $row = $query->fetch();

    $_SESSION['id'] = $row['id'];

    return TRUE;
  } else {
    throw new Exception('<h3 class="alert alert-danger text-center">Error username or password ! ,Try Agin </h3>');
  }

}

///////////////////////////////////////
//
//    GET ALL DATA FROM TABEL
//
///////////////////////////////////////
function getAllData($tablename, $colums = 'id', $orderBy = 'ASC')
{
  global $con;
  $query = $con->prepare("SELECT * FROM $tablename ORDER BY  ?  ?;");
  $query->execute(array($colums, $orderBy));
  $count = $query->rowCount();
  $data = $query->fetchAll();
  return $data;
}

///////////////////////////////////////
//
//    SELECT FROM TABLE BY ID
//
///////////////////////////////////////
function getDatabyID($tablename, $id = NULL)
{
  global $con;

  if ($id == NULL) {
    # code...
    $sql = '';
  } else {
    $sql = 'WHERE `id` =' . $id;
  }
  $query = $con->prepare("SELECT * FROM $tablename $sql ");
  $query->execute();
  $count = $query->rowCount();
  if ($count == 0) {
    throw new Exception("No Row To Show !");
  } else {
    $data = $query->fetchAll();
    return $data;
  }

}

///////////////////////////////////////
//
//    CHECK ITEM IN TABLE 
//
///////////////////////////////////////
function checkItem($select, $tablename, $value)
{
  global $con;
  $query = $con->prepare("SELECT $select FROM $tablename WHERE $select = ?");
  $query->execute(array($value));
  $count = $query->rowCount();
  return $count;
}

///////////////////////////////////////
//
//    REDIRECT TO HOME OR GO BACK 
//
///////////////////////////////////////
function redirectHome($theMsg, $secound = 5, $url = null)
{
  if ($url === null) {
    $url = 'index.php';
    $link = 'HomePage';
  } else {
    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
      $url = $_SERVER['HTTP_REFERER'];
      $link = 'Previous Page';
    } else {
      $url = 'index.php';
      $link = 'HomePage';
    }
  }
  echo $theMsg;
  echo "<div class='alert alert-info text-center'> You Will Be Redirected To $link After $secound Seconds.</div>";
  header("refresh:$secound;url=$url");


}

///////////////////////////////////////
//
//    REDIRECT TO LINK
//
///////////////////////////////////////
function redirectToLink($url, $secound = 3)
{
  echo "<div class='alert alert-info'> You Will Be Redirected To $url After $secound Seconds.</div>";
  header("refresh:$secound;url=$url");


}

///////////////////////////////////////
//
//    COUNT ITEM
//
///////////////////////////////////////
function countItem($item, $tablename)
{
  global $con;
  $query = $con->prepare("SELECT COUNT('$item') FROM $tablename");
  $query->execute();
  $count = $query->fetchColumn();
  return $count;
}

///////////////////////////////////////
//
//    ORDER ITEM
//
///////////////////////////////////////
function orderBy($select, $table, $ORDER, $limit = '5')
{
  global $con;
  $query = $con->prepare("SELECT $select FROM $table ORDER BY $ORDER DESC LIMIT $limit;");
  $query->execute();
  $data = $query->fetchAll();
  return $data;
}

function GetBywhere($select, $table, $where, $value)
{
  global $con;
  $query = $con->prepare("SELECT $select FROM $table WHERE $where = ?");
  $query->execute(array($value));
  $data = $query->fetchAll();
  return $data;
}

function varDump($data)
{
  echo '<pre>';
  var_dump($data);
  echo '</pre>';
}

///////////////////////////////////////
//
//    INSERT INTO TABLE
//
///////////////////////////////////////
function insertInto($tablename, $data)
{
  if (is_array($data)) {
    global $con;
    foreach ($data as $key => $value) {
      $keys[] = $key;
      $values[] = $value;
    }
    $varkey = implode($keys, ',');
    $varValues = " '" . implode($values, "','") . "'";
    $query = $con->prepare("INSERT INTO $tablename ($varkey) VALUES ($varValues);");
    $query->execute();
    $count = $query->rowCount();
    if ($count == 1) {
      return true;
    } else {
      throw new Exception("can not excite query");
    }
  } else {
    throw new Exception('Error Data must by in Array');
  }
}

///////////////////////////////////////
//
//    UPDATE DATA BY ID
//
///////////////////////////////////////
function editbyId($tablename, $id, $data)
{
  if (is_array($data)) {
    $id = intval($id);
    global $con;
    $query = "UPDATE `$tablename` SET ";
    foreach ($data as $key => $value) {
      $query .= "`" . $key . "` = '" . $value . "', ";
    }
    $pat = '+-0*';
    $query .= $pat;
    $query = str_replace(", " . $pat, " ", $query);
    $query .= "WHERE `id` = '$id';";
    $update = $con->prepare($query);
    $update->execute();
    $count = $update->rowCount();
    if ($count == 1) {
      return TRUE;
    } else {
      throw new Exception("No update Done .");
    }
  } else {
    throw new Exception('Error Data must by in Array');
  }
}

///////////////////////////////////////
//
//    TO DELETE ROW FROM TABLE BY ID
//
///////////////////////////////////////
function deletebyId($tablename, $id)
{

  global $con;
  $delete = $con->prepare("DELETE FROM $tablename  WHERE `id` = $id ");
  $delete->execute();
  $count = $delete->rowCount();
  if ($count == 1) {
    return TRUE;
  } else {
    throw new Exception("can not excite query");
    return FALSE;
  }
}

///////////////////////////////////////
//
//    TO checkPermissions of users
//
///////////////////////////////////////
function checkPermissions($tablename, $username)
{
  global $con;


  $query = $con->prepare("SELECT * FROM $tablename WHERE `username` = ? AND `type` = 1;");
  $query->execute(array($username));
  $count = $query->rowCount();

  if ($count == 1) {
    return TRUE;
  } else {
    throw new Exception("<h3 class='alert alert-danger text-center'> Sorry You dont have Permissions!</h3>");
  }
}

?>
