
<?php




if (isset ($_FILES['filnamn']))
{
	//om det finns en fil, och den är ok, så sparar vi orginalet,  vi skapar en thumbnail och vi skapar en 
	//mellanstorbild med watermrk på.
	
	if (checkImage($_FILES['filnamn']['tmp_name']) )
	{
		$tempfile =  $_FILES['filnamn']['tmp_name'] ;
		//slumpa fram namnet så att vi inte skriver över en befintlig fil
		$slumpnamn =   substr (  md5(uniqid(rand())) ,0, 5)  ;
		$uniqueName = false;
		while (!$uniqueName)
		{
			if (!file_exists ("bilder/". $slumpnamn. $_FILES['filnamn']['name'] ))
			{
				$uniqueName = true;
				break; 
			}
			else
			{
				$slumpnamn =   substr (  md5(uniqid(rand())) ,0, 5)  ;
			}
		}
		
		//skapa en thumnbail
		generateImage($tempfile, 200, false, "thumb_".$slumpnamn);
		generateImage($tempfile , 500, true, "500_".$slumpnamn);
		$img=  $_FILES['filnamn']['name'];
		saveToDB($slumpnamn.$img);
		
		
		//flytta filen...
		move_uploaded_file($tempfile,"bilder/".$slumpnamn.$img );
		
	}
}


?>


<!doctype html>
<html>
<head>
<link href='style2.css' rel='stylesheet' type='text/css'>
<title>Skapa en Fråga</title>
</head>
<body>
<h1>Skapa fråga</h1>

<form method="post" action="create.php"  enctype="multipart/form-data">
<input type="file" name="filnamn"><br><br>
<textarea name="question">Din Fråga</textarea><br><br>
Rätt svar:<br>
<select name="answerID">
<!--Här kunde vi hämtat id-n automatiskt -->
	<option value="1">Ja</option>
	<option value="2">Nej</option>
</select><br><br>
<input type="submit" value="Skicka">
</form>



</body>

</html>

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

function generateImage($tempfile, $thumbHeight, $namePrefix)
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