<?php
  //check the session data
  ini_set("session.cookie_httponly", 1);
  session_start();

  //checking to see if the session is still happening or the user is logged out
  $loggedIn = true;
  if(!isset($_SESSION["token"])){
    $loggedIn = false;
  }

?>

<!DOCTYPE html>
<html>
<head>
  <title>Calendar</title>
  <link rel="stylesheet" type="text/css" href="mystyleCal.css">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" type="text/javascript"></script>
</head>

<body>

  <!--referenced w3schools.com for html code-->
  <div class="monthView" id="monthView">
    <h1>
      <button id='prevMonth'>&#10094;</button> <!--back button-->
      <label id='monthTitle'></label>
      <button id='nextMonth'>&#10095;</button> <!--next button-->
    </h1>

    <table class="calendarView" id="calendarView"></table>
  </div>

  <div id = "formView">

<!--Two different UIs for logged in and logged out users -->
<?php if(!$loggedIn){ ?>

    <!--USER LOGIN FORM and later USER LOGOUT BUTTON-->
  <form action="login.php" method="POST">
    <Label id="LH">Login Here: </Label>
      <input type="text" name="user" id="user" placeholder="Username" required/>
      <input type="password" name="pass" id="pass" placeholder="Password" required/>
      <button id="login">Login</button> <br><br>
  </form>

  <?php
    if(isset($_GET["error"])){
      $error = $_GET["error"];
      if($error && $error=="3"){
        echo "Oh no. Please re-enter your username and password. Type carefully!";
        echo "<br>";
        echo "<br>";
      }
    }
  ?>

   <!--ADD USER FORM and later ADD EVENT FORM -->
   <form action="registerUser.php"  method="POST">
    <Label id="CA">Create Account: </Label>
      <input type="text" name="user" id="user" placeholder="New Username" required/>
      <input type="password" name="pass" id="pass" placeholder="Password" required/>

    <button id="addUser">Create</button> <br><br>
  </form>
  <br>
  <br>
  <!--ERROR NOTIFICATION-->
  <?php
    if(isset($_GET["error"])){
      $error = $_GET["error"];
      if($error && $error=="1"){
        echo "Sorry! That username already exists.";
      }
      else if($error && $error=="2"){
        echo "Oops! We couldn't make a new account.";
      }
    }
  ?>

<?php } else{ ?>
  <br>
  <label>Add Event: </label>
    Event Title:
      <input type="text" name="title" id="title" required/>
    Date of Event:
      <input type="datetime-local" name="eventDate" id="eventDate" required/>

    <select id="eventType" required>
      <option value="soc">Social</option>
      <option value="sch">School</option>
      <option value="fam">Family</option>
      <option value="clu">Clubs</option>
      <option value="bus">Business</option>
      <option value="spo">Sports</option>
      <option value="oth">Other</option>
    </select>

    <button id="addEvent">Add Event</button>
  <br>
  <a href="logout_cal.php"> Logout </a>

<?php } ?>

  <script src="ajax.js"></script>
  <script src="provided.js"></script>
  <script src="calendar.js"></script>

  </div>
</body>

</html>
