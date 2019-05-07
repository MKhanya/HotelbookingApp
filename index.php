<?php
//initialize the session
session_start();
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
     require_once "connect.php";
   
     $sql= "CREATE TABLE IF NOT EXISTS bookings(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        firstname VARCHAR(50),
        surname VARCHAR(50),
        hotel VARCHAR(30),
        checkin VARCHAR(30),
        checkout VARCHAR(30),
        booked INT(4))";

        $conn ->query($sql);
        echo $conn-> error;
    ?>

<form class="form-signin" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"
method="post">
<h1 class="form-signin-heading">Enter your details for booking</h1>
<label for = "firstname">First Name:</label><br>
<input type="text" id="firstname" name="firstname" placeholder="First Name" require><br>
<label for = "firstname">Surname:</label><br>
<input type="text" id="surname" name="surname" placeholder="Surname" require><br>
<label for="checkin">Checkin:</label><br>
<input type="date" id="checkin" name="checkin" min="2019-05-03" max="2020-01-01"><br>
<label for="checkin">Checkout:</label><br>
<input type="date" id="checkout" name="checkout" min="2019-01-01" max="2020-01-01"><br>
<label for="hotel">Hotel</label><br>
<select name="hotel" required>
<option value="Raddison Hotel">Raddison Hotel</option>
<option value="Blue Moon">Blue Moon</option>
<option value="Newtown Hotel">Newtown Hotel</option>
<option value="The Panther ">The Panther</option>
</select><br>

<button type="submit" name="submit">Book Now</button><br>
</form>

<?php

if(isset($_POST['submit'])){
//create a session variable from posted data
    $_SESSION['firstname']=$_POST['firstname'];
    $_SESSION['surname']=$_POST['surname'];
    $_SESSION['hotel']=$_POST['hotel'];
     $_SESSION['checkin']=$_POST['checkin'];
    $_SESSION['checkout']=$_POST['checkout'];
}

//amount of days the user stayed
    $datetime1 = new datetime($_SESSION['checkin']);
    $datetime2 = new datetime($_SESSION['checkout']);
    $interval = $datetime1->diff($datetime2);

    //switch to adjust cost
$daysBooked = $interval->format('%d');
$value;

switch($_POST['hotel']){
case "Raddison Hotel":
$value=$daysBooked * 750;
break;

case "Bluemoon":
$value=$daysBooked * 800;
break;

case "Newtown";
$value=$daysBooked * 500;
break;

case "The Panther";
$value=$daysBooked * 395;
break;

default:
echo 'invalid booking';
}

//display booking info for user
echo "<div class='feedback'> <br> Firstname: ". $_SESSION['firstname'] . "<br>
    Surname: " . $_SESSION['surname'].
    "<br> Checkin: " . $_SESSION['checkin'].
    "<br> Checkout: " . $_SESSION['checkout'].
    "<br> Hotel: " . $_SESSION['hotel'].
    "<br>" . $interval->format('%r
    %a days') . "<br> Total R " . $value . "</div>";

echo "<form class='form-inline' role='form' action".
htmlspecialchars($_SERVER["PHP_SELF"]).
"method='post'><input type='submit' name='confirm'></form>";

if(isset($_POST['confirm'])){
    $stmt = $conn ->prepare("INSERT INTO bookings(firstname,surname,hotel,checkin,checkout)
    VALUES(?, ?, ?, ?, ?)");
    $stmt -> bind_param('sssss',$firstname,$surname,$hotel,$checkin,$checkout);

    //create a session variable from posted data
    $firstname = $_SESSION['firstname'];
    $surname = $_SESSION['surname'];
    $hotel = $_SESSION['hotel'];
    $checkin = $_SESSION['checkin'];
    $checkout = $_SESSION['checkout'];
    $stmt -> execute();
 echo "booking confirmed";
}
?>
</body>
</html>