<?php
$user_name = $_POST["user_name"];
$exam_name = $_POST['exam_name'];
//$user_name = "ubd2";
//$exam_name = "exam1";

// all parent directories and the file have r-x:
// chmod 755 dir; chmod 755 file,

//$cmd = "../python grade.py ".$user_name.' '.$exam_name."";
$file_name = "../".$user_name . '_' . $exam_name . ".graded";
echo $file_name."<br>";
echo file_get_contents($file_name);
//echo runExternal('env', $code);
//echo $code;
?>