
<?php
include("header.php");
if(!isset($_SESSION['user_type']))
{
  header("Location: https://web.njit.edu/~mg254/web/login.php");
  exit;
}


echo "grade.php: ".$_POST['exam_name']." for user ".$_SESSION['user_name'].".<br>";
$exam_name = $_POST['exam_name'];
?>

<html lang="en-US">
<head>

	<meta charset="utf-8">
	<link href="style.css" rel="stylesheet" type="text/css">

	<title>Grade</title>
	<!--[if lt IE 9]>
		<script src="html5.js"></script>
	<![endif]-->

</head>
<body>

<div class="log1">Grading Table</div>
<table class="table">
  <tr>
    <th>grade</th>
  </tr>
  <tr>
    <td data-th="Output">
      <code>
      <?php
      DEFINE ('DB_USER', 'mg254');
      DEFINE ('DB_PASSWORD', 'lineage82');
      DEFINE ('DB_HOST', 'sql2.njit.edu');
      DEFINE ('DB_NAME', 'mg254');

      $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      $query = "SELECT * FROM release_ex WHERE exname='".$exam_name."'";
      $result = mysqli_query($mysqli, $query);
      if($mysqli->connect_errno) echo "Failed to connect to MySQL: (".$mysqli->connect_errno . ") ".$mysqli->connect_error;
      mysqli_close($mysqli);
      if(mysqli_num_rows($result) == 0) 
      {
          echo "Scores have not been released for this exam.";
          exit;
      }

      //set POST variables
      $url = 'https://web.njit.edu/~mg254/web/grading.php';
      $fields = array('user_name' => $_SESSION['user_name'], 'exam_name' => $_POST['exam_name']);

      foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
      rtrim($fields_string, '&');

      //open connection
      $ch = curl_init();

      //set the url, number of POST vars, POST data
      curl_setopt($ch,CURLOPT_URL, $url);
      curl_setopt($ch,CURLOPT_POST, count($fields));
      curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

      //execute post
      $result = curl_exec($ch);

      //close connection
      curl_close($ch);

      /*
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, 'https://web.njit.edu/~mg254/web/grading.php');

      curl_setopt($ch, CURLOPT_POST, true);
      //$data = array('exam_name' => $_SESSION['exam_name'], 'user_name' => $_SESSION['user_name']);
      $data = 'exam_name='.$_SESSION['exam_name'].'&user_name='.$_SESSION['user_name'];
      //echo $data."<br>";
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($curl);
      curl_close($curl);
      */
      echo $result;
      ?>
      </code>
      </td>
  </tr>
</table>

</body>
</html>