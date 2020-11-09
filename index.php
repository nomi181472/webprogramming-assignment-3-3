<!DOCTYPE html>
<html lang="en">

<head>
  <title></title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="nav.css" rel="stylesheet" />
  <link href="form.css" rel="stylesheet" />
</head>

<body>
  <nav id="navBar">
    <ul>

      <li><a id="nav_list2" href="report.html">Report</a></li>
    </ul>
  </nav>

  <?php
  session_start();
  $hostname = "localhost";
  $user = "root";
  $password = "";
  $conn = new mysqli("localhost", "root", "");
  if (!$conn) {
    die("connection can't established with server " . mysqli_connect_error());
  }


  if ($conn->query("Create Database PasswordSaver") === true) {
    mysqli_select_db($conn, 'PasswordSaver');
    mysqli_error($conn);

    $conn->query("CREATE TABLE  admin (id int not null Auto_increment, name VARCHAR(20) not null, passwordHash varchar(200) not null, passwordSalt varchar(200) not null, primary key(id))");
    mysqli_error($conn);


    echo '<section id="adminRegister">
        <table>
          <form method="post" action="index.php">
            <tr>
              <td>Enter Name:</td>
              <td><input type="text" name="adminName" /></td>
            </tr>
            <tr>
              <td>Enter Password:</td>
              <td><input type="password" name="adminPassword1" /></td>
            </tr>
            <tr>
              <td>Confirm Password:</td>
              <td><input type="password" name="adminPassword2" /></td>
            </tr>
            <tr>
              
              <td><input type="submit" name="adminSubmit" /></td>
            
            </tr>
            
          </form>
        </table>
      </section>';

    $name = "";
    $pass = "";
    $conPass = "";
    if (isset($_POST['adminName'])) {
      $name = $_POST['adminName'];
    }
    if (isset($_POST['adminPassword1'])) {
      $pass = $_POST['adminPassword1'];
    }

    if (isset($_POST['adminPassword2'])) {
      $conPass = $_POST['adminPassword2'];
    }
    if ($pass === $conPass) {
      $password = $pass;

      $hash_default_salt = password_hash($password, PASSWORD_DEFAULT);

      $hash_variable_salt = password_hash($password, PASSWORD_DEFAULT, array('cost' => 9));

      if (password_verify($pass, $hash_default_salt)) {
        //echo "" . $name . "<br>" . $pass . "<br>" . $conPass;
        //echo "<br>" . $hash_default_salt . "<br>" . $hash_variable_salt;
        $quer = 'INSERT INTO admin' .
          '(name,passwordHash,passwordsalt)' .
          'values' .
          '("' . $name . '","' . $hash_default_salt . '","' . $hash_variable_salt . '")';
        //echo $quer;
        $conn->query($quer);
        mysqli_error($conn);
        //print_r($conn);

        $result = $conn->query('SELECT * from admin');
        mysqli_error($conn);

        $row = mysqli_fetch_array($result);
        if (password_verify($pass, $row["passwordSalt"])) {
          $_SESSION['id'] = "verified";
          $conn->close();
          header("location:http://localhost/assignment3/manage.php");
        } else {
          echo "password hash creating problem";
        }
      }
    }
  } else {
    echo '<section id="adminLogin">
        <table>
          <form method="post" action="index.php">
            <tr>
              <td>Enter Name:</td>
              <td><input type="text" name="loginAdminName" /></td>
            </tr>
            <tr>
              <td>Master Password:</td>
              <td><input type="password" name="LoginAdminPassword" /></td>
            </tr>
            <tr>
              
              <td><input type="submit" name="loginSubmit" /></td>
            </tr>
            
          </form>
        </table>
      </section>';
    $name = "";
    $pass = "";
    mysqli_select_db($conn, 'PasswordSaver');
    if (isset($_POST['loginAdminName'])) {
      $name = $_POST['loginAdminName'];
    }
    if (isset($_POST['LoginAdminPassword'])) {
      $pass = $_POST['LoginAdminPassword'];
    }
    $result = $conn->query('SELECT * from admin');
    mysqli_error($conn);
    $row = mysqli_fetch_array($result);
    $uname = $row["name"];
    $passH = $row["passwordHash"];
    if (password_verify($pass, $passH) && $uname === $name) {
      $_SESSION['id'] = "verified";
      $conn->close();
      header("location:http://localhost/assignment3/manage.php");
    } else {
      echo "name or password donot match<br>";
    }
  }
  //echo "i am running outside ";
  //print_r($conn);
  //$conn->query("drop database passwordsaver");
  mysqli_error($conn);
  ?>
</body>

</html>