DROP TABLE IF EXISTS `#__tabapapo`;
DROP TABLE IF EXISTS `#__tabapapo_msg`;
DROP TABLE IF EXISTS `#__tabapapo_usu`;

DELETE FROM `#__content_types` WHERE type_alias in ('com_tabapapo.tabapapo','com_tabapapo.category');