PHP-Bouncer - An Open Source Access Control Library
===================================================

PHP-Bouncer is a php library focused on providing flexible access control in situations where strictly hierarchical access control may not be an option.

Introduction
------------
One of the goals of PHP-Bouncer is to make sure that configuration is as easy as possible. Using these instructions you should be able to be up and running quickly.

Basic Instructions
------------------
### Instantiate the Bouncer Object
```php
$bouncer = new Bouncer();
```

### Add Some Roles
```php
// Add a role     Name,      Array of pages role provides
$bouncer->addRole("Public", array("index.php", "about.php"));
// Add a role          Name,              Array of pages role provides
$bouncer->addRole("Registered User", array("myaccount.php", "editaccount.php", "viewusers.php"));
// Add a role          Name,   Array of pages role provides       List of pages that are overridden by other pages
$bouncer->addRole("Admin", array("stats.php", "manageusers.php"), array("viewusers.php" => "manageusers.php"));
```
### Create Our Users
```php
// Here we add some users. The user class here extends the BouncerUser class, so it can still do whatever you
// would normally create a user class to do..
$user1 = new User();
$user2 = new User();
$user3 = new User();
```
### Add Our Users to Roles
```php
$user1->addRole("Public");
$user2->addRole("Registered User");
$user3->addRole("Admin");
```
### Check Access on Various Pages
```php
$bouncer->verifyAccess($user1->getRoles(), "index.php");     // True!
$bouncer->verifyAccess($user1->getRoles(), "viewusers.php"); // False! User 1 does not have access to this page.

$bouncer->verifyAccess($user2->getRoles(), "index.php");     // True!
$bouncer->verifyAccess($user2->getRoles(), "viewusers.php"); // True!

$bouncer->verifyAccess($user3->getRoles(), "index.php");     // True!
$bouncer->verifyAccess($user3->getRoles(), "viewusers.php"); // False! As an Admin, viewusers.php has been replaced
                                                             // with manageusers.php
```