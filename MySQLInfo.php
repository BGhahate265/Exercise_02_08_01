<!DOCTYPE html>
<html>

<head>
    <title>MySQL</title>
    <meta charset="UTF-8">
    <meta src="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>
<body>
<h2>MySQL Database Server Information</h2>
<?php
$hostName = "localhost";
$userName = "adminer";
$password = "Earth-quite-70";
$DBConnect = mysqli_connect($hostName, $userName, $password);
if (!$DBConnect) {
    echo "<p>Connect failed.</p>";
}
else {
    echo "<p>Connect successful.</p>";
    mysqli_close($DBConnect);
}
?>

</body>
</html>
