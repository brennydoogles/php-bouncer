<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Brendon Dugan <wishingforayer@gmail.com>
 * Date: 7/9/12
 * Time: 5:28 PM
 */
	include("../../src/Bouncer.class.php");
	include("User.class.php");
	$bouncer = new Bouncer();
// Add a role     Name,      Array of pages role provides
	$bouncer->addRole("Public", array("index.php", "about.php"));
// Add a role          Name,              Array of pages role provides
	$bouncer->addRole("Registered User", array("myaccount.php", "editaccount.php", "viewusers.php"));
// Add a role          Name,   Array of pages role provides       List of pages that are overridden by other pages
	$bouncer->addRole("Admin", array("stats.php", "manageusers.php"), array("viewusers.php" => "manageusers.php"));

// Here we add some users. The user class here extends the BouncerUser class, so it can still do whatever you
// would normally create a user class to do..
	$user1 = new User();
	$user2 = new User();
	$user3 = new User();

	$user1->addRole("Public");
	$user2->addRole("Registered User");
	$user3->addRole("Admin");