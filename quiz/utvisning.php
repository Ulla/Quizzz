<!doctype html>
<html>
<head>
<link href='style.css' rel='stylesheet' type='text/css'>
<title>Quiz</title>
</head>
<body>
<h1><ul>Ta reda p� hur bra du �r!</ul></h1>
<ul><h2>Fr�gor:</h2></ul>





<?php createGallery();?>


<?php

function createGallery()
{
	$dbConn = mysqli_connect("localhost", "root", "", "quizzz");
	if(isset($_POST['question']))
	{
		$question = (int) $_POST['question'];
		$sql = "SELECT image, question from questions where questionID = $question questionID ASC";
	}
	else
	{
		$sql = "SELECT image, question, questionID FROM questions order by questionID ASC Limit 0, 20";
	}
	
	$res = mysqli_query($dbConn, $sql);
	echo"<ul>";

	while ($row = mysqli_fetch_assoc($res))
		{
			$id = $row['questionID'];
			$question = $row['question'];
			$image = $row['image'];
			echo "
			
			$id
			<img src='bilder/thumb_$image'>
			$question
			
			<br>";

		}
	
	
	
}//end function
?>


<h1>Svara p� fr�gorna</h1>
<form action="result.php" method="post">

<b>Fr�ga 1</b><br>
<input type="radio" name="q1" value="1"> JA <br>
<input type="radio" name="q1" value="2"> Nej <br>

<b>Fr�ga 2</b><br>
<input type="radio" name="q2" value="1"> Ja <br>
<input type="radio" name="q2" value="2"> Nej <br>

<b>Fr�ga 3</b><br>
<input type="radio" name="q3" value="1"> Ja <br>
<input type="radio" name="q3" value="2"> Nej <br>

<br>
<input type="submit" value="Hur bra �r du?">
</form>











<?php







function saveToDB($questionID)
{
	$dbConn = mysqli_connect("localhost", "root", "", "quizzz");
	
	//h�mta orginalbilden
	$imgData = file_get_contents($_FILES['filnamn']['tmp_name']);
	$imgData = mysqli_real_escape_string($dbConn, $imgData);
	$answerID = $_POST['answerID'];
	$question = safeInsert($_POST['question'], $dbConn);
	
	
	$sql = "INSERT INTO questions (image, question, answerID) VALUES ('$imgData', '$question', $answerID)";
	
	//echo $sql;
	mysqli_query($dbConn, $sql);
	

}

function safeInsert($string, $conn)
{
	
	$string = mysqli_real_escape_string($conn, $string);
	$string = htmlspecialchars($string);
	return $string;
	
}



function checkImage($file)
{
	//simpel check - om det �r en bild s� har den en h�jd
	$check = getimagesize($file);
	
	return $check;

}

function generateImage($tempfile, $thumbHeight, $watermark, $namePrefix)
 {
 
	$image = imagecreatefromjpeg($tempfile);
	$orgWidth = imagesx($image);
	$orgHeight = imagesy($image);

	
	$thumbWidth = ceil( ($orgWidth / $orgHeight) * $thumbHeight);
	$thumb = imagecreatetruecolor ($thumbWidth, $thumbHeight);
	imagecopyresampled($thumb, $image,0,0, 0,0,$thumbWidth,$thumbHeight, $orgWidth,$orgHeight);
	$thumbname = "bilder/" .$namePrefix. $_FILES['filnamn']['name'];
	imagejpeg($thumb, $thumbname, 60);
	imagedestroy($thumb);
	imagedestroy($image);
 
 }



?>



</body>

</html>