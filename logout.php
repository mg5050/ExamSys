
<?php
session_start();
session_unset(); 
session_destroy();
header("Location:  https://web.njit.edu/~mg254/web/login.php");
?>