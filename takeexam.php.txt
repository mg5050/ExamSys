
<?php
session_start();
if(!isset($_SESSION['user_type'])) // user not logged in
{
  header("Location:  https://web.njit.edu/~mg254/web/login.php");
  exit;
}

//include("header.php"); 
/**********
CS 490 Project
Phase 1: Skeleton
Urvish Doshi (Front-end), Michael Gonzalez (Middle-end), Muhammad Faheem Sultan (Back-end)
**********/

DEFINE ('DB_USER', 'mg254');
DEFINE ('DB_PASSWORD', 'lineage82');
DEFINE ('DB_HOST', 'sql2.njit.edu');
DEFINE ('DB_NAME', 'mg254');

echo $_SESSION["user_name"] . " logged in as type: " . $_SESSION["user_type"] . ".<br>";
echo "Taking exam: ".$_POST['exam_name']."<br>";


$answers = $_POST['answers'];
$user_name = $_SESSION["user_name"];

if(isset($_POST['exam_name'])) // haven't completed exam yet
{
  $_SESSION["exam_name"] = $_POST['exam_name'];
}
$exam_name = $_SESSION["exam_name"]; // completed exam

if(empty($answers)) // test not yet completed, fetch questions
{ 
  // Make the connection:
  $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $query = "SELECT questions.qid,qtxt FROM ".$exam_name.",questions WHERE questions.qid=".$exam_name.".qid "; // get questions
  $result = mysqli_query($mysqli, $query);
  if($mysqli->connect_errno) echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  mysqli_close($mysqli);
  //echo $query;
  if(mysqli_num_rows($result) == 0) echo "No Questions founds.";
}
else // test completed
{
  $table_name = $user_name.'_'.$exam_name;
  $query = "create table ".$table_name."(qid int, usr_qans VARCHAR(500), usr_qscore int); insert into ".$table_name."(qid, usr_qans) VALUES ";
  $i = 0;
  foreach($answers as $row)
  {
      echo "qid: ".$row['qid']."<br />\n";
      echo "usr_qans: ".$row['usr_qans']."<br />\n";
      if($i != 0) $query = $query.",(".$row['qid'].", '".$row['usr_qans']."')";
      else $query = $query."(".$row['qid'].", '".$row['usr_qans']."')";
      $i++;
  }
  $query = $query.";";
  // Make the connection:
  $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $result = mysqli_multi_query($mysqli, $query);
  if($mysqli->connect_errno) echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  mysqli_close($mysqli);
  echo $query;
  // post to grade.php
  /*
  echo "<form action='https://web.njit.edu/~mg254/web/grade.php' method='post' name='frm'>";
  echo "<input type='hidden' name='exam_name' value='".$exam_name."'/><script language='JavaScript'>";
  echo "document.frm.submit(); </script>";
  */
  $file = '../'.$table_name.'.exam';
  touch($file);
  header("Location: https://web.njit.edu/~mg254/web/grade.php");
  exit;
}
?>
<button onclick="goBack()">Go Back</button>
<script>
  function goBack() {
      window.history.back();
  }
</script>

<html lang="en-US">
<head>

	<meta charset="utf-8">
	<link href="style.css" rel="stylesheet" type="text/css">

	<title>Coding Exam</title>
	<!--[if lt IE 9]>
		<script src="html5.js"></script>
	<![endif]-->

</head>

<body>
<form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> method='post'>
  <?php
  $i = 1;
  while($row = mysqli_fetch_assoc($result))
  {
    echo "<br/><br/><label for=''>question ".$i."</label><br/><br/>";
    echo "<label>".$row["qtxt"]."</label>";
    echo "<input type='hidden' name='answers[".$i."][qid]' value=".$row["qid"].">";
    echo "<textarea name='answers[".$i."][usr_qans]' placeholder='def func_name(n):''></textarea>";
    $i++;
  }
  ?>
<input type="submit" value="submit">  
</form>

<script>
var textareas = document.getElementsByTagName('textarea');
var count = textareas.length;
for(var i=0;i<count;i++){
    textareas[i].onkeydown = function(e){
        if(e.keyCode==9 || e.which==9){
            e.preventDefault();
            var s = this.selectionStart;
            this.value = this.value.substring(0,this.selectionStart) + "\t" + this.value.substring(this.selectionEnd);
            this.selectionEnd = s+1; 
        }
    }
}
</script>

</body>	
</html>
<?php include("footer.php"); ?>