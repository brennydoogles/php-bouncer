-- create schema if not exists Bouncer; -- Uncomment these lines if you are not adding these tables to an existing DB.
-- use Bouncer;

CREATE TABLE BouncerPageOverrides (
  OverrideID int(11) NOT NULL AUTO_INCREMENT,
  OverridingPage varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  OverriddenPage varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  RoleID int(11) NOT NULL,
  PRIMARY KEY (OverrideID),
  KEY pageOverrideRole (RoleID),
  CONSTRAINT pageOverrideRole FOREIGN KEY (RoleID) REFERENCES BouncerRoles (RoleID) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE BouncerRoleContainsPage (
  RelID int(11) NOT NULL AUTO_INCREMENT,
  RoleID int(11) NOT NULL,
  PageName varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (RelID),
  KEY ContainsRole (RoleID),
  CONSTRAINT ContainsRole FOREIGN KEY (RoleID) REFERENCES BouncerRoles (RoleID) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE BouncerRoles (
  RoleID int(11) NOT NULL AUTO_INCREMENT,
  RoleName varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (RoleID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;