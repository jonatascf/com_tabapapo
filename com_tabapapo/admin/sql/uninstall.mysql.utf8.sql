DROP TABLE IF EXISTS `#__tabapapo`;
DROP TABLE IF EXISTS `#__tabapapo_msg`;
DROP TABLE IF EXISTS `#__tabapapo_usu`;

DELETE FROM `#__ucm_history` WHERE ucm_type_id in 
	(select type_id from `#__content_types` where type_alias in ('com_tabapapo.tabapapo','com_tabapapo.category'));
DELETE FROM `#__content_types` WHERE type_alias in ('com_tabapapo.tabapapo','com_tabapapo.category');