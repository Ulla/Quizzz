<!doctype html>
<html>
<head>
<title>Bilduppladdning</title>
</head>
<body>
<h1>Detaljutvisning av bild</h1>




<?php displayImage();?>




</body>

</html>

<?php

function displayImage()
{
	$dbConn = mysqli_connect("localhost", "root", "", "quizzz");
	$questionID = (int) $_GET['id'];
	$sql = "SELECT image, question from questions where questionID = $questionID";
	
	
	$res = mysqli_query($dbConn, $sql);
	if ($row = mysqli_fetch_assoc($res))
		{
			$image = $row['image'];
			$question = $row['question'];
			echo "<a href='bilder/$image'><img src='bilder/500_$image'></a>";
			echo "<p>$question</p>";
			// här kunde vi också haft en länk till en php-sida som hämtade bilden direkt i databasen
			
		}
	else
		{
			echo "<img src='dummy.jpg'>";
		}
	
	
	
}//end function

?>