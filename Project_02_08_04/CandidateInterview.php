<!DOCTYPE html>
<html>
<head>
    <title>Interview Application</title>
    <meta charset="UTF-8">
    <meta src="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
<!--Embedded CSS-->
    <style>
    @import url('https://fonts.googleapis.com/css?family=Raleway');
        #formscss, #tablecss, h1, h2, h3 {
            font-family: 'Raleway', sans-serif;
        }
        #formscss {
            text-align: center;
        }
        #h1format {
            text-align: center;
        }
        #h2format {
            padding-left: 0.8em;
        }
        input[type=submit] {
            cursor: pointer;
            font-family: 'Raleway', sans-serif;
            font-size: 0.9em;
            display: inline-block;
            text-align: center;
            background-color: azure;
        }
        input[type=text] {
            background-color: azure;
        }
        html {
            background-color: #80ced6;
        }
        html body {
            background:#fefbd8;
            margin-left: auto;
            margin-right: auto;
            min-width: 25%;
            max-width: 75%;
        }
    </style>
<!--    End of Embedded CSS-->
</head>
<body>
<h1 id="h1format">Interview Application</h1>
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
            //debug
//            echo "<p>Successfully selected the \"$DBName\"database.</p>\n";
        }
        else {
//            Debugging purposes
            echo "<p>Could not select the \"$DBName\"database: " . mysqli_error($DBConnect) . ", creating it.</p>\n"; 
            $sql = "CREATE DATABASE $DBName";
            if (mysqli_query($DBConnect, $sql)) {
                //debug
//                echo "<p>Successfully created the \"$DBName\"database.</p>\n";
                $success = mysqli_select_db($DBConnect, $DBName);
                if ($success) {
                    //debug
//                    echo "<p>Successfully selected the \"$DBName\"database.</p>\n"; 
                }
            }
            else {
                //debug
//                echo "<p>Could not create the \"$DBName\"database: " . mysqli_error($DBConnect) . "</p>\n";
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
            //debug
//            echo "The <strong>$tablename</strong> table does not exist, creating table.<br>\n";
            $sql = "CREATE TABLE $tablename" . 
                "(candidateID SMALLINT NOT NULL" .
                " AUTO_INCREMENT PRIMARY KEY," . 
                " name VARCHAR(80), interviewerName VARCHAR(80), position VARCHAR(100)," .
                " interviewDate DATE, communication VARCHAR(200)," .
                " appearance VARCHAR(200), computerskills VARCHAR(200)," .
                " bussKnowledge VARCHAR(200), comments VARCHAR(250))";
            $result = mysqli_query($DBConnect, $sql);
            if ($result === false) {
                $success = false;
                //Debugging purposes
//                echo "<p>Unable to create the $tablename table</p>";
//                echo "Error code " . mysqli_errno($DBConnect) . ":" . mysqli_error($DBConnect) . "</p>";
            }
            else {
                $success = true;
                //debug
//                echo "<p>Successfully created the $tablename table.</p>"; 
            }
        }
        else {
            $success = true;
            //debug
//            echo "The $tablename table already exists.<br>\n";
        }
        return $success;
    }
    $hostName = "localhost";
    $userName = "adminer";
    $password = "Earth-quite-70";
    $DBName = "candidatelist";
    $tablename = "candidates";
    $Name = "";
    $interviewerName = "";
    $position = "";
    $communication = "";
    $appearance = "";
    $computer = "";
    $business = "";
    $comments = "";
    $formErrorCount = 0;
    //Validation Tests
    if (isset($_POST['submit'])) {
        $interviewerName = stripslashes($_POST['interviewerName']);
        $interviewerName = trim($_POST['interviewerName']);
        $Name = stripslashes($_POST['canName']);
        $Name = trim($Name);
        $position = stripslashes($_POST['canPosition']);
        $position = trim($position);
        $communication = stripslashes($_POST['canComms']);
        $communication = trim($communication);
        $appearance = stripslashes($_POST['canAppearance']);
        $appearance = trim($appearance);
        $computer = stripslashes($_POST['canComputer']);
        $computer = trim($computer);
        $business = stripslashes($_POST['canBusiness']);
        $business = trim($business);
        $comments = stripslashes($_POST['canComments']);
        if (empty($Name && $interviewerName)) {
            echo "<p>Please enter your name in admitting the application.</p>\n";
            ++$formErrorCount;
        }
        //Call to DB connection function of form has no errors
        if ($formErrorCount === 0) {
            $DBConnect = connectToDB($hostName, $userName, $password);
            //Call selectDB function
            if ($DBConnect) {
                if (selectDB($DBConnect, $DBName)) {
                    if (createTable($DBConnect, $tablename)) {
                        //debug
//                        echo "<p>Connection successful!</p>\n";
                        $interviewerDate = date("Y-m-d");
                        $sql = "INSERT INTO $tablename VALUES(NULL, '$interviewerName', '$Name', '$position', '$interviewerDate', '$communication', '$appearance', '$computer', '$business', '$comments')";
                        $result = mysqli_query($DBConnect, $sql);
                        if ($result === false) {
                            //Debugging purposes
//                            echo "<p>Unable to execute the query.</p>";
//                            echo "<p>Error code" . mysqli_errno($DBConnect) . ":" . mysqli_error($DBConnect) . "</p>";
                        }
                        else {
                            echo "<h3 id='h1format'>Thank you for applying!</h3>";
                            $Name = "";
                            $interviewerName = "";
                            $position = "";
                            $communication = "";
                            $appearance = "";
                            $computer = "";
                            $business = "";
                            $comments = "";
                        }
                    }
                }
                mysqli_close($DBConnect);
            }
        }
    }
