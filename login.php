<?php
session_start();
require_once 'header.php';

  $loginErrMsg = "Invalid username or password";


  if (isset($_POST['username']) && isset($_POST['password1']) && isset($_POST['password2'])) {
    if (!empty($_POST['username']) && !empty($_POST['password1']) && !empty($_POST['password2'])) {
      require_once 'Connector.php';
    $DBCon = new Connector;

    $UserNameIn = mysqli_real_escape_string($DBCon->connection,trim($_POST['username'])); ;
    $Pass1In = mysqli_real_escape_string($DBCon->connection, trim($_POST['password1']));
    $Pass2In = mysqli_real_escape_string($DBCon->connection, trim($_POST['password2']));

    $sql = "SELECT * FROM `users` WHERE username = '{$UserNameIn}'";
    $res = $DBCon->connection->query($sql);

    $numrows = $res->num_rows;
    $numrows = $res->num_rows;

      while ($numrows) {
        $row = $res->fetch_array();
        $dbUser = $row['username'];
        $dbPass1 = $row['password1'];
        $dbPass2 = $row['password2'];
        echo $dbUser;
        echo $UserNameIn;

        if ((crypt($UserNameIn, $dbUser) == $dbUser) && (crypt($Pass1In, $dbPass1) == $dbPass1)
          &&  crypt($Pass2In, $dbPass2) == $dbPass2 ) {
          
        
          $_SESSION['UserNameIn'] = $UserNameIn;
          $_SESSION['username'] = $dbUser;
          $redirect_page = 'upload_dropdown2.php';
          header('Location: '.$redirect_page);
        }else{
          echo $loginErrMsg;
          break;
        }
      }
     
    }else{
    echo "Please complete all fields";
    }
  }
?>
  <form action="login.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="username" placeholder="Username"><br><br>
    <input type="password" name="password1" placeholder="Password 1"><br><br>
    <input type="password" name="password2" placeholder="Password 2"><br><br>
    <input type="submit" name="submit" value="Log In"><br><br>
  </form>

<?php require_once 'footer.php'; ?>