
<?php
//Skapa
if(isset($_POST['add']))
{
	
	$con = mysqli_connect("localhost", "root", "", "quizzz");
	
// $slump = time() . "gubben" . $user;	
// $salt = hash('sha256', $slump );	
	
	
	$username = $_POST['user'];
	$password =  hash('sha256', $username );

//till�ter inga "konstiga" tecken i inmatningen
$hash = mysqli_real_escape_string($con, $password);
$user = mysqli_real_escape_string($con, $username);
$user = htmlspecialchars($user);

$sql = "insert into usertable (username, pass) VALUES('$user', '$password')";

mysqli_query($con, $sql);

}


//Kolla
if(isset($_POST['check']))
{
	
	$con = mysqli_connect("localhost", "root", "", "quizzz");
	$username = $_POST['user'];
	//$password =  hash('sha256', $username );
	//$password = mysqli_real_escape_string($con, $password);
	$user = mysqli_real_escape_string($con, $username);

	$sql = "SELECT pass from userTable WHERE username = '$username'";

	//echo $sql;
	
//skickar fr�gan till databasen
	$res = mysqli_query($con, $sql);

	if ($row = mysqli_fetch_assoc($res))
	{
		$db_pass = $row['pass'];
		echo "<h1>Du �r inloggad <a href='utvisning.php'>G� vidare till Quiset!</h1></a>";
	}
	else
	{
		echo "Felaktigt l�senord skitst�vel. Stick iv�g!";
		die();	
	}
	//det fanns en anv�ndare
	$password = hash('sha256', $_POST['pass']);
	
	if ($password == $db_pass )
		{
			echo "hittade inga poster......";	
		}




}



?><!doctype html>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css'>
<title>Skapa anv�ndare</title>
</head>

<body>

<h1>Skapa anv�ndare</h1>

<form method="post" acion="login.php">

<input type="hidden" name="add" value="true">

<input type="text" name="user">
Anv�ndarnamn<br>
<input type="text" name="pass">
L�senord<br>
<input type="submit">


</form>

<h1>Logga in</h1>
<form method="post" acion="login.php">

<input type="hidden" name="check" value="true">

<input type="text" name="user">
<input type="text" name="pass">

<input type="submit">


</form>


</body>
</html>