CREATE TABLE `typecho_links` (
  `lid` int(10) unsigned NOT NULL auto_increment COMMENT 'links±íÖ÷¼ü',
  `name` varchar(200) default NULL COMMENT 'linksÃû³Æ',
  `url` varchar(200) default NULL COMMENT 'linksÍøÖ·',
  `description` varchar(200) default NULL COMMENT 'linksÃèÊö',
  `order` int(10) unsigned default '0' COMMENT 'linksÅÅĞò',
  PRIMARY KEY  (`lid`)
) ENGINE=MYISAM  DEFAULT CHARSET=%charset%;