?>
<form action="CandidateInterview.php" method="post" id="formscss">
    <p><strong>Interviewer Name: </strong><br>
    <input type="text" name="interviewerName" required size="35" value="<?php echo $interviewerName;?>"
    </p>
    <p><strong>Candidate Name: </strong><br>
    <input type="text" name="canName" required size="35" value="<?php echo $Name;?>"
    </p>
    <p><strong>Position: </strong><br>
    <input type="text" name="canPosition" required size="35" value="<?php echo $position;?>"
    </p>
    <p><strong>Commincation Abilities: </strong><br>
    <input type="text" name="canComms" required size="35" value="<?php echo $communication;?>"
    </p>
    <p><strong>Professional Appearance: </strong><br>
    <input type="text" name="canAppearance" required size="35" value="<?php echo $appearance;?>"
    </p>
    <p><strong>Computer Skills: </strong><br>
    <input type="text" name="canComputer" required size="35" value="<?php echo $computer;?>"
    </p>
    <p><strong>Business Knowledge: </strong><br>
    <input type="text" name="canBusiness" required size="35" value="<?php echo $business;?>"
    </p>
    <p><strong>Comments: </strong><br>
    <input type="text" name="canComments" required size="35" placeholder="Professional Please" value="<?php echo $comments;?>"
    </p>
    <p><input type="submit" name="submit" value="Submit"></p>
</form>
<?php
$DBConnect = connectToDB($hostName, $userName, $password);
if ($DBConnect) {
    if (selectDB($DBConnect, $DBName)) {
        if (createTable($DBConnect, $tablename)) {
            //debug
//            echo "<p>Connection successful!</p>\n";
            echo "<h2 id='h2format'>Candidate Log</h2>";
            $sql = "SELECT * FROM $tablename";
            $result = mysqli_query($DBConnect, $sql);
            if (mysqli_num_rows($result) == 0) {
                //debug
//                echo "<p>There are no entries in the candidate list!</p>";
            }
            else {
                echo "<table width='100%' border='1' padding: '1.5em' id='tablecss'>";
                echo "<tr>";
                echo "<th>Candidate</th>";
                echo "<th>Interviewer Name</th>";
                echo "<th>Candidate Name</th>";
                echo "<th>Position</th>";
                echo "<th>Date of Interview</th>";
                echo "<th>Commincation Abilities</th>";
                echo "<th>Professional Appearance</th>";
                echo "<th>Computer Skills</th>";
                echo "<th>Business Knowledge</th>";
                echo "<th>Interviewer Comments</th>";
                echo "</tr>";
                while ($row = mysqli_fetch_row($result)) {
                    echo "<tr>";
                    echo "<td width='10%' style='text-align:center'>$row[0]</td>";
                    echo "<td>$row[1]</td>";
                    echo "<td>$row[2]</td>";
                    echo "<td>$row[3]</td>";
                    echo "<td>$row[4]</td>";
                    echo "<td>$row[5]</td>";
                    echo "<td>$row[6]</td>";
                    echo "<td>$row[7]</td>";
                    echo "<td>$row[8]</td>";
                    echo "<td>$row[9]</td>";
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
