<!DOCTYPE html>
<html>

<head>
    <title>Select Test</title>
    <meta charset="UTF-8">
    <meta src="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>
<body>
<h2>Select Test</h2>
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
    if (mysqli_select_db($DBConnect, $DBName)) {
        echo "<p>Successfully selected the \"$DBName\"database.</p>\n";
    }
    else {
        echo "<p>Could not select the \"$DBName\"database: " . mysqli_error($DBConnect) . "</p>\n"; 
    }
    mysqli_close($DBConnect);
}
?>

</body>
</html>
