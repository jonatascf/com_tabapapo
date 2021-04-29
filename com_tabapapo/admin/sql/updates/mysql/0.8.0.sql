DROP TABLE IF EXISTS `#__tabapapo`;
DROP TABLE IF EXISTS `#__tabamsg`;
DROP TABLE IF EXISTS `#__tabausu`;

CREATE TABLE `#__tabapapo` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10)     NOT NULL DEFAULT '0',
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by`  INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`title` VARCHAR(25) NOT NULL,
	`alias` VARCHAR(40) NOT NULL DEFAULT '',
	`published` tinyint(4) NOT NULL DEFAULT '1',
	`catid` INT(11) NOT NULL DEFAULT '0',
    `description` VARCHAR(4000) NOT NULL DEFAULT '',
	`params` VARCHAR(1024) NOT NULL DEFAULT '',
	`imagem` VARCHAR(1024) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)
)
	ENGINE=InnoDB 
	AUTO_INCREMENT =0
	DEFAULT CHARSET=utf8mb4 
	DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `#__tabapapo_msg`(
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`reservado` tinyint(4) NOT NULL,
	`sala_id` INT(11) NOT NULL,
	`usu_id`  INT(11) NOT NULL,
	`params` VARCHAR(1024) NOT NULL DEFAULT '',
	`msg` TEXT NOT NULL,
	`falacom_id`  INT(11) NOT NULL,
	`tempo` DATETIME NOT NULL,
	PRIMARY KEY(`id`)
	)
	ENGINE=InnoDB 
	AUTO_INCREMENT =0
	DEFAULT CHARSET=utf8mb4 
	DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `#__tabapapo_usu`(
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
	AUTO_INCREMENT =0
	DEFAULT CHARSET=utf8mb4 
	DEFAULT COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `content_history_options`) 
VALUES
('Tabapapo', 'com_tabapapo.tabapapo', 
'{"formFile":"administrator\\/components\\/com_tabapapo\\/models\\/forms\\/tabapapo.xml", 
"hideFields":["asset_id","checked_out","checked_out_time","version","lft","rgt","level","path"], 
"ignoreChanges":["checked_out", "checked_out_time", "path"],
"convertToInt":[], 
"displayLookup":[
{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},
{"sourceColumn":"parent_id","targetTable":"#__tabapapo","targetColumn":"id","displayColumn":"greeting"},
{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"}]}'),
('Tabapapo Category', 'com_tabapapo.category',
'{"formFile":"administrator\\/components\\/com_categories\\/models\\/forms\\/category.xml", 
"hideFields":["asset_id","checked_out","checked_out_time","version","lft","rgt","level","path","extension"], 
"ignoreChanges":["modified_user_id", "modified_time", "checked_out", "checked_out_time", "version", "hits", "path"],
"convertToInt":["publish_up", "publish_down"], 
"displayLookup":[
{"sourceColumn":"created_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},
{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},
{"sourceColumn":"modified_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},
{"sourceColumn":"parent_id","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"}]}');