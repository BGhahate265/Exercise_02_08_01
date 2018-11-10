<!DOCTYPE html>
<html>

<head>
    <title>Create Subscribers Table</title>
    <meta charset="UTF-8">
    <meta src="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>
<body>
    <h2>Create Subscribers Table</h2>
<?php
$hostName = "localhost";
$userName = "adminer";
$password = "Earth-quite-70";
$DBName = "newsletter2";
$tableName = "subscribers";
$DBConnect = mysqli_connect($hostName, $userName, $password);
if (!$DBConnect) {
    echo "<p>Connect failed.</p>";
}
else {
    if (mysqli_select_db($DBConnect, $DBName)) {
        echo "<p>Successfully selected the \"$DBName\"database.</p>\n";
        $sql = "SHOW TABLES LIKE '$tableName'";
        $result = mysqli_query($DBConnect, $sql);
        if (mysqli_num_rows($result) == 0) {
            echo "The <strong>$tableName</strong>" . " table does not exist, create it.<br>\n";
            $sql = "CREATE TABLE $tableName" . 
                "(subscribersID SMALLINT NOT NULL" .
                " AUTO_INCREMENT PRIMARY KEY," . 
                " name VARCHAR(80), email VARCHAR(100)," .
                " subscribeDate DATE, confirmedDate DATE)";
            $result = mysqli_query($DBConnect, $sql);
            if (!$result) {
                echo "<p>Unable to create the <strong>" . " $tableName</strong> table.</p>";
                echo "<p>Error code: " . mysqli_errno($DBConnect) . "</p>";
            }
            else {
                echo "<p>Able to create the <strong>" . " $tableName</strong> table.</p>";
            }
        }
        else {
            echo "The <strong>$tableName</strong>" . " table already exists.<br>\n";
        }
    }
    else {
        echo "<p>Could not select the \"$DBName\"database: " . mysqli_error($DBConnect) . "</p>\n"; 
    }
    mysqli_close($DBConnect);
}
?>

</body>
</html>
