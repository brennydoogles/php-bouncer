<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * User: Brendon Dugan <wishingforayer@gmail.com>
	 * Date: 7/9/12
	 * Time: 5:28 PM
	 */

	include("../../src/Bouncer.class.php");
	include("../../src/User.class.php");
	$bouncer = new Bouncer();
// Add a role     Name,      Array of pages role provides
	$bouncer->addRole("Public", array("index.php", "about.php"));
// Add a role          Name,              Array of pages role provides
	$bouncer->addRole("Registered User", array("myaccount.php", "editaccount.php", "viewusers.php"));
// Add a role          Name,   Array of pages role provides       List of pages that are overridden by other pages
	$bouncer->addRole("Admin", array("stats.php", "manageusers.php"), array("viewusers.php" => "manageusers.php"));

// Here we add some users. The user class here extends the BouncerUser class, so it can still do whatever you
// would normally create a user class to do..
	$publicUser         = new User();
	$registeredUser     = new User();
	$adminUser          = new User();
	$registeredAndAdmin = new User();

	$publicUser->addRole("Public");

	$registeredUser->addRole("Public"); // We add the public group to all users since they need it to see index.php
	$registeredUser->addRole("Registered User");

	$adminUser->addRole("Public"); // We add the public group to all users since they need it to see index.php
	$adminUser->addRole("Admin");

	$registeredAndAdmin->addRole("Public"); // We add the public group to all users since they need it to see index.php
	$registeredAndAdmin->addRole("Registered User");
	$registeredAndAdmin->addRole("Admin");

	//$bouncer->manageAccess($publicUser->getRoles(), substr($_SERVER["PHP_SELF"], 1), "fail.php");
	if($bouncer->verifyAccess($publicUser->getRoles(), substr($_SERVER["PHP_SELF"], 1))){
		echo "We have access";
	}
	else{
		echo "Fail";
	}