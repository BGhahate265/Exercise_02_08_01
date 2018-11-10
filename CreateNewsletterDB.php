<!DOCTYPE html>
<html>

<head>
    <title>Create Newsletter DB</title>
    <meta charset="UTF-8">
    <meta src="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>
<body>
<h2>Create Newsletter DB</h2>
<?php
$hostName = "localhost";
$userName = "adminer";
$password = "Earth-quite-70";
$DBName = "newsletter2";
$DBConnect = mysqli_connect($hostName, $userName, $password);
if (!$DBConnect) {
    echo "<p>Connect failed.</p>";
}
else {
    $sql = "CREATE DATABASE $DBName";
    if (mysqli_query($DBConnect, $sql)) {
        echo "<p>Successfully created the \"$DBName\"database.</p>\n";
    }
    else {
        echo "<p>Could not create the \"$DBName\"database: " . mysqli_error($DBConnect) . "</p>\n"; 
    }
    mysqli_close($DBConnect);
}
?>

</body>
</html>
