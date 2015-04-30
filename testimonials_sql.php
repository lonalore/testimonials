CREATE TABLE `testimonials` (
`tm_id` int(11) unsigned NOT NULL auto_increment,
`tm_name` varchar(50) NOT NULL DEFAULT '',
`tm_url` varchar(255) NOT NULL DEFAULT '',
`tm_message` text NOT NULL,
`tm_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
`tm_blocked` tinyint(3) unsigned NOT NULL DEFAULT '0',
`tm_ip` varchar(45) NOT NULL DEFAULT '',
`tm_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
PRIMARY KEY  (`tm_id`)
) ENGINE=MyISAM;
