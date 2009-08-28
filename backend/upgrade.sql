/* $Id$ */
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

USE w3pw;
ALTER TABLE `main` CHANGE COLUMN `version` `version` VARCHAR(25) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NOT NULL DEFAULT '';
UPDATE `main` SET version = '1.5.0-rc1';

DELIMITER //

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_from_wallet`()
BEGIN

        select *
        from wallet;

END//

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