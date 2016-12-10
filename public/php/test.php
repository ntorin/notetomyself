<?php
	ob_start();
	$serverName = "localhost";
	$dbUsername = "josh";
	$dbPassword = "L.anoire1";
	$database = "note_to_myself";

	// Create connection
	$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $database) 
				or die (mysqli_connect_error());

	if (isset($_POST['submit'])) {
		$notesToUpdate = $_POST['notes'];
		$tbdToUpdate = $_POST['tbd'];
		echo $notesToUpdate . "<br>";
		echo $tbdToUpdate . "<br>";

	} else {
		echo "submit not set";
	}
	

	$query = "SELECT * FROM User
			  WHERE user_name = 'test@gmail.com' ";

	// Execute query
	$result_set = mysqli_query($conn, $query);

	while($row = mysqli_fetch_row($result_set)) {
        $user = $row[1];
        $pass = $row[2];
        $notesText = $row[5];
        $tbdText = $row[6];
    }

	ob_end_flush();
?>

<html>
	<h2><?php echo $user;?></h2><a href="logout.php">Log out</a><br><br>
	<form action="test.php" method="post">
		Notes: <br>
		<textarea rows="40" cols="16" name="notes"><?php echo $notesText;?></textarea><br><br>
		Tbd: <br>
		<textarea rows="40" cols="16" name="tbd"><?php echo $tbdText;?></textarea><br><br>
		<input type="submit" value="Save">
	</form>
</html>
