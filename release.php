<?php
session_start();
include("header.php"); 
if(!isset($_SESSION['user_type']))
{
  header("Location: https://web.njit.edu/~mg254/web/login.php");
  exit;
}
?>

<html lang="en-US">
<head>

  <meta charset="utf-8">
  <link href="style.css" rel="stylesheet" type="text/css">

  <title>Release Exam</title>
  <!--[if lt IE 9]>
    <script src="html5.js"></script>
  <![endif]-->

</head>
<body>
    <br/><br/>
 
<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
<table class="table">
  <tr>
    <th>Release Exam</th>
  </tr>

<?php

$exam_name = $_POST['exam_name'];

if(!empty($exam_name)) 
{
  $query = "insert into release_ex(exname) VALUES ('".$exam_name."');";
  echo $query;

  $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $result = mysqli_query($mysqli, $query);
  if($mysqli->connect_errno) echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  mysqli_close($mysqli);

  echo "Exam \"".$exam_name."\" released.<br>"; 
}

$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$query = "SELECT * FROM allexams;";
$result = mysqli_query($mysqli, $query);
if($mysqli->connect_errno) echo "Failed to connect to MySQL: (".$mysqli->connect_errno . ") ".$mysqli->connect_error;
mysqli_close($mysqli);
if(mysqli_num_rows($result) == 0) 
{
    echo "No Exams found.";
    exit;
}

while($row = mysqli_fetch_assoc($result))
{
  echo "<tr>";
  echo "<td data-th=''><input type='checkbox' name = 'exam_name' value='".$row['exname']."'/><label>".$row['exname']."</label></td>";
  echo "</tr>";
}
?>

<tr><td><input type='submit' name='formSubmit' value='Submit' /></td></tr>

</table>
</form>

</body>
  
</html>