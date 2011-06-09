/* $Id$ */
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DECLARE @db varchar(100);
SET @db = 'w3pw';

CREATE SCHEMA IF NOT EXISTS @db;
USE @db;

-- -----------------------------------------------------
-- Table `@db`.`main`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `main` (
  `version` VARCHAR(25) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `pw` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- Insert data into 'main'
-- USER DO: Change 'secret' to whatever pw you want to use as your main pw.
INSERT INTO `main` VALUES ('1.5.0-rc1', sha1('secret'));


-- -----------------------------------------------------
-- Table `w3pw`.`wallet`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wallet` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT ,
  `itemname` TINYBLOB NOT NULL ,
  `host` TINYBLOB NOT NULL ,
  `login` TINYBLOB NOT NULL ,
  `pw` TINYBLOB NOT NULL ,
  `comment` MEDIUMBLOB NOT NULL ,
  PRIMARY KEY (`ID`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
