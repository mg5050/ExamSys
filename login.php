
<?php
include("header.php");

$uname = $_POST["username"];
$pword = $_POST["password"];

if(!empty($uname)) // this file was called from POSTed form
{
	// Make the connection:
	$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$safe_uname = mysqli_real_escape_string($mysqli, $uname); // prevent injection
	$safe_pword = mysqli_real_escape_string($mysqli, $pword); // prevent injection
	$query = "SELECT username, user_type FROM users WHERE username='".$safe_uname."' AND password=MD5('".$safe_pword."');";
	$result = mysqli_query($mysqli, $query);
	echo $query;
	if($mysqli->connect_errno) echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	mysqli_close($mysqli);
}
?>

<html lang="en-US">
<head>
	<meta charset="utf-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<title>Login</title>
	<!--[if lt IE 9]>
		<script src="html5.js"></script>
	<![endif]-->
</head>
<body>
	<div id="login">
        <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
          <fieldset class="clearfix">
            <p><span class="fontawesome-user"></span><input type="text" name="username" value="Username" onBlur="if(this.value == '') this.value = 'Username'" onFocus="if(this.value == 'Username') this.value = ''" required></p> <!-- JS because of IE support; better: placeholder="Username" -->
            <p><span class="fontawesome-lock"></span><input type="password" name="password" value="Password" onBlur="if(this.value == '') this.value = 'Password'" onFocus="if(this.value == 'Password') this.value = ''" required></p> <!-- JS because of IE support; better: placeholder="Password" -->
            <p><input type="submit" value="Sign In"></p>
          </fieldset>
        </form>
	</div>
</body>	
</html>
<?php 
include("footer.php");
if(mysqli_num_rows($result) > 0) # success
{
	$row = mysqli_fetch_assoc($result);
	$_SESSION["user_name"] = $row["username"]; // set user_name session var
	echo $row["username"] . " logged in as " . $row["user_type"] . ".<br>";
	if(strcmp($row["user_type"], "Professor") == 0) # prof type
	{
		$_SESSION["user_type"] = 1;
		header("Location: https://web.njit.edu/~mg254/web/addquestion.php");
		exit;
	}
	else // student logged in
	{
		$_SESSION["user_type"] = 0;
		header("Location:  https://web.njit.edu/~mg254/web/grade.php");
		#exit;
	}
}
else if(!empty($uname)) echo "Login failed.";
?>
