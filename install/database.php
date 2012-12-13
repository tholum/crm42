<?php
/*
 * Currently just a dump of the database structure,
 */
ob_start();
?>

-- Server version: 5.5.25
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


--

-- --------------------------------------------------------

--
-- Table structure for table `accounting`
--

CREATE TABLE IF NOT EXISTS `accounting` (
  `accounting_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `amount` float NOT NULL,
  `type` varchar(20) NOT NULL,
  `imported` tinyint(1) NOT NULL,
  `module_name` varchar(45) NOT NULL,
  `module_id` bigint(20) NOT NULL,
  PRIMARY KEY (`accounting_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `accounting_tax`
--

CREATE TABLE IF NOT EXISTS `accounting_tax` (
  `atid` int(11) NOT NULL AUTO_INCREMENT,
  `software_id` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `county` varchar(100) NOT NULL,
  `state_rate` float NOT NULL,
  `county_rate` float NOT NULL,
  `stadium_rate` float NOT NULL,
  `total_rate` float NOT NULL,
  PRIMARY KEY (`atid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE IF NOT EXISTS `activity_log` (
  `activity_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(250) NOT NULL,
  `module_id` int(11) NOT NULL,
  `attached_module_name` varchar(250) NOT NULL,
  `attached_module_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `action` varchar(200) NOT NULL,
  `from` varchar(200) NOT NULL,
  `to` varchar(250) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`activity_id`),
  KEY `module_name` (`module_name`,`module_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=348 ;

-- --------------------------------------------------------

--
-- Table structure for table `alert`
--

CREATE TABLE IF NOT EXISTS `alert` (
  `alert_id` int(25) NOT NULL AUTO_INCREMENT,
  `user_id` int(25) NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_display` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` varchar(100) NOT NULL DEFAULT '',
  `user_group` varchar(100) NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`alert_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `alert_status`
--

CREATE TABLE IF NOT EXISTS `alert_status` (
  `alert_id` int(50) NOT NULL AUTO_INCREMENT,
  `user_id` int(50) NOT NULL DEFAULT '0',
  `date_diamissed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`alert_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `assign_flow_chart_task`
--

CREATE TABLE IF NOT EXISTS `assign_flow_chart_task` (
  `chart_assign_id` int(222) NOT NULL AUTO_INCREMENT,
  `tree_id` varchar(222) NOT NULL,
  `module` varchar(222) NOT NULL,
  `flow_chart_id` int(222) NOT NULL,
  `status_id` varchar(222) NOT NULL,
  `task_status` varchar(222) NOT NULL,
  `profile_page` varchar(222) NOT NULL,
  `module_id` varchar(222) NOT NULL,
  `created_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `completion_date` datetime NOT NULL,
  `completion_result` varchar(222) NOT NULL,
  `completed_by` int(11) NOT NULL,
  `projected_path_due_date` datetime NOT NULL,
  `owner_module_name` varchar(255) NOT NULL,
  `owner_module_id` int(11) NOT NULL,
  `priority_date` date NOT NULL,
  `debug` text NOT NULL,
  `optional1` varchar(250) NOT NULL,
  `optional2` varchar(250) NOT NULL,
  `optional3` varchar(250) NOT NULL,
  `optional4` varchar(250) NOT NULL,
  `optional5` varchar(250) NOT NULL,
  `optional6` varchar(250) NOT NULL,
  `optional7` varchar(250) NOT NULL,
  `optional8` varchar(250) NOT NULL,
  `optional9` varchar(250) NOT NULL,
  `optional10` varchar(250) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`chart_assign_id`),
  KEY `product_id` (`flow_chart_id`),
  KEY `module` (`module`,`flow_chart_id`,`task_status`),
  KEY `last_update` (`last_update`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=645 ;

-- --------------------------------------------------------

--
-- Table structure for table `assign_report_to_system_task`
--

CREATE TABLE IF NOT EXISTS `assign_report_to_system_task` (
  `system_task_id` int(11) NOT NULL AUTO_INCREMENT,
  `global_task_id` int(11) NOT NULL,
  `selection_option_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  PRIMARY KEY (`system_task_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `assign_task`
--

CREATE TABLE IF NOT EXISTS `assign_task` (
  `assign_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` varchar(25) NOT NULL DEFAULT '',
  `module` varchar(100) NOT NULL DEFAULT '',
  `module_id` varchar(25) NOT NULL DEFAULT '',
  `profile_page` varchar(200) NOT NULL,
  `profile_id` varchar(200) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`assign_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=181 ;

-- --------------------------------------------------------

--
-- Table structure for table `assign_tree_task`
--

CREATE TABLE IF NOT EXISTS `assign_tree_task` (
  `assign_sub_task_id` int(222) NOT NULL AUTO_INCREMENT,
  `assigned_task_id` int(222) NOT NULL,
  `status_id` int(222) NOT NULL,
  `assign_task_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `chart_assign_id` int(222) NOT NULL,
  PRIMARY KEY (`assign_sub_task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE IF NOT EXISTS `calendar` (
  `calendar_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(250) NOT NULL,
  `module_id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `source` varchar(500) NOT NULL DEFAULT 'local',
  PRIMARY KEY (`calendar_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_data`
--

CREATE TABLE IF NOT EXISTS `calendar_data` (
  `calendar_event_id` int(11) NOT NULL AUTO_INCREMENT,
  `calendar_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `title` varchar(250) NOT NULL,
  `all_day` tinyint(1) NOT NULL,
  PRIMARY KEY (`calendar_event_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE IF NOT EXISTS `cases` (
  `case_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_id` bigint(20) NOT NULL,
  `module_name` varchar(250) NOT NULL,
  `subject` varchar(250) NOT NULL,
  `OrderNumber` bigint(20) NOT NULL,
  `module_id` varchar(100) NOT NULL,
  `contact_module_name` varchar(255) NOT NULL,
  `contact_module_id` int(11) NOT NULL,
  `TicketNumber` varchar(222) NOT NULL,
  `Title` varchar(222) NOT NULL,
  `CreatedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ModifiedOn` varchar(222) NOT NULL,
  `CaseType` varchar(222) NOT NULL,
  `Priority` varchar(222) NOT NULL,
  `FollowupBy` varchar(222) NOT NULL,
  `Owner_old` varchar(222) NOT NULL,
  `Owner` bigint(20) NOT NULL,
  `Status` varchar(222) NOT NULL,
  `CaseOrigin` varchar(222) NOT NULL,
  `CreatedBy` varchar(222) NOT NULL,
  `ModifiedBy` varchar(222) NOT NULL,
  PRIMARY KEY (`case_id`),
  KEY `case_id` (`case_id`,`group_id`,`module_id`,`contact_module_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=925 ;

-- --------------------------------------------------------

--
-- Table structure for table `cases_activity`
--

CREATE TABLE IF NOT EXISTS `cases_activity` (
  `case_activity_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `case_id` bigint(20) NOT NULL,
  `module_name` varchar(250) NOT NULL,
  `module_id` int(11) NOT NULL,
  `module_type` varchar(20) NOT NULL,
  PRIMARY KEY (`case_activity_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2248 ;

-- --------------------------------------------------------

--
-- Table structure for table `cases_suboptions`
--

CREATE TABLE IF NOT EXISTS `cases_suboptions` (
  `suboption_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `case_id` bigint(20) NOT NULL,
  `option_id` varchar(200) NOT NULL,
  `SubOption` varchar(200) NOT NULL,
  PRIMARY KEY (`suboption_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=220 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat_display_name`
--

CREATE TABLE IF NOT EXISTS `chat_display_name` (
  `module_id` bigint(20) NOT NULL,
  `module_name` varchar(250) NOT NULL,
  `display_name` varchar(150) NOT NULL,
  UNIQUE KEY `module_id` (`module_id`,`module_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE IF NOT EXISTS `chat_messages` (
  `chat_message_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `session_id` bigint(20) NOT NULL,
  `module_id` bigint(20) NOT NULL,
  `module_name` varchar(250) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`chat_message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat_session`
--

CREATE TABLE IF NOT EXISTS `chat_session` (
  `session_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `start_time` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `last_activity` int(11) NOT NULL,
  `session_type` enum('many','first') NOT NULL DEFAULT 'many',
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat_session_user`
--

CREATE TABLE IF NOT EXISTS `chat_session_user` (
  `session_id` bigint(20) NOT NULL,
  `module_id` bigint(20) NOT NULL,
  `module_name` varchar(250) NOT NULL,
  `status` enum('active','pending','inactive','hidden') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chat_status`
--

CREATE TABLE IF NOT EXISTS `chat_status` (
  `module_name` varchar(250) NOT NULL,
  `module_id` int(11) NOT NULL,
  `datetime` int(11) NOT NULL,
  UNIQUE KEY `module_name` (`module_name`,`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `connected_project`
--

CREATE TABLE IF NOT EXISTS `connected_project` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `connected_project_id` int(25) NOT NULL,
  `project_id` int(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `other_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(200) NOT NULL DEFAULT '',
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  `company` int(200) NOT NULL DEFAULT '0',
  `comments` text NOT NULL,
  `company_name` varchar(100) NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `picture` varchar(100) NOT NULL DEFAULT '',
  `directory` varchar(100) NOT NULL DEFAULT '',
  KEY `contact_id` (`contact_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11821 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_address`
--

CREATE TABLE IF NOT EXISTS `contacts_address` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` varchar(100) NOT NULL DEFAULT '',
  `street_address` text NOT NULL,
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` varchar(100) NOT NULL DEFAULT '',
  `zip` varchar(50) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  KEY `address_id` (`address_id`),
  KEY `contact_id` (`contact_id`),
  KEY `contact_id_2` (`contact_id`),
  KEY `contact_id_3` (`contact_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19358 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_email`
--

CREATE TABLE IF NOT EXISTS `contacts_email` (
  `email_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  KEY `email_id` (`email_id`),
  KEY `contact_id` (`contact_id`),
  KEY `contact_id_2` (`contact_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10054 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_im`
--

CREATE TABLE IF NOT EXISTS `contacts_im` (
  `im_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` varchar(100) NOT NULL DEFAULT '',
  `im` varchar(100) NOT NULL DEFAULT '',
  `im_network` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  KEY `im_id` (`im_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_phone`
--

CREATE TABLE IF NOT EXISTS `contacts_phone` (
  `phone_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` varchar(50) NOT NULL DEFAULT '',
  `number` varchar(50) NOT NULL DEFAULT '',
  `ext` int(11) NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT '',
  KEY `phone_id` (`phone_id`),
  KEY `contact_id` (`contact_id`),
  KEY `contact_id_2` (`contact_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16818 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_twitter`
--

CREATE TABLE IF NOT EXISTS `contacts_twitter` (
  `twitter_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` varchar(20) NOT NULL DEFAULT '',
  `twitter` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL DEFAULT '',
  KEY `twitter_id` (`twitter_id`),
  KEY `contact_id` (`contact_id`),
  KEY `contact_id_2` (`contact_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_website`
--

CREATE TABLE IF NOT EXISTS `contacts_website` (
  `website_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` varchar(100) NOT NULL DEFAULT '',
  `website` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  KEY `website_id` (`website_id`),
  KEY `contact_id` (`contact_id`),
  KEY `contact_id_2` (`contact_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=223 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_in_project`
--

CREATE TABLE IF NOT EXISTS `contact_in_project` (
  `contact_in_project_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  PRIMARY KEY (`contact_in_project_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `credit_cards`
--

CREATE TABLE IF NOT EXISTS `credit_cards` (
  `ccid` int(11) NOT NULL AUTO_INCREMENT,
  `ccnum` varchar(200) NOT NULL,
  `cvv` varchar(200) NOT NULL,
  `expiration` varchar(4) NOT NULL,
  `address_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `type` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `module_id` int(11) NOT NULL,
  PRIMARY KEY (`ccid`),
  KEY `module_name` (`module_name`),
  KEY `module_id` (`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `currentcalls`
--

CREATE TABLE IF NOT EXISTS `currentcalls` (
  `currentcalls_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `call_id` varchar(250) NOT NULL,
  `phone_number` bigint(11) NOT NULL,
  `user_id` varchar(250) NOT NULL,
  `ip_address` varchar(250) NOT NULL,
  `exten` varchar(200) NOT NULL,
  `status` varchar(250) NOT NULL,
  `direction` enum('in','out') NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`currentcalls_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `daily_news`
--

CREATE TABLE IF NOT EXISTS `daily_news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `news` longtext NOT NULL,
  PRIMARY KEY (`news_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `department_id` int(25) NOT NULL AUTO_INCREMENT,
  `department_value` varchar(100) NOT NULL,
  PRIMARY KEY (`department_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `eapi_account_displayname`
--

CREATE TABLE IF NOT EXISTS `eapi_account_displayname` (
  `account_id` bigint(20) NOT NULL,
  `display_name` varchar(250) NOT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `eapi_module_account_info_cache`
--

CREATE TABLE IF NOT EXISTS `eapi_module_account_info_cache` (
  `cache_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(255) NOT NULL,
  `module_id` int(11) NOT NULL,
  `phone_number` bigint(11) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `account_id` bigint(20) NOT NULL,
  PRIMARY KEY (`cache_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `eml_address`
--

CREATE TABLE IF NOT EXISTS `eml_address` (
  `eaid` bigint(20) NOT NULL AUTO_INCREMENT,
  `source` varchar(2) NOT NULL,
  `mid` bigint(20) NOT NULL,
  `mailbox` varchar(200) NOT NULL,
  `host` varchar(200) NOT NULL,
  PRIMARY KEY (`eaid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7180 ;

-- --------------------------------------------------------

--
-- Table structure for table `eml_files`
--

CREATE TABLE IF NOT EXISTS `eml_files` (
  `eml_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `filename` varchar(250) NOT NULL,
  `filepath` varchar(250) NOT NULL,
  `upload_status` varchar(50) NOT NULL,
  PRIMARY KEY (`eml_file_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=714 ;

-- --------------------------------------------------------

--
-- Table structure for table `eml_filters`
--

CREATE TABLE IF NOT EXISTS `eml_filters` (
  `eml_filter_id` int(11) NOT NULL AUTO_INCREMENT,
  `mailbox_id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `qualify` text NOT NULL,
  `process` text NOT NULL,
  PRIMARY KEY (`eml_filter_id`),
  KEY `mailbox_id` (`mailbox_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `eml_mailboxs`
--

CREATE TABLE IF NOT EXISTS `eml_mailboxs` (
  `mailbox_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(15) NOT NULL,
  `email_address` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `module_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `connectstring` varchar(255) NOT NULL,
  `smtp_host` varchar(255) NOT NULL,
  `smtp_port` varchar(255) NOT NULL,
  PRIMARY KEY (`mailbox_id`),
  KEY `module_id` (`module_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `eml_message`
--

CREATE TABLE IF NOT EXISTS `eml_message` (
  `mid` bigint(20) NOT NULL AUTO_INCREMENT,
  `subject` varchar(1024) NOT NULL,
  `body` text NOT NULL,
  `encoding` varchar(200) NOT NULL DEFAULT 'plain',
  `unixtime` bigint(20) NOT NULL,
  `importetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `from_displayname` varchar(250) NOT NULL,
  `from_mailbox` varchar(200) NOT NULL,
  `from_host` varchar(200) NOT NULL,
  `read` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `module_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `owner_user_id` int(11) NOT NULL,
  `imap_id` varchar(400) NOT NULL,
  `tmp` longtext NOT NULL,
  `sent_by_user_id` int(11) NOT NULL,
  PRIMARY KEY (`mid`),
  KEY `owner_user_id` (`owner_user_id`) USING BTREE,
  KEY `unixtime` (`unixtime`) USING BTREE,
  KEY `active` (`active`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2684 ;

-- --------------------------------------------------------

--
-- Table structure for table `eml_open`
--

CREATE TABLE IF NOT EXISTS `eml_open` (
  `user_id` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `user_id` (`user_id`,`mid`),
  KEY `user_id_2` (`user_id`,`mid`,`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `eml_owner_top`
--

CREATE TABLE IF NOT EXISTS `eml_owner_top` (
  `user_id` int(11) NOT NULL,
  `top_list` tinyint(1) NOT NULL,
  UNIQUE KEY `user_id_2` (`user_id`),
  KEY `top_list` (`top_list`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `eml_permition`
--

CREATE TABLE IF NOT EXISTS `eml_permition` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `perm` varchar(10) DEFAULT NULL,
  `host` varchar(200) NOT NULL,
  `mailbox` varchar(200) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `module_id` int(11) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `eml_signature`
--

CREATE TABLE IF NOT EXISTS `eml_signature` (
  `signature_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(250) NOT NULL,
  `module_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `signature` text NOT NULL,
  PRIMARY KEY (`signature_id`),
  KEY `module_id` (`module_id`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `eml_template`
--

CREATE TABLE IF NOT EXISTS `eml_template` (
  `eml_template_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(250) NOT NULL,
  `module_id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `subject` varchar(250) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`eml_template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `eml_template_used`
--

CREATE TABLE IF NOT EXISTS `eml_template_used` (
  `module_name` varchar(250) NOT NULL,
  `module_id` int(11) NOT NULL,
  `eml_template_id` int(11) NOT NULL,
  `use_count` int(11) NOT NULL,
  UNIQUE KEY `module_name` (`module_name`,`module_id`,`eml_template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `em_accept`
--

CREATE TABLE IF NOT EXISTS `em_accept` (
  `event_id` int(11) NOT NULL,
  `accept_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `import_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `accept_status` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `accept_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`accept_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `em_contact_documents`
--

CREATE TABLE IF NOT EXISTS `em_contact_documents` (
  `document_id` int(25) NOT NULL AUTO_INCREMENT,
  `contact_id` int(25) NOT NULL,
  `document_name` varchar(100) NOT NULL,
  `document_status` varchar(100) NOT NULL,
  `document_server_name` varchar(500) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_assign_inventory`
--

CREATE TABLE IF NOT EXISTS `erp_assign_inventory` (
  `assign_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_inventory_id` int(222) NOT NULL,
  `inventory_id` int(222) NOT NULL,
  `name` varchar(222) NOT NULL,
  `product_id` int(222) NOT NULL,
  `inventory_cost_increase` double NOT NULL DEFAULT '0',
  `inventory_csr` double NOT NULL DEFAULT '0',
  `inventory_art` double NOT NULL DEFAULT '0',
  `inventory_print` double NOT NULL DEFAULT '0',
  `inventory_dye_sub` double NOT NULL DEFAULT '0',
  `inventory_cut` double NOT NULL DEFAULT '0',
  `inventory_sew` double NOT NULL DEFAULT '0',
  `inventory_shipping` double NOT NULL DEFAULT '0',
  `xs_inventory_usage` double NOT NULL DEFAULT '0',
  `s_inventory_usage` double NOT NULL DEFAULT '0',
  `m_inventory_usage` double NOT NULL DEFAULT '0',
  `l_inventory_usage` double NOT NULL DEFAULT '0',
  `xl_inventory_usage` double NOT NULL DEFAULT '0',
  `2x_inventory_usage` double NOT NULL DEFAULT '0',
  `3x_inventory_usage` double NOT NULL DEFAULT '0',
  `4x_inventory_usage` double NOT NULL DEFAULT '0',
  `check_clone` int(22) NOT NULL,
  PRIMARY KEY (`assign_id`),
  KEY `inventory_id` (`inventory_id`,`product_id`),
  KEY `group_inventory_id` (`group_inventory_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_contactscreen_custom`
--

CREATE TABLE IF NOT EXISTS `erp_contactscreen_custom` (
  `contact_screen_custom_id` int(25) NOT NULL AUTO_INCREMENT,
  `account_id` int(25) NOT NULL,
  `account_type` varchar(100) NOT NULL,
  `csr` int(25) NOT NULL,
  `contact_id` int(25) NOT NULL,
  `peachtree_account` varchar(20) NOT NULL,
  `peachtree_ytd` int(11) NOT NULL,
  `peachtree_prospect` enum('true','false') NOT NULL,
  `peachtree_inactive` enum('true','false') NOT NULL,
  `peachtree_standard_terms` enum('true','false') NOT NULL,
  `peachtree_cod` enum('true','false') NOT NULL,
  `peachtree_prepaid` enum('true','false') NOT NULL,
  `peachtree_due_days` int(11) NOT NULL,
  `peachtree_credit_limit` int(11) NOT NULL,
  `peachtree_account_created` date NOT NULL,
  `sales` varchar(25) NOT NULL,
  `text_exempt` varchar(222) NOT NULL,
  PRIMARY KEY (`contact_screen_custom_id`),
  KEY `contact_id` (`contact_id`),
  KEY `peachtree_account` (`peachtree_account`),
  KEY `peachtree_account_2` (`peachtree_account`),
  KEY `contact_id_2` (`contact_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5107 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_create_group`
--

CREATE TABLE IF NOT EXISTS `erp_create_group` (
  `g_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `assign_fct_id` int(222) NOT NULL,
  `type` varchar(222) NOT NULL,
  `rework_id` int(222) NOT NULL,
  `printer` int(11) NOT NULL,
  `fabric_id` int(11) NOT NULL,
  `workorder_id` int(11) NOT NULL,
  `inches` varchar(255) NOT NULL,
  `created` varchar(255) NOT NULL,
  `fabric_rolles` int(11) NOT NULL,
  `inventory_name` varchar(222) NOT NULL,
  `order_id` int(11) NOT NULL,
  `total_inch` varchar(222) NOT NULL,
  `est_ship_date` varchar(50) DEFAULT NULL,
  `est_task_due_date` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`g_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_defect_category`
--

CREATE TABLE IF NOT EXISTS `erp_defect_category` (
  `cat_id` int(10) NOT NULL AUTO_INCREMENT,
  `defect_category` varchar(50) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_dropdown_options`
--

CREATE TABLE IF NOT EXISTS `erp_dropdown_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(50) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=120 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_fabric_rolls`
--

CREATE TABLE IF NOT EXISTS `erp_fabric_rolls` (
  `fabric_type` varchar(222) NOT NULL,
  `inches` int(222) NOT NULL,
  `location_id` varchar(222) NOT NULL,
  `created_date` date NOT NULL,
  `id` int(222) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_fileserver`
--

CREATE TABLE IF NOT EXISTS `erp_fileserver` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `module_name` varchar(100) NOT NULL,
  `path` varchar(1024) NOT NULL,
  `name` varchar(250) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_fileserver_status`
--

CREATE TABLE IF NOT EXISTS `erp_fileserver_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(200) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_inventory_details`
--

CREATE TABLE IF NOT EXISTS `erp_inventory_details` (
  `inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `Item_number` varchar(222) NOT NULL,
  `name` varchar(50) NOT NULL,
  `inv_desc` varchar(250) NOT NULL,
  `type_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `measured_in` varchar(50) NOT NULL,
  `allocated` varchar(100) NOT NULL,
  `ordered` int(100) NOT NULL,
  `amt_onhand` varchar(100) NOT NULL,
  `reorder` int(100) NOT NULL,
  `reorder_amt` double NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `estimated_cost` varchar(100) NOT NULL,
  `reorder_instruction` varchar(500) NOT NULL,
  `warning_amount` varchar(50) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `uses` varchar(250) NOT NULL,
  `tmp_vendor_id` varchar(100) NOT NULL,
  `tmp_vendor_id2` varchar(100) NOT NULL,
  PRIMARY KEY (`inventory_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_inventory_group`
--

CREATE TABLE IF NOT EXISTS `erp_inventory_group` (
  `inventory_group_id` int(222) NOT NULL AUTO_INCREMENT,
  `group_id` int(222) NOT NULL,
  `group_name` varchar(222) NOT NULL,
  `product_group` varchar(222) NOT NULL,
  `check_clone` int(22) NOT NULL,
  PRIMARY KEY (`inventory_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_inventory_log`
--

CREATE TABLE IF NOT EXISTS `erp_inventory_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_name` varchar(50) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `prev_amount_on_hand` varchar(100) NOT NULL,
  `inventory_used` varchar(100) NOT NULL,
  `current_amount_on_hand` varchar(100) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_inventory_price`
--

CREATE TABLE IF NOT EXISTS `erp_inventory_price` (
  `price_id` int(222) NOT NULL AUTO_INCREMENT,
  `work_orderid` varchar(222) NOT NULL,
  `inventory_type` varchar(222) NOT NULL,
  `sub_product_id` int(222) NOT NULL,
  `size_type` varchar(222) NOT NULL,
  `inventory` varchar(222) NOT NULL,
  `cost_increase` double NOT NULL,
  `group_id` varchar(222) NOT NULL,
  `order_id` int(222) NOT NULL,
  PRIMARY KEY (`price_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_item_type`
--

CREATE TABLE IF NOT EXISTS `erp_item_type` (
  `type_id` int(222) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(2000) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_notes`
--

CREATE TABLE IF NOT EXISTS `erp_notes` (
  `note_id` int(10) NOT NULL AUTO_INCREMENT,
  `note_type` varchar(50) NOT NULL,
  `contact_id` int(222) NOT NULL,
  `note_content` varchar(1000) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`note_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_order`
--

CREATE TABLE IF NOT EXISTS `erp_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `csr` int(25) NOT NULL,
  `shipping_charges` varchar(50) NOT NULL,
  `state_tax` double NOT NULL,
  `county_tax` double NOT NULL,
  `stadium_tax` double NOT NULL,
  `total_tax` double NOT NULL,
  `multiplier` double NOT NULL DEFAULT '1',
  `grant_total` double NOT NULL DEFAULT '0',
  `sub_total` float NOT NULL,
  `event_date` varchar(50) NOT NULL,
  `ship_date` varchar(50) NOT NULL,
  `vendor_contact_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `shipping_address` int(11) NOT NULL,
  `billing_address` int(11) NOT NULL,
  `weight` double NOT NULL,
  `size_kit` double NOT NULL,
  `shipment_type` varchar(2) NOT NULL DEFAULT '03',
  `shipment_label` blob NOT NULL,
  `shipment_hvr` blob NOT NULL,
  `ccid` int(11) NOT NULL,
  `status` varchar(222) NOT NULL,
  `created` varchar(222) NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `vendor_contact_id` (`vendor_contact_id`,`contact_id`,`shipping_address`,`billing_address`,`ccid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_printer_paper`
--

CREATE TABLE IF NOT EXISTS `erp_printer_paper` (
  `id` int(222) NOT NULL AUTO_INCREMENT,
  `printer` varchar(222) NOT NULL,
  `paper` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_product`
--

CREATE TABLE IF NOT EXISTS `erp_product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_product_id` int(222) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `product_type` varchar(50) NOT NULL,
  `item_number` varchar(50) NOT NULL,
  `product_status` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `quantity_6_12` double NOT NULL DEFAULT '0',
  `quantity_13_24` double NOT NULL DEFAULT '0',
  `quantity_25_49` double NOT NULL DEFAULT '0',
  `quantity_50_74` double NOT NULL DEFAULT '0',
  `quantity_75` double NOT NULL DEFAULT '0',
  `size_xs` double NOT NULL DEFAULT '0',
  `size_s` double NOT NULL DEFAULT '0',
  `size_m` double NOT NULL DEFAULT '0',
  `size_l` double NOT NULL DEFAULT '0',
  `size_xl` double NOT NULL DEFAULT '0',
  `size_2x` double NOT NULL DEFAULT '0',
  `size_3x` double NOT NULL DEFAULT '0',
  `size_4x` double NOT NULL DEFAULT '0',
  `order_cost_increase` double NOT NULL DEFAULT '0',
  `order_csr` double NOT NULL DEFAULT '0',
  `order_art` double NOT NULL DEFAULT '0',
  `order_print` double NOT NULL DEFAULT '0',
  `order_dye_sub` double NOT NULL DEFAULT '0',
  `order_cut` double NOT NULL DEFAULT '0',
  `order_sew` double NOT NULL DEFAULT '0',
  `order_shipping` double NOT NULL DEFAULT '0',
  `per_item_cost_increase` double NOT NULL DEFAULT '0',
  `per_item_csr` double NOT NULL DEFAULT '0',
  `per_item_art` double NOT NULL DEFAULT '0',
  `per_item_print` double NOT NULL DEFAULT '0',
  `per_item_dye_sub` double NOT NULL DEFAULT '0',
  `per_item_cut` double NOT NULL DEFAULT '0',
  `per_item_sew` double NOT NULL DEFAULT '0',
  `per_item_shipping` double NOT NULL DEFAULT '0',
  `per_size_cost_increase` double NOT NULL DEFAULT '0',
  `per_size_csr` double NOT NULL DEFAULT '0',
  `per_size_art` double NOT NULL DEFAULT '0',
  `per_size_print` double NOT NULL DEFAULT '0',
  `per_size_dye_sub` double NOT NULL DEFAULT '0',
  `per_size_cut` double NOT NULL DEFAULT '0',
  `per_size_sew` double NOT NULL DEFAULT '0',
  `per_size_shipping` double NOT NULL DEFAULT '0',
  `check_clone` int(11) NOT NULL,
  PRIMARY KEY (`product_id`),
  KEY `group_product_id` (`group_product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_product_order`
--

CREATE TABLE IF NOT EXISTS `erp_product_order` (
  `workorder_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `gp_id` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(222) NOT NULL,
  PRIMARY KEY (`workorder_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_product_screen`
--

CREATE TABLE IF NOT EXISTS `erp_product_screen` (
  `product_id` int(255) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `item_num` varchar(255) NOT NULL,
  `product_type` varchar(255) NOT NULL,
  `product_status` varchar(255) NOT NULL,
  `uses` varchar(255) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_rework`
--

CREATE TABLE IF NOT EXISTS `erp_rework` (
  `rework_id` int(255) NOT NULL,
  `order_id` int(222) NOT NULL,
  `product_id` int(222) NOT NULL,
  `qty` int(255) NOT NULL,
  `list_item_requested` varchar(2222) NOT NULL,
  `unique_marks` varchar(255) NOT NULL,
  `defect_category_id` varchar(255) NOT NULL,
  `describe_problem` varchar(255) NOT NULL,
  `notes` varchar(2222) NOT NULL,
  `fabric_scrap` varchar(222) DEFAULT NULL,
  `printer_used` varchar(222) DEFAULT NULL,
  `rework_item_id` int(255) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`rework_item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_salesdata_custom`
--

CREATE TABLE IF NOT EXISTS `erp_salesdata_custom` (
  `sdc_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `contact_id` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `amount` float NOT NULL,
  PRIMARY KEY (`sdc_id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_salesdata_custum`
--

CREATE TABLE IF NOT EXISTS `erp_salesdata_custum` (
  `sdc_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `contact_id` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `amount` float NOT NULL,
  PRIMARY KEY (`sdc_id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_size`
--

CREATE TABLE IF NOT EXISTS `erp_size` (
  `size_alot_id` int(222) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `unit_price` varchar(222) NOT NULL,
  `per_size_price` double NOT NULL,
  `size` varchar(222) NOT NULL,
  `base_price` double NOT NULL,
  `total` varchar(222) NOT NULL,
  `quantity` varchar(222) NOT NULL,
  `order_id` int(11) NOT NULL,
  PRIMARY KEY (`size_alot_id`),
  KEY `product_id` (`product_id`,`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_size_dependant`
--

CREATE TABLE IF NOT EXISTS `erp_size_dependant` (
  `group` varchar(222) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `option_type` varchar(222) NOT NULL,
  `xs_size_dependant` varchar(255) NOT NULL,
  `s_size_dependant` varchar(255) NOT NULL,
  `m_size_dependant` varchar(222) NOT NULL,
  `l_size_dependant` varchar(255) NOT NULL,
  `xl_size_dependant` varchar(222) NOT NULL,
  `2x_size_dependant` varchar(222) NOT NULL,
  `3x_size_dependant` varchar(222) NOT NULL,
  `4x_size_dependant` varchar(222) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `erp_window`
--

CREATE TABLE IF NOT EXISTS `erp_window` (
  `window_id` int(222) NOT NULL AUTO_INCREMENT,
  `global_task_id` varchar(222) NOT NULL,
  `window` int(222) NOT NULL,
  `root_id` int(200) NOT NULL,
  PRIMARY KEY (`window_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=271 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_window_position`
--

CREATE TABLE IF NOT EXISTS `erp_window_position` (
  `connection_id` int(222) NOT NULL AUTO_INCREMENT,
  `source_id` int(222) NOT NULL,
  `target_id` int(222) NOT NULL,
  `connection_type` varchar(1000) NOT NULL,
  `windows` int(222) NOT NULL,
  `root_id` int(222) NOT NULL,
  `global_task_tree` int(11) NOT NULL,
  PRIMARY KEY (`connection_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_work_order`
--

CREATE TABLE IF NOT EXISTS `erp_work_order` (
  `work_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `assign_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `inve_size` varchar(222) NOT NULL,
  `fabric` varchar(50) NOT NULL,
  `zipper` varchar(50) NOT NULL,
  `pad` varchar(50) NOT NULL,
  `elastic` varchar(50) NOT NULL,
  `lining` varchar(50) NOT NULL,
  `binding` varchar(50) NOT NULL,
  `seamoption` varchar(222) NOT NULL,
  `variableinfo` varchar(222) NOT NULL,
  `profile_JV5` varchar(222) NOT NULL,
  `seamcrossing` varchar(222) NOT NULL,
  `group_name` varchar(222) NOT NULL,
  PRIMARY KEY (`work_order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `erp_work_order_document`
--

CREATE TABLE IF NOT EXISTS `erp_work_order_document` (
  `doc_id` int(255) NOT NULL AUTO_INCREMENT,
  `doc_local_name` varchar(255) NOT NULL,
  `doc_server_name` varchar(255) NOT NULL,
  PRIMARY KEY (`doc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `event_date`
--

CREATE TABLE IF NOT EXISTS `event_date` (
  `event_id` varchar(25) NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `files_category`
--

CREATE TABLE IF NOT EXISTS `files_category` (
  `file_category_id` int(50) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`file_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `files_user_control`
--

CREATE TABLE IF NOT EXISTS `files_user_control` (
  `control_id` int(50) NOT NULL AUTO_INCREMENT,
  `file_id` int(50) NOT NULL DEFAULT '0',
  `user_group` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`control_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `flags`
--

CREATE TABLE IF NOT EXISTS `flags` (
  `flag_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(255) NOT NULL,
  `module_id` int(11) NOT NULL,
  `flag_type_id` int(11) NOT NULL,
  `owner_module_name` varchar(255) NOT NULL,
  `owner_module_id` varchar(11) NOT NULL,
  PRIMARY KEY (`flag_id`),
  KEY `module_id` (`module_id`,`owner_module_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=721 ;

-- --------------------------------------------------------

--
-- Table structure for table `flag_type`
--

CREATE TABLE IF NOT EXISTS `flag_type` (
  `flag_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `color` varchar(6) NOT NULL,
  `description` varchar(250) NOT NULL,
  PRIMARY KEY (`flag_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE IF NOT EXISTS `follow` (
  `user_id` int(11) NOT NULL,
  `module_name` varchar(250) NOT NULL,
  `module_id` int(11) NOT NULL,
  UNIQUE KEY `user_id` (`user_id`,`module_name`,`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `global_settings`
--

CREATE TABLE IF NOT EXISTS `global_settings` (
  `setting_name` varchar(250) NOT NULL,
  `value` varchar(500) NOT NULL,
  UNIQUE KEY `setting_name` (`setting_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `group_access`
--

CREATE TABLE IF NOT EXISTS `group_access` (
  `rule_id` int(50) NOT NULL AUTO_INCREMENT,
  `group_id` varchar(50) NOT NULL DEFAULT '',
  `user_id` varchar(50) NOT NULL DEFAULT '',
  `access_level` enum('Admin','User') NOT NULL DEFAULT 'Admin',
  PRIMARY KEY (`rule_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=127 ;

-- --------------------------------------------------------

--
-- Table structure for table `importance_type`
--

CREATE TABLE IF NOT EXISTS `importance_type` (
  `importance_type_id` int(25) NOT NULL AUTO_INCREMENT,
  `importance_type_value` varchar(100) NOT NULL,
  PRIMARY KEY (`importance_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `insert_to_report`
--

CREATE TABLE IF NOT EXISTS `insert_to_report` (
  `insert_id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `field_value` varchar(100) NOT NULL,
  `timestamp` varchar(100) NOT NULL,
  `column_name` varchar(100) NOT NULL,
  `column_name_main` varchar(500) NOT NULL,
  `table_name_main` varchar(100) NOT NULL,
  PRIMARY KEY (`insert_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_details`
--

CREATE TABLE IF NOT EXISTS `inventory_details` (
  `inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `measured_in` varchar(50) NOT NULL,
  `allocated` varchar(100) NOT NULL,
  `ordered` varchar(100) NOT NULL,
  `amt_onhand` varchar(100) NOT NULL,
  `reorder` varchar(100) NOT NULL,
  `vendor_id` varchar(25) NOT NULL,
  `estimated_cost` varchar(100) NOT NULL,
  `reorder_instruction` varchar(500) NOT NULL,
  PRIMARY KEY (`inventory_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `knowledgebase`
--

CREATE TABLE IF NOT EXISTS `knowledgebase` (
  `knowledgebase_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `body` longtext NOT NULL,
  `last_edited` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_edited_user` int(11) NOT NULL,
  PRIMARY KEY (`knowledgebase_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `module_address`
--

CREATE TABLE IF NOT EXISTS `module_address` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `module_name` varchar(100) NOT NULL DEFAULT '',
  `street_address` text NOT NULL,
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` varchar(100) NOT NULL DEFAULT '',
  `zip` varchar(50) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  KEY `address_id` (`address_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=163 ;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `news_id` int(50) NOT NULL AUTO_INCREMENT,
  `user_id` int(50) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`news_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `news_category`
--

CREATE TABLE IF NOT EXISTS `news_category` (
  `news_cid` int(25) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`news_cid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `page_tablecolumn`
--

CREATE TABLE IF NOT EXISTS `page_tablecolumn` (
  `page_tablecolumn_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `table_id` varchar(255) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `options` longtext NOT NULL,
  `sort` enum('','ASC','DESC') NOT NULL,
  `sortable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_tablecolumn_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=513 ;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `payee_module_name` varchar(200) NOT NULL,
  `payee_module_id` int(11) NOT NULL,
  `for_module_name` varchar(200) NOT NULL,
  `for_module_id` int(11) NOT NULL,
  `chart_assign_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `curency` varchar(10) NOT NULL,
  `payment_module` varchar(200) NOT NULL,
  `payment_module_id` int(11) NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `synced` int(11) NOT NULL,
  `synced_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `refund` enum('no','yes','done') NOT NULL,
  `refund_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `info` text NOT NULL,
  `state_tax` double NOT NULL,
  `county_tax` double NOT NULL,
  `stadium_tax` double NOT NULL,
  `total_tax` double NOT NULL,
  `atid` int(11) NOT NULL,
  `shipping_amt` double NOT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `predicted_flow_chart_task`
--

CREATE TABLE IF NOT EXISTS `predicted_flow_chart_task` (
  `predicted_assign_id` int(222) NOT NULL AUTO_INCREMENT,
  `order_id` int(222) NOT NULL,
  `module` varchar(222) NOT NULL,
  `flow_chart_id` int(222) NOT NULL,
  `module_id` varchar(222) NOT NULL,
  `due_date` varchar(222) NOT NULL,
  `chart_assign_id` int(11) NOT NULL,
  PRIMARY KEY (`predicted_assign_id`),
  KEY `product_id` (`order_id`,`flow_chart_id`),
  KEY `module` (`module`,`flow_chart_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2714 ;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `project_id` int(25) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `importance_type_id` varchar(100) NOT NULL,
  `department_id` varchar(100) NOT NULL,
  `me_to_project_id` varchar(100) NOT NULL,
  `due_date` date NOT NULL,
  `started` date NOT NULL,
  `user_id` int(25) NOT NULL,
  `complete` varchar(100) NOT NULL,
  `status_id` int(25) NOT NULL,
  `parent_project_id` int(25) NOT NULL,
  `select_mode` varchar(20) NOT NULL,
  `cur_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`project_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_document`
--

CREATE TABLE IF NOT EXISTS `project_document` (
  `document_id` int(25) NOT NULL AUTO_INCREMENT,
  `project_id` int(25) NOT NULL,
  `document_name` varchar(100) NOT NULL,
  `document_server_name` varchar(500) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cur_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`document_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_status`
--

CREATE TABLE IF NOT EXISTS `project_status` (
  `status_id` int(25) NOT NULL AUTO_INCREMENT,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `securekey`
--

CREATE TABLE IF NOT EXISTS `securekey` (
  `key_id` int(11) NOT NULL AUTO_INCREMENT,
  `enc_key` varchar(1024) NOT NULL,
  `hash` varchar(1024) NOT NULL,
  `type` varchar(100) NOT NULL,
  `current_attempts` int(11) NOT NULL,
  PRIMARY KEY (`key_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_tracking`
--

CREATE TABLE IF NOT EXISTS `shipping_tracking` (
  `shipper_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(200) NOT NULL,
  `module_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `shipping_module` varchar(100) NOT NULL,
  `tracking_number` varchar(300) NOT NULL,
  `last_edited` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_shipped` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_arrived` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `shipper_id` (`shipper_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `tagging_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` varchar(100) NOT NULL DEFAULT '',
  `module` varchar(100) NOT NULL DEFAULT '',
  `module_id` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`tagging_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=421 ;

-- --------------------------------------------------------

--
-- Table structure for table `tags_name`
--

CREATE TABLE IF NOT EXISTS `tags_name` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `module_name` varchar(200) NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(100) NOT NULL DEFAULT '',
  `assigned_to` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  `due_date` int(25) NOT NULL,
  `description` varchar(100) NOT NULL DEFAULT '',
  `is_global` enum('yes','no') NOT NULL DEFAULT 'no',
  `cat_id` varchar(50) NOT NULL DEFAULT '',
  `task_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed` enum('Yes','No') NOT NULL DEFAULT 'No',
  `completed_on` int(25) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `doc_name` varchar(50) NOT NULL,
  `doc_server_name` varchar(255) NOT NULL,
  `importance_type_id` varchar(100) NOT NULL,
  `completed_by` varchar(100) NOT NULL,
  PRIMARY KEY (`task_id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=178 ;

-- --------------------------------------------------------

--
-- Table structure for table `tasks_category`
--

CREATE TABLE IF NOT EXISTS `tasks_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `color` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`cat_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `task_relation`
--

CREATE TABLE IF NOT EXISTS `task_relation` (
  `rel_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  `module_id` varchar(100) NOT NULL,
  PRIMARY KEY (`rel_id`),
  KEY `module` (`module`,`module_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=201 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attachment`
--

CREATE TABLE IF NOT EXISTS `tbl_attachment` (
  `attachment_id` int(25) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `size` varchar(100) NOT NULL,
  `omessage_id` int(25) NOT NULL,
  `content` varchar(256) NOT NULL,
  PRIMARY KEY (`attachment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bug`
--

CREATE TABLE IF NOT EXISTS `tbl_bug` (
  `bug_info_id` int(25) NOT NULL AUTO_INCREMENT,
  `variable` varchar(200) NOT NULL,
  `value` varchar(200) NOT NULL,
  `bug_id` int(25) NOT NULL,
  `importance` varchar(100) NOT NULL DEFAULT 'normal',
  PRIMARY KEY (`bug_info_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bug_linker`
--

CREATE TABLE IF NOT EXISTS `tbl_bug_linker` (
  `bug_id` int(25) NOT NULL AUTO_INCREMENT,
  `project_id` int(25) NOT NULL,
  PRIMARY KEY (`bug_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_capacity`
--

CREATE TABLE IF NOT EXISTS `tbl_capacity` (
  `capacity_id` int(11) NOT NULL AUTO_INCREMENT,
  `shedule_capacity` varchar(20) NOT NULL,
  `estimated_capacity` varchar(20) NOT NULL,
  `due_date` varchar(200) NOT NULL,
  `department` varchar(20) NOT NULL,
  PRIMARY KEY (`capacity_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_countries`
--

CREATE TABLE IF NOT EXISTS `tbl_countries` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `value` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_date`
--

CREATE TABLE IF NOT EXISTS `tbl_date` (
  `date_id` int(12) NOT NULL AUTO_INCREMENT,
  `user_id` int(12) NOT NULL DEFAULT '0',
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(250) NOT NULL DEFAULT '',
  `category` varchar(50) NOT NULL DEFAULT '',
  `recurring` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`date_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_element_permission`
--

CREATE TABLE IF NOT EXISTS `tbl_element_permission` (
  `element_access_id` int(50) NOT NULL AUTO_INCREMENT,
  `module_id` int(50) NOT NULL,
  `module` varchar(100) NOT NULL,
  `access_to_type` varchar(100) NOT NULL,
  `access_to` varchar(100) NOT NULL,
  `access_type` enum('FULL','VIEWONLY') NOT NULL DEFAULT 'VIEWONLY',
  `display` varchar(50) NOT NULL,
  PRIMARY KEY (`element_access_id`),
  KEY `module_id` (`module_id`),
  KEY `module` (`module`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=59266 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_file`
--

CREATE TABLE IF NOT EXISTS `tbl_file` (
  `file_id` int(50) NOT NULL AUTO_INCREMENT,
  `user_id` int(50) NOT NULL DEFAULT '0',
  `directory` varchar(50) NOT NULL DEFAULT '',
  `name` text NOT NULL,
  `modified` varchar(50) NOT NULL DEFAULT '',
  `uploaded` varchar(50) NOT NULL DEFAULT '',
  `title` text NOT NULL,
  `description` text NOT NULL,
  `category` int(50) NOT NULL DEFAULT '0',
  `group` varchar(100) NOT NULL,
  `isprivate` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_global_task`
--

CREATE TABLE IF NOT EXISTS `tbl_global_task` (
  `global_task_id` int(25) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `module` varchar(222) NOT NULL,
  `order_num` varchar(222) NOT NULL,
  `task_type` varchar(222) NOT NULL,
  `department_chk_tsk` varchar(222) NOT NULL,
  `est_day_dep` varchar(222) NOT NULL,
  `est_min_task` varchar(222) NOT NULL,
  `department_id` int(25) NOT NULL,
  `top` int(11) NOT NULL,
  `left` int(11) NOT NULL,
  `global_task_tree_id` int(11) NOT NULL,
  `default_path` int(11) NOT NULL,
  PRIMARY KEY (`global_task_id`),
  KEY `department_id` (`department_id`,`global_task_tree_id`,`default_path`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_global_task_default`
--

CREATE TABLE IF NOT EXISTS `tbl_global_task_default` (
  `global_task_id` int(11) NOT NULL,
  `module_name` varchar(250) NOT NULL,
  PRIMARY KEY (`module_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_global_task_holidays`
--

CREATE TABLE IF NOT EXISTS `tbl_global_task_holidays` (
  `date_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `name` varchar(250) NOT NULL,
  PRIMARY KEY (`date_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_global_task_link`
--

CREATE TABLE IF NOT EXISTS `tbl_global_task_link` (
  `global_task_link_id` int(25) NOT NULL AUTO_INCREMENT,
  `from_module_name` varchar(100) NOT NULL,
  `from_module_id` int(11) NOT NULL,
  `to_module_name` varchar(100) NOT NULL,
  `to_module_id` int(11) NOT NULL,
  `global_task_tree_id` int(11) NOT NULL,
  PRIMARY KEY (`global_task_link_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=140 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_global_task_status`
--

CREATE TABLE IF NOT EXISTS `tbl_global_task_status` (
  `global_task_status_id` int(25) NOT NULL AUTO_INCREMENT,
  `global_task_status_name` varchar(100) NOT NULL,
  `global_task_id` int(25) NOT NULL,
  `user_group` int(25) NOT NULL,
  `left` int(11) NOT NULL,
  `top` int(11) NOT NULL,
  `global_task_tree_id` int(11) NOT NULL,
  `default_path` int(11) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '50',
  PRIMARY KEY (`global_task_status_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_global_task_status_result`
--

CREATE TABLE IF NOT EXISTS `tbl_global_task_status_result` (
  `global_task_status_result_id` int(25) NOT NULL AUTO_INCREMENT,
  `global_task_status_id` int(25) NOT NULL,
  `global_task_id` int(25) NOT NULL,
  `user_group` varchar(100) NOT NULL,
  PRIMARY KEY (`global_task_status_result_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_global_task_tree`
--

CREATE TABLE IF NOT EXISTS `tbl_global_task_tree` (
  `global_task_tree_id` int(11) NOT NULL AUTO_INCREMENT,
  `global_task_tree_name` varchar(100) NOT NULL,
  `root_task_id` int(222) NOT NULL,
  PRIMARY KEY (`global_task_tree_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_message_inbox`
--

CREATE TABLE IF NOT EXISTS `tbl_message_inbox` (
  `imessage_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(100) NOT NULL,
  `message` longtext NOT NULL,
  `subject` varchar(255) NOT NULL,
  `omessage_id` varchar(100) NOT NULL,
  `isread` enum('True','False') NOT NULL DEFAULT 'False',
  `timestamp` varchar(50) NOT NULL,
  `last_opened` varchar(100) NOT NULL,
  PRIMARY KEY (`imessage_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_message_outbox`
--

CREATE TABLE IF NOT EXISTS `tbl_message_outbox` (
  `omessage_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(100) NOT NULL,
  `message` longtext NOT NULL,
  `subject` varchar(255) NOT NULL,
  `timestamp` varchar(50) NOT NULL,
  `reply_to_message_id` int(11) NOT NULL,
  `send_to_text` varchar(256) NOT NULL,
  PRIMARY KEY (`omessage_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_message_sent_to`
--

CREATE TABLE IF NOT EXISTS `tbl_message_sent_to` (
  `sent_id` int(11) NOT NULL AUTO_INCREMENT,
  `omessage_id` varchar(100) NOT NULL,
  `to` varchar(100) NOT NULL,
  `timestamp` varchar(50) NOT NULL,
  PRIMARY KEY (`sent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_note`
--

CREATE TABLE IF NOT EXISTS `tbl_note` (
  `note_id` int(100) NOT NULL AUTO_INCREMENT,
  `module_id` int(100) NOT NULL DEFAULT '0',
  `user_id` int(100) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `n_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `module_name` varchar(100) NOT NULL,
  PRIMARY KEY (`note_id`),
  KEY `module_name` (`module_name`),
  KEY `module_id` (`module_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=792 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_note_status`
--

CREATE TABLE IF NOT EXISTS `tbl_note_status` (
  `note_id` int(100) NOT NULL AUTO_INCREMENT,
  `module_id` int(100) NOT NULL DEFAULT '0',
  `user_id` int(100) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `n_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `module_name` varchar(100) NOT NULL,
  `note_status` varchar(100) NOT NULL,
  PRIMARY KEY (`note_id`),
  KEY `module_id` (`module_id`),
  KEY `module_name` (`module_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projected_path`
--

CREATE TABLE IF NOT EXISTS `tbl_projected_path` (
  `path_id` int(222) NOT NULL AUTO_INCREMENT,
  `start_from` int(222) NOT NULL,
  `from_name` varchar(222) NOT NULL,
  `global_task_tree_id` int(222) NOT NULL,
  `end_to` int(222) NOT NULL,
  `to_name` varchar(222) NOT NULL,
  `global_task_status_id` int(222) NOT NULL,
  `root_id` int(222) NOT NULL,
  PRIMARY KEY (`path_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_report_access`
--

CREATE TABLE IF NOT EXISTS `tbl_report_access` (
  `report_access_id` int(25) NOT NULL AUTO_INCREMENT,
  `search_report_id` int(25) NOT NULL,
  `group_id` int(25) NOT NULL,
  PRIMARY KEY (`report_access_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_search_report`
--

CREATE TABLE IF NOT EXISTS `tbl_search_report` (
  `search_report_id` int(25) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) NOT NULL,
  `report_title` varchar(100) NOT NULL,
  `fields` blob NOT NULL,
  `fieldHeads` blob NOT NULL,
  `field_types` blob NOT NULL,
  `link_fields` blob NOT NULL,
  `link_table_names` blob NOT NULL,
  `link_cols` blob NOT NULL,
  `link_field_texts` blob NOT NULL,
  `link_field_types` blob NOT NULL,
  `access_group_id` int(25) NOT NULL,
  `cur_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `links` blob NOT NULL,
  PRIMARY KEY (`search_report_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_theme`
--

CREATE TABLE IF NOT EXISTS `tbl_theme` (
  `id` int(20) NOT NULL,
  `logo_file_name` varchar(255) NOT NULL,
  `logo_server_name` varchar(255) NOT NULL,
  `header_file_name` varchar(255) NOT NULL,
  `header_server_name` varchar(255) NOT NULL,
  `body_file_name` varchar(255) NOT NULL,
  `body_server_name` varchar(255) NOT NULL,
  `footer_file_name` varchar(255) NOT NULL,
  `footer_server_name` varchar(255) NOT NULL,
  `h1_color` varchar(255) NOT NULL,
  `h2_color` varchar(255) NOT NULL,
  `tab_default_color` varchar(255) NOT NULL,
  `tab_sel_color` varchar(255) NOT NULL,
  `default_letter_color` varchar(255) NOT NULL,
  `tab_sel_color_default` varchar(255) NOT NULL,
  `text_color` varchar(255) NOT NULL,
  `hyperlink_color` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `middle_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `email_id` varchar(100) NOT NULL DEFAULT '',
  `phone` varchar(15) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `home_phone` varchar(15) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `block` varchar(20) NOT NULL DEFAULT '',
  `flag` varchar(256) NOT NULL,
  `google_apps_id` varchar(100) DEFAULT NULL,
  `google_apps_password` varchar(50) DEFAULT NULL,
  `email_username` varchar(100) NOT NULL,
  `email_password_enc` blob NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_usergroup`
--

CREATE TABLE IF NOT EXISTS `tbl_usergroup` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) NOT NULL DEFAULT '',
  `group_description` varchar(500) NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- Table structure for table `template`
--

CREATE TABLE IF NOT EXISTS `template` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL DEFAULT 'EMAIL',
  `file` varchar(250) NOT NULL,
  `title` varchar(200) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` mediumtext,
  `module` varchar(50) NOT NULL,
  `module_id` int(11) NOT NULL,
  `query` varchar(1500) NOT NULL,
  `timestamp` varchar(50) NOT NULL,
  `email_to` varchar(100) NOT NULL,
  `left` int(25) NOT NULL,
  `top` int(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=84 ;

-- --------------------------------------------------------

--
-- Table structure for table `time_tracker`
--

CREATE TABLE IF NOT EXISTS `time_tracker` (
  `time_tracker_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `module_name` varchar(250) NOT NULL,
  `module_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  PRIMARY KEY (`time_tracker_id`),
  KEY `user_id` (`user_id`,`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=99 ;

-- --------------------------------------------------------

--
-- Table structure for table `twitter`
--

CREATE TABLE IF NOT EXISTS `twitter` (
  `twitter_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `twitter_name` varchar(150) NOT NULL,
  `tweet` varchar(150) NOT NULL,
  `search_string` varchar(250) NOT NULL,
  `result` tinyint(1) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sent_on` datetime NOT NULL,
  `replyed` tinyint(1) NOT NULL,
  UNIQUE KEY `twitter_id` (`twitter_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `upload`
--

CREATE TABLE IF NOT EXISTS `upload` (
  `file_name` varchar(55) NOT NULL,
  `type` varchar(22) NOT NULL,
  `path` varchar(100) NOT NULL,
  `server_file_name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_asterisk`
--

CREATE TABLE IF NOT EXISTS `user_asterisk` (
  `uaid` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `module` varchar(50) NOT NULL,
  `module_id` bigint(20) NOT NULL,
  PRIMARY KEY (`uaid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_in_project`
--

CREATE TABLE IF NOT EXISTS `user_in_project` (
  `user_in_project_id` int(25) NOT NULL AUTO_INCREMENT,
  `project_id` int(25) NOT NULL,
  `user_id` int(25) NOT NULL,
  PRIMARY KEY (`user_in_project_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE IF NOT EXISTS `user_settings` (
  `user_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `value` varchar(250) NOT NULL,
  UNIQUE KEY `user_id` (`user_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_details`
--

CREATE TABLE IF NOT EXISTS `vendor_details` (
  `vendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `city` varchar(50) NOT NULL,
  `zip_code` varchar(50) NOT NULL,
  `contact_id` varchar(25) NOT NULL,
  PRIMARY KEY (`vendor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zip_code`
--

CREATE TABLE IF NOT EXISTS `zip_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zip_code` varchar(5) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `county` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `state_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `state_prefix` varchar(2) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `area_code` varchar(3) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `time_zone` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `zip_code` (`zip_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;


INSERT INTO `tbl_user` (`user_id`, `user_name`, `password`, `first_name`, `middle_name`, `last_name`, `email_id`, `phone`, `mobile`, `home_phone`, `fax`, `website`, `image`, `create_time`, `block`, `flag`, `google_apps_id`, `google_apps_password`, `email_username`, `email_password_enc`) VALUES
(1, 'admin', '<?php echo $db->clean_string( $_REQUEST['admin_password'] ); ?>', 'Admin', '', 'User', '', NULL, NULL, NULL, NULL, NULL, NULL, '2012-01-18 12:35:15', '', '', NULL, NULL, '', '');

INSERT INTO `tbl_usergroup` (`group_id`, `group_name`, `group_description`, `required`) VALUES
(1, 'Admin', 'Administrator', 1),
(2, 'csradmin', 'csr admin', 1);
INSERT INTO `group_access` (`rule_id`, `group_id`, `user_id`, `access_level`) VALUES
(1, '1', '1', 'Admin'),
(2, '1', '1', 'Admin'),
(3, '2', '1', 'Admin'),
(4, '2', '1', 'Admin');

INSERT INTO `contacts` (`contact_id`, `other_id`, `user_id`, `type`, `first_name`, `last_name`, `title`, `company`, `comments`, `company_name`, `timestamp`, `picture`, `directory`) VALUES
(11821, 0, '16', 'Company', '', '', '', 0, '', 'Coulee Techlink Inc', '2012-12-13 14:47:17', '', '');

--
-- Dumping data for table `contacts_address`
--

INSERT INTO `contacts_address` (`address_id`, `contact_id`, `street_address`, `city`, `state`, `zip`, `country`, `type`) VALUES
(19358, '11821', '1129 Riders Club RD', 'Onalaska', 'WI', '54650', '', 'Work');

--
-- Dumping data for table `contacts_email`
--

INSERT INTO `contacts_email` (`email_id`, `contact_id`, `email`, `type`) VALUES
(10054, '11821', 'crm42@couleetechlink.com', 'Work');

--
-- Dumping data for table `contacts_phone`
--

INSERT INTO `contacts_phone` (`phone_id`, `contact_id`, `number`, `ext`, `type`) VALUES
(16818, '11821', '6087838324', 0, 'Work');

--
-- Dumping data for table `contacts_website`
--

INSERT INTO `contacts_website` (`website_id`, `contact_id`, `website`, `type`) VALUES
(223, '11821', 'http://www.slimcrm.com', 'Work'),
(224, '11821', 'http://www.couleetechlink.com', '');
INSERT INTO `cases` (`case_id`, `group_id`, `module_name`, `subject`, `OrderNumber`, `module_id`, `contact_module_name`, `contact_module_id`, `TicketNumber`, `Title`, `CreatedOn`, `ModifiedOn`, `CaseType`, `Priority`, `FollowupBy`, `Owner_old`, `Owner`, `Status`, `CaseOrigin`, `CreatedBy`, `ModifiedBy`) VALUES
(925, 0, 'CONTACTS', 'Welcome To Our CRM', 0, '11821', 'CONTACTS', 11821, '', '', '2012-12-13 14:46:53', '', '', '', '', '', 1, 'Active', '', '1', '');

<?php 
$sql = ob_get_contents();
ob_end_clean();
?>