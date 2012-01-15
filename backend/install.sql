/* $Id$ */
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS w3pw;
USE w3pw;

-- -----------------------------------------------------
-- Table w3pw.main
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS main (
    ID INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    version VARCHAR(25) NOT NULL,
    pw VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (ID)
)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- Insert data into 'main'
-- USER DO: Change 'secret' to whatever pw you want to use as your main pw.
INSERT INTO main VALUES (null, '1.5.0-rc1', sha1('secret'));


-- -----------------------------------------------------
-- Table w3pw.wallet
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS wallet (
  ID INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  itemname TINYBLOB NOT NULL,
  host TINYBLOB NOT NULL,
  login TINYBLOB NOT NULL,
  pw TINYBLOB NOT NULL,
  comment MEDIUMBLOB NOT NULL,
  PRIMARY KEY (ID)
)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
