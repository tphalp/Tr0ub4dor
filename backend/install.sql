SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `w3pw` ;
USE `w3pw`;

-- -----------------------------------------------------
-- Table `w3pw`.`main`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `w3pw`.`main` (
  `version` VARCHAR(25) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NOT NULL ,
  `pw` VARCHAR(255) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NOT NULL DEFAULT '' )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- Insert data into 'main'
-- USER DO: Change 'secret' to whatever pw you want to use as your main pw.
INSERT INTO `main` VALUES ('1.5.0-rc1', sha1('secret'));


-- -----------------------------------------------------
-- Table `w3pw`.`wallet`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `w3pw`.`wallet` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT ,
  `itemname` TINYBLOB NOT NULL ,
  `host` TINYBLOB NOT NULL ,
  `login` TINYBLOB NOT NULL ,
  `pw` TINYBLOB NOT NULL ,
  `comment` MEDIUMBLOB NOT NULL ,
  PRIMARY KEY (`ID`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


DELIMITER //
//
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_from_wallet`()
BEGIN

        select *
        from wallet;

END//
//
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_wallet_entry`(
in id_in int
)
BEGIN

        select *
        from wallet
        where ID = id_in;

END//
DELIMITER ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
