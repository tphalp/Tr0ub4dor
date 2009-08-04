CREATE TABLE `main` (
  `version` varchar(5) character set latin1 collate latin1_general_ci NOT NULL default '',
  `pw` varchar(255) character set latin1 collate latin1_general_ci NOT NULL default ''
) ENGINE=MyISAM;

INSERT INTO `main` VALUES ('1.40', 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4');

CREATE TABLE `wallet` (
  `ID` int(11) NOT NULL auto_increment,
  `itemname` tinyblob NOT NULL,
  `host` tinyblob NOT NULL,
  `login` tinyblob NOT NULL,
  `pw` tinyblob NOT NULL,
  `comment` mediumblob NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM;
