ALTER TABLE `#__eiko_einsatzberichte` ADD `createdate` DATETIME NOT NULL AFTER `updatedate`;
ALTER TABLE `#__eiko_einsatzberichte` ADD `modified_by` INT(11)  NOT NULL AFTER `created_by`;
UPDATE `#__eiko_einsatzberichte` SET `createdate` = `updatedate`;
UPDATE `#__eiko_einsatzberichte` SET `modified_by` = `created_by`;

