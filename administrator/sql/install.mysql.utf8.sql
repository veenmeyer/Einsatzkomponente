CREATE TABLE IF NOT EXISTS `#__eiko_einsatzberichte` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`article_id` VARCHAR(255)  NOT NULL DEFAULT '0' ,
`ordering` INT(11)  NOT NULL ,
`data1` INT(10)  NOT NULL ,
`image` VARCHAR(255)  NOT NULL ,
`address` VARCHAR(255)  NOT NULL ,
`date1` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`date2` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`date3` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`summary` VARCHAR(255)  NOT NULL ,
`boss` VARCHAR(255)  NOT NULL ,
`boss2` VARCHAR(255)  NOT NULL ,
`people` VARCHAR(255)  NOT NULL ,
`department` INT(11)  NOT NULL ,
`desc` TEXT NOT NULL ,
`alerting` TEXT NOT NULL ,
`gmap_report_latitude` VARCHAR(255)  NOT NULL ,
`gmap_report_longitude` VARCHAR(255)  NOT NULL ,
`counter` INT(20)  NOT NULL ,
`gmap` VARCHAR(255)  NOT NULL ,
`status_fb` VARCHAR(255)  NOT NULL DEFAULT '1' ,
`presse_label` VARCHAR(255)  NOT NULL DEFAULT 'Presselink' ,
`presse` VARCHAR(255)  NOT NULL ,
`presse2_label` VARCHAR(255)  NOT NULL DEFAULT 'Presselink' ,
`presse2` VARCHAR(255)  NOT NULL ,
`presse3_label` VARCHAR(255)  NOT NULL DEFAULT 'Presselink' ,
`presse3` VARCHAR(255)  NOT NULL ,
`updatedate` DATETIME NOT NULL ,
`einsatzticker` VARCHAR(255)  NOT NULL ,
`notrufticker` VARCHAR(255)  NOT NULL ,
`tickerkat` INT(10)  NOT NULL ,
`auswahl_orga` TEXT NOT NULL ,
`vehicles` TEXT NOT NULL ,
`ausruestung` TEXT NOT NULL ,
`status` VARCHAR(255)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__eiko_einsatzarten` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`title` VARCHAR(255)  NOT NULL ,
`marker` VARCHAR(255)  NOT NULL ,
`beschr` VARCHAR(255)  NOT NULL ,
`icon` VARCHAR(255)  NOT NULL ,
`list_icon` VARCHAR(255)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__eiko_organisationen` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`ordering` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`gmap_icon` VARCHAR(255)  NOT NULL ,
`detail1_label` VARCHAR(255)  NOT NULL DEFAULT 'Detail1',
`detail1` VARCHAR(255)  NOT NULL ,
`detail2_label` VARCHAR(255)  NOT NULL DEFAULT 'Detail2',
`detail2` VARCHAR(255)  NOT NULL ,
`detail3_label` VARCHAR(255)  NOT NULL DEFAULT 'Detail3',
`detail3` VARCHAR(255)  NOT NULL ,
`detail4_label` VARCHAR(255)  NOT NULL DEFAULT 'Detail4',
`detail4` VARCHAR(255)  NOT NULL ,
`detail5_label` VARCHAR(255)  NOT NULL DEFAULT 'Detail5',
`detail5` VARCHAR(255)  NOT NULL ,
`detail6_label` VARCHAR(255)  NOT NULL DEFAULT 'Detail6',
`detail6` VARCHAR(255)  NOT NULL ,
`detail7_label` VARCHAR(255)  NOT NULL DEFAULT 'Detail7',
`detail7` VARCHAR(255)  NOT NULL ,
`link` VARCHAR(255)  NOT NULL ,
`gmap_latitude` VARCHAR(255)  NOT NULL ,
`gmap_longitude` VARCHAR(255)  NOT NULL ,
`ffw` VARCHAR(255)  NOT NULL ,
`desc` TEXT NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__eiko_fahrzeuge` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`ordering` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`detail1_label` VARCHAR(255)  NOT NULL DEFAULT 'Detail1',
`detail1` VARCHAR(255)  NOT NULL ,
`detail2_label` VARCHAR(255)  NOT NULL DEFAULT 'Detail2',
`detail2` VARCHAR(255)  NOT NULL ,
`detail3_label` VARCHAR(255)  NOT NULL DEFAULT 'Detail3',
`detail3` VARCHAR(255)  NOT NULL ,
`detail4_label` VARCHAR(255)  NOT NULL DEFAULT 'Detail4',
`detail4` VARCHAR(255)  NOT NULL ,
`detail5_label` VARCHAR(255)  NOT NULL DEFAULT 'Detail5',
`detail5` VARCHAR(255)  NOT NULL ,
`detail6_label` VARCHAR(255)  NOT NULL DEFAULT 'Detail6',
`detail6` VARCHAR(255)  NOT NULL ,
`detail7_label` VARCHAR(255)  NOT NULL DEFAULT 'Detail7',
`detail7` VARCHAR(255)  NOT NULL ,
`department` TEXT NOT NULL ,
`ausruestung` TEXT NOT NULL ,
`link` VARCHAR(255)  NOT NULL ,
`image` VARCHAR(255)  NOT NULL ,
`desc` TEXT NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__eiko_ausruestung` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`name` VARCHAR(255)  NOT NULL ,
`image` VARCHAR(255)  NOT NULL ,
`beschreibung` TEXT NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__eiko_alarmierungsarten` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`ordering` INT(11)  NOT NULL ,
`title` VARCHAR(255)  NOT NULL ,
`image` VARCHAR(255)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__eiko_images` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`ordering` INT(11)  NOT NULL ,
`image` VARCHAR(255)  NOT NULL ,
`report_id` INT(11)  NOT NULL ,
`comment` TEXT NOT NULL ,
`thumb` VARCHAR(255)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__eiko_gmap_config` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`gmap_zoom_level` VARCHAR(255)  NOT NULL DEFAULT '12',
`gmap_onload` VARCHAR(255)  NOT NULL DEFAULT 'HYBRID',
`gmap_width` INT(11)  NOT NULL DEFAULT '600',
`gmap_height` INT(11)  NOT NULL DEFAULT '300',
`gmap_alarmarea` TEXT NOT NULL ,
`start_lat` VARCHAR(255)  NOT NULL DEFAULT '53.286871867528056',
`start_lang` VARCHAR(255)  NOT NULL DEFAULT '7.475510015869147',
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__eiko_tickerkat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `beschreibung` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;
