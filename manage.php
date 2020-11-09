<!DOCTYPE html>
<html lang="en">

<head>
  <title></title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="nav.css" rel="stylesheet" />
  <link href="form.css" rel="stylesheet" />
</head>

<body>
  <?php
  function show_nav()
  {
    echo ' <nav id="navBar">
    <ul>
    <li><a id="nav_list1" href="addpassword.php">Add Password</a></li>
    <li><a id="nav_list2" href="listpassword.php">List Password</a></li>
    <li><a id="nav_list2" href="signOut.php">Sign Out</a></li>
    <li><a id="nav_list2" href="report.html">Report</a></li>
    </ul>
  </nav>';
  }
  session_start();
  if ($_SESSION['id'] === "verified") {
    show_nav();
  } else {
    header("location:http://localhost/assignment3/index.php");
  }
  ?>


</body>

</html>