
<?php
session_start();
?>
<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css">

</head>
<body>
  <header>
    <div class="nav">
      <ul>
        <li class="Login"><a class="active"  href="#">CS490 -
          <?php
          $usr_type = "";
          if($_SESSION["user_type"] == 1) $usr_type = "Instructor";
          else $usr_type = "Student";
          echo $usr_type;
          ?>
          </a>
        </li>
        <?php
        if($_SESSION["user_type"] == 1) // professor
        {
          echo "<li class='form1'><a href='addquestion.php'>add question</a></li>";
          echo "<li class='form2'><a href='makeexam.php'>create exam</a></li>";
          echo "<li class='form2'><a href='release.php'>release grades</a></li>";
        }
        else
        {
          echo "<li class='form3'><a href='chooseexam.php'>take exam</a></li>";
          echo "<li class='form4'><a href='choosegrade.php'>grade</a></li>";
        }
        if(isset($_SESSION['user_type']))
        {
          echo "<li class='form5'><a href='logout.php'>logout</a></li>";
        }
        else
        {
          echo "<li class='form5'><a href='login.php'>login</a></li>";
        }
        ?>
      </ul>
    </div>
  </header>
</body>
</html>