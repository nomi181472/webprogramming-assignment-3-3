<?php include("safeCrypto.php"); ?>
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
    session_start();
    function show_nav()
    {
        echo ' <nav id="navBar">
      <ul>
        <li><a id="nav_list1" href="addpassword.php">Add Password</a></li>
        <li><a id="nav_list2" href="signOut.php">Sign Out</a></li>
        <li><a id="nav_list2" href="report.html">Report</a></li>
      </ul>
    </nav>';
    }
    function show_list($result, $showpass)
    {
        $obj = new shiftCipher("", 23);
        echo "<table border=1>";
        echo "<form method='post' action='listpassword.php'>";
        echo "<tr><th colspan='1'>title</th>"

            . "<th colspan='1'>Username</th>"
            . "<th colspan='1'>email</th>"
            . "<th colspan='1'>Password</th>"
            . "<th colspan='1'>URL</th>"
            . "</tr>";
        while ($row = mysqli_fetch_array($result)) {
            $pass = $row['passwordHash'];
            if ($showpass === true) {
                $pass = $obj->get_decrypted($pass, true);
            }

            echo "<tr><td>" . $row['title'] . "</td>"
                . "<td>" . $row['username'] . "</td>"
                . "<td>" . $row['email'] . "</td>"
                . "<td>" . $pass . "</td>"
                . "<td>" . $row['url'] . "</td>"
                . "</tr>";
        }

        echo "</form>";
        echo "</table>";
    }

    function show_buttons()
    {
        echo '<section id="buttons">
        <table>
          <form method="post">
            <tr>
              <td><input type="submit" name="update" value="update" /></td>
              <td><input type="submit" name="delete" value="delete" /></td>
              <td><input type="submit" name="showpass" value="show pass" /></td>
            </tr>
          </form>
        </table>
      </section>';
    }
    function to_delete($result)
    {
        echo "<table border=1>";
        echo "<form method='post' >";
        echo "<tr><th colspan='1'>title</th>"

            . "<th colspan='1'>Username</th>"
            . "<th colspan='1'>email</th>"
            . "<th colspan='1'>Password</th>"
            . "<th colspan='1'>URL</th>"
            . "<th colspan='1'>select</th>"
            . "</tr>";
        while ($row = mysqli_fetch_array($result)) {

            echo "<tr><td>" . $row['title'] . "</td>"
                . "<td>" . $row['username'] . "</td>"
                . "<td>" . $row['email'] . "</td>"
                . "<td>" . $row['passwordHash'] . "</td>"
                . "<td>" . $row['url'] . "</td>"
                . '<td><input type="checkbox" name="id[]" id="color"value="' . $row["id"] . '"></td>'
                . "</tr>";
        }
        echo "<tr><td><input type='submit' name='deleted' value='delete it'></td></tr>";

        echo "</form>";
        echo "</table>";
    }
    function to_update($result)
    {
        echo "<table border=1>";

        echo "<tr><th colspan='1'>title</th>"

            . "<th colspan='1'>Username</th>"
            . "<th colspan='1'>email</th>"
            . "<th colspan='1'>Password</th>"
            . "<th colspan='1'>URL</th>"
            . "<th colspan='1'>select</th>"
            . "</tr>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<form method='post' name='updating' >";
            echo "<tr><td><input type='text' name='utitle[]' value='" . $row['title'] . "'></td>"
                . "<td><input type='text' name='uusername[]' value='" . $row['username'] . "'></td>"
                . "<td><input type='email' name='uemail[]' value='" . $row['email'] . "'></td>"
                . "<td><input type='password' name='upassword[]' value='" . $row['passwordHash'] . "'></td>"
                . "<td><input type='text' name='uurl[]' value='" . $row['url'] . "'></td>"
                . '<td><button class="button" type="submit" name="ids" value="' . $row["id"] . '">update it</button></td>'
                . "</tr>";
            echo "</form>";
        }



        echo "</table>";
    }
    function updatePassword($form, $conn, $id)
    {
        $obj = new shiftCipher($form[3][0], 23);

        $vare = "";

        $result = $conn->query("select passwordHash from password where id=" . $id);
        echo "<pre>";
        if ($obj->get_decrypted(mysqli_fetch_array($result)[0], true) == $obj->get_decrypted($form[3][0], true)) {
            $vare = $form[3][0];
        } else {
            $vare = $obj->get_encryted();
        }


        $quer = 'UPDATE password set ' .
            'title = "' . $form[0][0] . '",username = "' . $form[1][0] . '",email ="' . $form[2][0] . '",url = "' . $form[4][0] . '",passwordHash ="' . $vare . '",passwordSalt="' . $vare . '232sda' . '" where id=' . $id;
        //echo $quer . '<br>';
        $conn->query($quer);


        echo "</pre>";
    }


    if ($_SESSION['id'] === "verified") {
        show_nav();
        $conn = new mysqli("localhost", "root", "");
        if (!$conn) {
            die("connection can't established with server " . mysqli_connect_error());
        }
        mysqli_select_db($conn, 'PasswordSaver');
        $result = $conn->query("select id,title,username,email,url,passwordHash from password");


        show_buttons();
        if (isset($_POST['delete'])) {
            to_delete($result);
        } else if (isset($_POST['update'])) {

            to_update($result);
        } else {

            if (isset($_POST['deleted'])) {
                if (isset($_POST['id'])) {
                    $name = $_POST['id'];

                    foreach ($name as $color) {
                        $conn->query("delete from password where id = " . (int)$color);
                    }
                    header("location:http://localhost/assignment3/listpassword.php");
                }
            }
            $data = [0 => "utitle", 1 => "uusername", 2 => "uemail", 3 => "upassword", 4 => "uurl"];
            $form = array();


            if (isset($_POST["ids"])) {


                for ($i = 0; $i < count($data); $i++) {
                    if (isset($_POST[$data[$i]])) {

                        array_push($form, $_POST[$data[$i]]);
                    } else {
                        die("form not filled");
                    }
                }



                updatePassword($form, $conn, $_POST["ids"]);
                header("location:http://localhost/assignment3/listpassword.php");
            }
            if (isset($_POST["showpass"])) {

                show_list($result, true);
            } else {
                show_list($result, false);
            }
        }
    } else {
        header("location:http://localhost/assignment3/index.php");
    }
    ?>


</body>

</html>