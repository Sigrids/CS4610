<?php
 /* mn=PHP var; cn=column#; dir=direction; */ 
$mn = intval(filter_input(INPUT_GET, "mn"));
$cn = intval(filter_input(INPUT_GET, "cn"));
$dir = intval(filter_input(INPUT_GET, "dir"));

$btnArr = array();
$btnArr[] = "Move-Up";
$btnArr[] = "Move-Down";
$btnArr[] = "Edit";
$btnArr[] = "Delete";

$glyphArr = array();
$glyphArr[] = "arrow-up";
$glyphArr[] = "arrow-down";
$glyphArr[] = "edit";
$glyphArr[] = "trash";

$dbhost = "localhost";
$dbuser = "root";
$dbpassword = "";
$dbname = "university";

$con = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

if (!$con) {
    die('Could not connect: ' . mysqli_connect_error());
}

$tblArr = array();
$tblArr[] = "student";
$tblArr[] = "course";
$tblArr[] = "section";
$tblArr[] = "grade_report";
$tblArr[] = "prerequisite";

$table_name = $tblArr[$mn];

$sql = "SHOW COLUMNS FROM $table_name";
$result1 = mysqli_query($con,$sql);

while ($record = mysqli_fetch_array($result1)) {
    $fields[] = $record['0'];
}

$optArr = array();
$optArr[] = "Student";
$optArr[] = "Course";
$optArr[] = "Section";
$optArr[] = "Grade Report";
$optArr[] = "Prerequisite";

$data2dArr = array();

if ($dir == 0) {
    $query = "SELECT * FROM  $table_name ORDER BY $fields[$cn]";
} else {
    $query = "SELECT * FROM  $table_name ORDER BY $fields[$cn] DESC";
}
$testArr = array();
$result2 = mysqli_query($con, $query);

while ($line = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
    $i = 0;
    foreach ($line as $col_value) {
        $data2dArr[$i][] = $col_value;
		$testArr[$i] = $col_value;
        $i++;
    }
}
$insertArr = array();
$x=0;
if($_SERVER["REQUEST_METHOD"] == "POST") {
	for($x=0; $x<count($testArr); $x++){
		print $testArr[$x];
		$insertArr[$x] = test_input($_POST["field$x"]);
		print $insertArr[$x];
	}
}

if(!empty($insertArr)){
	$insert = implode("', '", $insertArr);
	$num = count($insertArr);
	if($num == 5){
		$query="INSERT INTO optArr[2](section_identifier, course_number, ,semester, year, instructor)VALUES('$insert')";
	}
	else if($num = 4){
		if(is_int($insertArr[0] == True)){
			$query="INSERT INTO optArr[0](student_number, name, class, major)VALUES('$insertArr')";
		}
		else{
		$query="INSERT INTO optArr[1](course_number, course_name, credit_hours, department)VALUES('"$insertArr['0']"', '"$insertArr['1']"', '"$insertArr['2']"', '"$insertArr['3']"')";
		}
	}
	else if($num = 3){
		$query="INSERT INTO optArr[1](student_number, section_identifier, grade)VALUES('$insertArr[0]', '$insertArr[1]', '$insertArr[2]', '$insertArr[3]')";
	}
	else if($num = 2){
		$query="INSERT INTO optArr[1](course_number, prerequisite_number )VALUES('$insertArr[0]', '$insertArr[1]')";
	}
	if(mysqli_query($con,$query)){
		$last_id = mysqli_insert_id($con);
		echo "Happy days";
	} else{ printf("error: %s\n", mysqli_error($con));
	die('Error in inserting records'.mysqli_connect_error());
	}
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
	}

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Elaina's Database Project</title>
		<link type="text/css" rel="stylesheet" href="css/style.css"/>
        <script type="text/javascript" src="js/university.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    </head>
    <body>
	<div id="page">
        <table>
            <tr>
                <?php
                for ($i = 0; $i < count($optArr); $i++) {
                    ?>
                    <td style="width: 9em">
                        <?php
                        if ($mn == $i) {
                            ?>
                            <b><?php print $optArr[$i]; ?></b>
                            <?php
                        } else {
                            ?>
                            <a href="index.php?mn=<?php print $i; ?>">
                                <?php print $optArr[$i]; ?>
                            </a>
                            <?php
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
        </table>
        <hr />
        <table>
            <tr>
                <?php
                for ($i = 0; $i < count($fields); $i++) {
                    ?>
                    <th style="width: 8em"><?php print $fields[$i]; ?></th>
                    <?php
                }
                ?>
            </tr>
            <?php
            for ($j = 0; $j < count($data2dArr[0]); $j++) {
                ?>
                <tr>
                    <?php
                    for ($k = 0; $k < count($fields); $k++) {
                        ?>
                        <td><?php print $data2dArr[$k][$j]; 
                    }
                    ?>
					<button type="button" class="elsebtn"onclick=""/>
                    <span class="glyphicon glyphicon-<?php print $glyphArr["2"]; ?>"></span>
                            </button>
					<button type="button" class="elsebtn"onclick=""/>
                    <span class="glyphicon glyphicon-<?php print $glyphArr["3"]; ?>"></span>
                            </button>
						</td>
                </tr>
                <?php
            }
            ?>
			
            <tr>
                <?php
                for ($i = 0; $i < count($fields); $i++) {
                    ?>
                    <td style="width: 2em">
						<button type="button" class="btn-default"onclick="sortCurrentField(<?php print $mn; ?>,<?php print $i; ?>, 0)"/>
                        <span class="glyphicon glyphicon-<?php print $glyphArr["0"]; ?>"></span>
                            </button>
						<button type="button" class="btn-default"onclick="sortCurrentField(<?php print $mn; ?>,<?php print $i; ?>, 1)"/>
                        <span class="glyphicon glyphicon-<?php print $glyphArr["1"]; ?>"></span>
                            </button>
                    </td>
                    <?php
                }
                ?>
            </tr>
			<tr>
				<td>
				<div id="newbtn" style="display: block">
				<button id="btn" type="button" class="btnRow" onclick="changeView()"> New Row</button>
				</div>
                </td>
			</tr>
        </table>
		<hr />
		<div id="guicomp2" style="display: none">
		<table>
		<tr>
                <?php
                for ($i = 0; $i < count($fields); $i++) {
                    ?>
					<td style="width: 9em">
					<form id="form" method="POST" action="">
					<input type="text" id="field<?php print $i; ?>"  name="field<?php print $i; ?>" size="10" value=""/>
					</td>
                        <?php
                    }
                    ?>
                    <td height="40"><button type="submit" class="newRow" onclick="">Update</button></td>
                </tr>
		</table>
		</div>
	</div>
    </body>
</html>
<?php
mysqlI_close($con);
?>
