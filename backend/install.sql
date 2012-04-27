SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS tr0ub4dor;
USE tr0ub4dor;

-- -----------------------------------------------------
-- Table tr0ub4dor.main
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
-- USER DO: Change 'superdooperexamplepassword' to whatever pw you want to use as your main pw.
INSERT INTO main (version, pw) VALUES ('1.5.0', sha1('superdooperexamplepassword'));


-- -----------------------------------------------------
-- Table tr0ub4dor.wallet
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
