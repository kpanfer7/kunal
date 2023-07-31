<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ){
    exit ('failed to connect to mysql: ' . mysqli_connect_errno());
  }
 if ( !isset($_POST['username'], $_POST['password'])) {
    exit ('please fill both username and password');
 }

 if ($stmt = $con->prepare('SELECT id,password FROM accounts Where username = ?') ) {
    $stmt->bind_param('s',$_POST['username']);
    $stmt->execute();

    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id,$password);
        $stmt->fetch();
        if ($_POST['password'] === $password) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            echo header('location: home.php');
        } else {
            echo 'incorrect username or password!';
        }

    }
          else {
            echo 'incorrect username or password';
          }

    $stmt->close();
 }
 ?>