
<?php


	$dbConn = mysqli_connect("localhost", "root", "", "quizzz");

		
		
		//resultat
		$res = mysqli_query($dbConn, "SELECT answerID from questions sort by questionID");
		$answerID = 'answerID';

$score = 0;
if ($_POST['q1'] == '1')
$score++;
if ($_POST['q2'] == '2')
$score++;
if ($_POST['q3'] == '1')
$score++;
?> 

<html>
<head>
<title>resultat</title>
</head>
<body>
<h1>Ditt resultat!</h1>

<?php
echo '<b>Din po�ng blev ' . $score . '/3</b><br><br>';
if ($score < 1)
echo 'Du �r skitd�lig!';
else if ($score < 2)
echo 'Du �r kass!';
else
echo 'Du �r OK';
?> 

</body>
</html>