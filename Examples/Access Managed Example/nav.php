<?php
	/**
	 * Created with JetBrains PhpStorm.
	 * User: Brendon Dugan <wishingforayer@gmail.com>
	 * Date: 7/11/12
	 * Time: 3:13 PM
	 *
	 */
	if(isset($level)){
		echo "You have $level Permissions";
	}
?>
<p>Public Pages:</p>
<ul>
	<li><a href="index.php">Home</a></li>
	<li><a href="about.php">About</a></li>
</ul>
<p>Registered Pages:</p>
<ul>
	<li><a href="editaccount.php">Edit Account</a></li>
	<li><a href="myaccount.php">My Account</a></li>
	<li><a href="viewusers.php">View Users</a></li>
</ul>
<p>Admin Pages:</p>
<ul>
	<li><a href="stats.php">Stats</a></li>
	<li><a href="manageusers.php">Manage Users</a></li>
</ul>