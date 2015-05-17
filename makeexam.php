
<?php
include("header.php"); 
if(!isset($_SESSION['user_type']))
{
  header("Location: https://web.njit.edu/~mg254/web/login.php");
  exit;
}

$selection = $_POST['sel_qs'];
$exam_name = $_POST['exam_name'];
$points = $_POST['pts'];
$default_score = 5;

$cnt = count($points); 

if(!empty($selection)) 
{
  $cnt = count($selection);
  $query = "create table ".$exam_name."(qid int, qscore int); insert into ".$exam_name."(qid, qscore) VALUES ";
  for($i=0; $i < $cnt; $i++)
  {
    $qid = $selection[$i];
    $qscore = $points[$i];
    echo "points: ".$qscore;
    if($i != 0) $query = $query.",(".$qid.", ".$qscore.")"; // qid starts at 1
    else $query = $query."(".$qid.", ".$qscore.")";
  }
  $query = $query."; insert into allexams(exname) VALUES ('".$exam_name."');";
  echo $query;

  $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $result = mysqli_multi_query($mysqli, $query);
  if($mysqli->connect_errno) echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  mysqli_close($mysqli);

  echo "Exam \"".$exam_name."\" created.<br>"; 
}

// Make the connection:
$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$query = "SELECT * FROM questions;";
$result = mysqli_query($mysqli, $query);
if($mysqli->connect_errno) echo "Failed to connect to MySQL: (".$mysqli->connect_errno . ") ".$mysqli->connect_error;
mysqli_close($mysqli);
if(mysqli_num_rows($result) == 0) 
{
    echo "No Questions found.";
    exit;
}
?>

<html lang="en-US">
<head>

	<meta charset="utf-8">
	<link href="style.css" rel="stylesheet" type="text/css">

	<title>make question</title>
	<!--[if lt IE 9]>
		<script src="html5.js"></script>
	<![endif]-->
</head>
<body>
<br/><br/>

<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
<textarea maxlength="200" name="exam_name" id="message" placeholder="exam name"></textarea>
<br/><br/>

<?php
$i = 1;
while($row = mysqli_fetch_assoc($result))
{
  echo "<input type=\"checkbox\" name=\"sel_qs[]\" value='".$row["qid"]."' />";
  echo "<label for=''>question ".$i."</label><br/>";
  echo "<select name='pts[]'>";
  for($pt = 5; $pt >= 1; $pt--) echo "<option value='".$pt."'>".$pt."pt</option>";
  echo "</select><label>".$row['qtxt']."</label><br/><br/>";
  $i++;
}
?>
<input type='submit' name='formSubmit' value='Submit' />
</form>

</body>	
</html>
<?php include("footer.php"); ?>