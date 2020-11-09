<?php include("safeCrypto.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title></title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="nav.css" rel="stylesheet" />
  <link href="form.css" rel="stylesheet" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="strengthbar.js">

  </script>

</head>

<body>
  <?php
  session_start();
  function show_nav()
  {
    echo ' <nav id="navBar">
    <ul>

      <li><a id="nav_list2" href="listpassword.php">List Password</a></li>
      <li><a id="nav_list2" href="signOut.php">Sign Out</a></li>
      <li><a id="nav_list2" href="report.html">Report</a></li>

    </ul>
  </nav>';
  }
  function show_form()
  {
    echo ' <section id="addForm">
        <table>
          <form method="post" action="addpassword.php">
            <tr>
              <td>Title:</td>
              <td><input type="text" name="title" required/></td>
            </tr>
            <tr>
              <td>UserName:</td>
              <td><input type="text" name="username" /></td>
            </tr>
            <tr>
              <td>Email:</td>
              <td><input type="email" name="email" /></td>
            </tr>
            <tr>
              <td>Password:</td>
              <td><input id="pass" type="password" name="password" required/></td>
            </tr>
            <tr>
              <td>Confirm Password:</td>
              <td><input type="password" name="cpassword" required/></td>
            </tr>
            <tr>
            <td>Password strength:</td>
            <td ><meter  id="meter" value="10" min="0" max="160" ></meter></td>
            </tr>
            <tr>
              <td>URL:</td>
              <td><input type="text" name="url" required /></td>
            </tr>
            <tr>
              
              <td><input type="submit" /></td>
            </tr>
            
          </form>
        </table>
      </section>';
  }


  function addingPassword($conn, $form)
  {
    $obj = new shiftCipher($form[3], 23);
    $vare = $obj->get_encryted();
    //$vard = $obj->get_decrypted($vare);
    //echo "<br>" . $vare . '<br>' . $vard;
    $quer = 'INSERT INTO password' .
      '(title,username,email,url,passwordHash,passwordsalt)' .
      'values' .
      '("' . $form[0] . '","' . $form[1] . '","' . $form[2] . '","' . $form[5]  . '","' . $vare . '","' . $vare . '232sda' . '")';
    //echo $quer;
    $conn->query($quer);
  }
  if ($_SESSION['id'] === "verified") {
    show_nav();
    show_form();
    $data = [0 => "title", 1 => "username", 2 => "email", 3 => "password", 4 => "cpassword", 5 => "url"];
    $form = array();
    for ($i = 0; $i < count($data); $i++) {
      if (isset($_POST[$data[$i]])) {
        array_push($form, $_POST[$data[$i]]);
      } else {
        die("form not filled");
      }
    }
    if (!($form[3] === $form[4])) {
      die("Password Does not Match");
    } else {
      $conn = new mysqli("localhost", "root", "");
      if (!$conn) {
        die("connection can't established with server " . mysqli_connect_error());
      }
      mysqli_select_db($conn, 'PasswordSaver');
      $sqlQuery = "CREATE TABLE  password (id int not null Auto_increment, title VARCHAR(20) not null,username varchar(20), email varchar(30), url varchar(200), passwordHash varchar(200) not null, passwordSalt varchar(200) not null, primary key(id))";
      if ($conn->query($sqlQuery) === true) {
        addingPassword($conn, $form);
      } else {

        addingPassword($conn, $form);
      }
      echo "<br><label>Password Added</label>";
      $conn->close();
    }
  } else {
    header("location:http://localhost/assignment3/index.php");
  }
  ?>

</body>

</html>