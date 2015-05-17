
<?php
include("header.php"); 
if(!isset($_SESSION['user_type']))
{
  header("Location: https://web.njit.edu/~mg254/web/login.php");
  exit;
}
/**********
CS 490 Project
Phase 1: Skeleton
Urvish Doshi (Front-end), Michael Gonzalez (Middle-end), Muhammad Faheem Sultan (Back-end)
**********/

DEFINE ('DB_USER', 'mg254');
DEFINE ('DB_PASSWORD', 'lineage82');
DEFINE ('DB_HOST', 'sql2.njit.edu');
DEFINE ('DB_NAME', 'mg254');

$qtxt = $_POST["qtxt"];
$q_tests = $_POST["q_testvals"];
$qans = $_POST["qans"];

if(!empty($qtxt)) // this file was called from POSTed form
{
  // Make the connection:
  $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $safe_qtxt = mysqli_real_escape_string($mysqli, $qtxt); // prevent injection
  $safe_q_tests = mysqli_real_escape_string($mysqli, $q_tests); // prevent injection
  $safe_qans = mysqli_real_escape_string($mysqli, $qans); // prevent injection
  $query = "INSERT INTO questions (qtxt, qans, qarg) VALUES ('".$safe_qtxt."', '".$safe_qans."', '".$safe_q_tests."');";
  $result = mysqli_query($mysqli, $query);
  //echo $query;
  if($mysqli->connect_errno) echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  else echo "Question added.";
  mysqli_close($mysqli);
}
?>

<html lang="en-US">
<head>

<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script>
$(document).ready(function(){
    $("button").click(function(){
        $("p").slideToggle();
    });
});
</script>

	<meta charset="utf-8">
	<link href="style.css" rel="stylesheet" type="text/css">

	<title>Create Question</title>
	<!--[if lt IE 9]>
		<script src="html5.js"></script>
	<![endif]-->

</head>

<body>

<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
  <textarea maxlength="500" name="qtxt" id="" placeholder="Question description"></textarea>
  
  <textarea maxlength="500" name="q_testvals" id="hide" placeholder="Question test values (comma-seperated)"></textarea>  

  <textarea maxlength="500" name="qans" id="" placeholder="Question expected output (comma-seperated)"></textarea>

  <x>Please select the difficulty level of the Question</x>
  	<select>
    	<option disabled>Select question type</option>
    	<option value="">Op</option>
    	<option  value="">Trivia</option>
    	<option value="">Iterative</option>
 	 </select>
   <div></div>

  <script>
  $( "select" ).change(function () {
      event.preventDefault();
      $( "#hide" ).show();

    var str = "";
    $( "select option:selected" ).each(function() {
      str += $( this ).text();
    });
    if(str == "Trivia") $( "#hide" ).hide();
  });
  </script>

 	<br>
 	<br>
 	<br>
  <input type="submit" value="submit">
</form>
</body>	
</html>
<?php include("footer.php"); ?>