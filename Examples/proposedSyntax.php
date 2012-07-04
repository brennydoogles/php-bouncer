<?php
include("../src/Bouncer.class.php");
include("User.class.php");
$bouncer = new Bouncer();
// Add a role     Name,      Array of pages role provides
$bouncer->addRole("Public", array("index.php", "about.php"));
// Add a role          Name,              Array of pages role provides                          Array of encompassed roles
$bouncer->addRole("Registered User", array("myaccount.php", "editaccount.php", "viewusers.php"), array("Public"));
// Add a role          Name,   Array of pages role provides       Array of encompassed roles         List of pages that are replaced by other pages
$bouncer->addRole("Admin", array("stats.php", "manageusers.php"), array("Public", "Registered User"), array("viewusers.php" => "manageusers.php"));

// Here we add some users. The user class here extends the BouncerUser class, so it can still do whatever you
// would normally create a user class to do..
$user1 = new User();
$user2 = new User();
$user3 = new User();

$user1->addRole("Public");
$user2->addRole("Registered User");
$user3->addRole("Admin");

$bouncer->verifyAccess($user1->getRoles(), "index.php"); // True!
$bouncer->verifyAccess($user1->getRoles(), "viewusers.php"); // False! User 1 does not have access to this page.

$bouncer->verifyAccess($user2->getRoles(), "index.php"); // True!
$bouncer->verifyAccess($user2->getRoles(), "viewusers.php"); // True!

$bouncer->verifyAccess($user3->getRoles(), "index.php"); // True!
$bouncer->verifyAccess($user3->getRoles(), "viewusers.php"); // False! As an Admin, viewusers.php has been replaced
// with manageusers.php
?>