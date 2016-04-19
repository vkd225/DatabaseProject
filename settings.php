<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Settings Page</title>
	</head>
<body>
<h5> Privacy Settings </h5>
<form>
	<table>
		<tr>
			<td><input type="radio" name="Privacy" id="Public" value="Public" > Public </td>
  		</tr>
  		<tr>
  			<td><input type="radio" name="Privacy" id="Friends" value="Friends"> Friends </td>
		</tr>
		<tr>
			<td><input type="radio" name="Privacy" id="FriendsOfFriends" value="FriendsOfFriends" > Friends of Friends </td>
  		</tr>
  			
	</table>

</form>

</body>
</html>