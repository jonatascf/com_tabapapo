SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `#__tabapapo` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `asset_id` int unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
    `title` varchar(255) NOT NULL DEFAULT '',
    `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `description` VARCHAR(4000) NOT NULL DEFAULT '',
    `state` tinyint NOT NULL DEFAULT 0,
    `catid` int unsigned NOT NULL DEFAULT 0,
    `created` datetime NOT NULL,
    `created_by` int unsigned NOT NULL DEFAULT 0,
    `created_by_alias` varchar(255) NOT NULL DEFAULT '',
    `modified` datetime NOT NULL,
    `modified_by` int unsigned NOT NULL DEFAULT 0,
    `checked_out` int unsigned,
    `checked_out_time` datetime,
    `published` tinyint NOT NULL DEFAULT 0,
    `publish_up` datetime,
    `publish_down` datetime,
    `version` int unsigned NOT NULL DEFAULT 1,
    `metakey` text,
    `metadesc` text NOT NULL DEFAULT '',
    `access` int unsigned NOT NULL DEFAULT 0,
    `hits` int unsigned NOT NULL DEFAULT 0,
    `metadata` text NOT NULL DEFAULT '',
    `featured` tinyint unsigned NOT NULL DEFAULT 0 COMMENT 'Set if article is featured.',
    `language` varchar(7) NOT NULL,
	`params` text NOT NULL,
	PRIMARY KEY (`id`),
    KEY `idx_access` (`access`),
    KEY `idx_checkout` (`checked_out`),
    KEY `idx_state` (`published`),
    KEY `idx_catid` (`catid`),
    KEY `idx_createdby` (`created_by`),
    KEY `idx_featured_catid` (`featured`,`catid`),
    KEY `idx_language` (`language`)
)
	ENGINE=InnoDB 
	DEFAULT CHARSET=utf8mb4 
	DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__tabapapo_msg`(
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`reservado` tinyint(4) NOT NULL DEFAULT '0',
	`sala_id` INT(11) NOT NULL,
	`usu_id`  INT(11) NOT NULL,
	`params` VARCHAR(1024) NOT NULL DEFAULT '',
	`msg` TEXT NOT NULL,
	`falacom_id`  INT(11) NOT NULL,
	`tempo` DATETIME NOT NULL,
	PRIMARY KEY(`id`)
	)
	ENGINE=InnoDB
	DEFAULT CHARSET=utf8mb4 
	DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__tabapapo_usu`(
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`sala_id` INT(11) NOT NULL,
	`usu_id` VARCHAR (40) NOT NULL,
	`status` VARCHAR (140) NULL,
	`params` VARCHAR(1024) NOT NULL DEFAULT '',
	`ip` VARCHAR (20) NOT NULL,
	`tempo` DATETIME NOT NULL,
	PRIMARY KEY(`id`)
	)
	ENGINE=InnoDB 
	DEFAULT CHARSET=utf8mb4 
	DEFAULT COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`) 
VALUES
('Tabapapo', 'com_tabapapo.tabapapo',
'{"special":{"dbtable":"#__tabapapo","key":"id","type":"tabapapo","prefix":"TabaPapoTable"}}', 
'',
'',
'',
'{"formFile":"administrator\\/components\\/com_tabapapo\\/models\\/forms\\/tabapapo.xml", 
"hideFields":["params"], 
"ignoreChanges":["created","created_by"],
"convertToInt":[], 
"displayLookup":[
{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},
{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"}]}');