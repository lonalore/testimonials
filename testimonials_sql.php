CREATE TABLE `testimonials` (
`tm_id` int(11) unsigned NOT NULL auto_increment,
`tm_name` varchar(50) NOT NULL DEFAULT '' COMMENT 'Author anme.',
`tm_url` varchar(255) NOT NULL DEFAULT '' COMMENT 'URL.',
`tm_message` text NOT NULL COMMENT 'Testimonial message.',
`tm_datestamp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created at..',
`tm_blocked` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Blocked or not.',
`tm_ip` varchar(45) NOT NULL DEFAULT '' COMMENT 'Author IP.',
`tm_order` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Order.',
PRIMARY KEY  (`tm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
