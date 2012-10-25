<!doctype html>
<html>
<head>
<title>Bildutvisning</title>
</head>
<body>
<h1><ul>Hur bra är du?</ul></h1>





<?php createGallery();?>




</body>

</html>

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
			<a href='imageDetail.php?id=$id'><img src='bilder/thumb_$image'></a>
			$question
			
			<br>";

		}
	
	
	
}//end function
?>


<h1>Svara på frågorna</h1>
<form action="result.php" method="post">

<b>Fråga 1</b><br>
<input type="radio" name="q1" value="1"> JA <br>
<input type="radio" name="q1" value="2"> Nej <br>

<b>Fråga 2</b><br>
<input type="radio" name="q2" value="1"> Ja <br>
<input type="radio" name="q2" value="2"> Nej <br>

<b>Fråga 3</b><br>
<input type="radio" name="q3" value="1"> Ja <br>
<input type="radio" name="q3" value="2"> Nej <br>

<br>
<input type="submit" value="Hur bra är du?">
</form>











<?php







function saveToDB($questionID)
{
	$dbConn = mysqli_connect("localhost", "root", "", "quizzz");
	
	//hämta orginalbilden
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
	//simpel check - om det är en bild så har den en höjd
	$check = getimagesize($file);
	
	return $check;

}

function generateImage($tempfile, $thumbHeight, $watermark, $namePrefix)
 {
	
	//Hr kunde man kontrollerat om det är en JPG eller png och använrt imagecreatefromjpeg / imagecreatefrompng
 
	$image = imagecreatefromjpeg($tempfile);
	$orgWidth = imagesx($image);
	$orgHeight = imagesy($image);
	//Här kunde man kollat så att den uppladdade filen inte förstoras beroende på den önskade storleken
	
	
	
	$thumbWidth = ceil( ($orgWidth / $orgHeight) * $thumbHeight);
	$thumb = imagecreatetruecolor ($thumbWidth, $thumbHeight);
	imagecopyresampled($thumb, $image,0,0, 0,0,$thumbWidth,$thumbHeight, $orgWidth,$orgHeight);
	$thumbname = "bilder/" .$namePrefix. $_FILES['filnamn']['name'];
	imagejpeg($thumb, $thumbname, 60);
	imagedestroy($thumb);
	imagedestroy($image);
 
 }



?>