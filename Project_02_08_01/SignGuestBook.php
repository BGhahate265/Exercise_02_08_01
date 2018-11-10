<!DOCTYPE html>
<html>
<head>
    <title>Sign Guest Book</title>
    <meta charset="UTF-8">
    <meta src="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>
<body>
<h1>Sign Guest Book</h1>
<?php
    //Function to connect to database multiple times
    function connectToDB($hostName, $userName, $password) {
        $DBConnect = mysqli_connect($hostName, $userName, $password);
        if (!$DBConnect) {
            echo "<p>Connection error: " . mysqli_connect_error() . "</p>\n";
        }
        return $DBConnect;
    }
    function selectDB($DBConnect, $DBName) {
        $success = mysqli_select_db($DBConnect, $DBName);
        if ($success) {
//            echo "<p>Successfully selected the \"$DBName\"database.</p>\n"; Debugging purposes
        }
        else {
            //Debugging purposes
//            echo "<p>Could not select the \"$DBName\"database: " . mysqli_error($DBConnect) . ", creating it.</p>\n"; 
            $sql = "CREATE DATABASE $DBName";
            if (mysqli_query($DBConnect, $sql)) {
//                echo "<p>Successfully created the \"$DBName\"database.</p>\n"; Debugging purposes
                $success = mysqli_select_db($DBConnect, $DBName);
                if ($success) {
//                    echo "<p>Successfully selected the \"$DBName\"database.</p>\n"; Debugging purposes
                }
            }
            else {
//                echo "<p>Could not create the \"$DBName\"database: " . mysqli_error($DBConnect) . "</p>\n"; Debugging purposes
            }
        }
        return $success;
    }
    //Check if table exists, if not, create it
    function createTable($DBConnect, $tablename) {
        $success = false;
        $sql = "SHOW TABLES LIKE '$tablename'";
        $result = mysqli_query($DBConnect, $sql);
        if (mysqli_num_rows($result) === 0) {
//            echo "The <strong>$tablename</strong> table does not exist, creating table.<br>\n";Debugging purposes
            $sql = "CREATE TABLE $tablename (countID SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY, lastName VARCHAR (40), firstName VARCHAR(40))";
            $result = mysqli_query($DBConnect, $sql);
            if ($result === false) {
                $success = false;
                //Debugging purposes
//                echo "<p>Unable to create the $tablename table</p>";
//                echo "Error code " . mysqli_errno($DBConnect) . ":" . mysqli_error($DBConnect) . "</p>";
            }
            else {
                $success = true;
//                echo "<p>Successfully created the $tablename table.</p>"; Debugging purposes
            }
        }
        else {
            $success = true;
//            echo "The $tablename table already exists.<br>\n"; Debugging purposes
        }
        return $success;
    }
    $hostName = "localhost";
    $userName = "adminer";
    $password = "Earth-quite-70";
    $DBName = "guestbook";
    $tablename = "visitors";
    $firstName = "";
    $lastName = "";
    $formErrorCount = 0;
    //Validation Tests
    if (isset($_POST['submit'])) {
        $firstName = stripslashes($_POST['firstName']);
        $firstName = trim($firstName);
        $lastName = stripslashes($_POST['lastName']);
        $lastName = trim($lastName);
        if (empty($firstName) || empty($lastName)) {
            echo "<p>You must enter your first and last <strong>name</strong>.</p>\n";
            ++$formErrorCount;
        }
        //Call to DB connection function of form has no errors
        if ($formErrorCount === 0) {
            $DBConnect = connectToDB($hostName, $userName, $password);
            //Call selectDB function
            if ($DBConnect) {
                if (selectDB($DBConnect, $DBName)) {
                    if (createTable($DBConnect, $tablename)) {
//                        echo "<p>Connection successful!</p>\n"; Debugging purposes
                        $sql = "INSERT INTO $tablename VALUES(NULL, '$lastName', '$firstName')";
                        $result = mysqli_query($DBConnect, $sql);
                        if ($result === false) {
                            //Debugging purposes
//                            echo "<p>Unable to execute the query.</p>";
//                            echo "<p>Error code" . mysqli_errno($DBConnect) . ":" . mysqli_error($DBConnect) . "</p>";
                        }
                        else {
                            echo "<h3>Thank you for signing our guest book!</h3>";
                            $firstName = "";
                            $lastName = "";
                        }
                    }
                }
                mysqli_close($DBConnect);
            }
        }
    }
?>
<form action="SignGuestBook.php" method="post">
    <p><strong>First Name: </strong><br>
    <input type="text" name="firstName" value="<?php echo $firstName;?>"
    </p>
    <p><strong>Last Name: </strong><br>
    <input type="text" name="lastName" value="<?php echo $lastName;?>"
    </p>
    <p><input type="submit" name="submit" value="Submit"></p>
</form>
<?php
$DBConnect = connectToDB($hostName, $userName, $password);
if ($DBConnect) {
    if (selectDB($DBConnect, $DBName)) {
        if (createTable($DBConnect, $tablename)) {
//            echo "<p>Connection successful!</p>\n"; Debugging purposes
            echo "<h2>Visitors Log</h2>";
            $sql = "SELECT * FROM $tablename";
            $result = mysqli_query($DBConnect, $sql);
            if (mysqli_num_rows($result) == 0) {
//                echo "<p>There are no entries in the quest book!</p>"; Debugging purposes
            }
            else {
                echo "<table width='60%' border='1'>";
                echo "<tr>";
                echo "<th>Visitor</th>";
                echo "<th>First Name</th>";
                echo "<th>Last Name</th>";
                echo "</tr>";
                while ($row = mysqli_fetch_row($result)) {
                    echo "<tr>";
                    echo "<td width='10%' style='text-align:center'>$row[0]</td>";
                    echo "<td>$row[1]</td>";
                    echo "<td>$row[2]</td>";
                    echo "</tr>";
                }
                echo "</table>";
                mysqli_free_result($result);
            }
        }
    }
    mysqli_close($DBConnect);
}
?>

</body>
</html>
