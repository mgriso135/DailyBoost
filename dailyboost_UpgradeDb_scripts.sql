CREATE TABLE `dailyboost`.`externalcalendarsuserscategories` (
  `id` INT NOT NULL,
  `userid` INT NOT NULL,
  `categoryid` INT NOT NULL,
  `externalappid` INT NOT NULL,
  `calendarid` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`, `userid`, `categoryid`, `externalappid`, `calendarid`));

CREATE TABLE `dailyboost`.`externalcalendartasksevents` (
  `id` INT NOT NULL,
  `extcalusercatid` INT NOT NULL,
  `eventid` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`, `extcalusercatid`, `eventid`),
  INDEX `exdtcal_fk1_idx` (`extcalusercatid` ASC) VISIBLE,
  CONSTRAINT `exdtcal_fk1`
    FOREIGN KEY (`extcalusercatid`)
    REFERENCES `dailyboost`.`externalcalendarsuserscategories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

ALTER TABLE `dailyboost`.`externalcalendarsuserscategories` 
ADD INDEX `extuser_fk1_idx` (`userid` ASC) VISIBLE,
ADD INDEX `extcategory_fk1_idx` (`categoryid` ASC) VISIBLE,
ADD INDEX `extappid_idx` (`externalappid` ASC) VISIBLE;
;
ALTER TABLE `dailyboost`.`externalcalendarsuserscategories` 
ADD CONSTRAINT `extuser_fk1`
  FOREIGN KEY (`userid`)
  REFERENCES `dailyboost`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `extcategory_fk1`
  FOREIGN KEY (`categoryid`)
  REFERENCES `dailyboost`.`categories` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `extappid`
  FOREIGN KEY (`externalappid`)
  REFERENCES `dailyboost`.`usersexternalapps` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `dailyboost`.`usersexternalapps` 
ADD COLUMN `AccountName` VARCHAR(255) NULL AFTER `ExternalAppName`;

ALTER TABLE `dailyboost`.`usersexternalapps` 
RENAME TO  `dailyboost`.`usersexternalaccounts` ;

ALTER TABLE `dailyboost`.`externalcalendarsuserscategories` 
ADD COLUMN `calendarname` VARCHAR(255) NULL AFTER `calendarid`;

ALTER TABLE `dailyboost`.`externalcalendartasksevents` 
RENAME TO  `dailyboost`.`externalcalendartask` ;

ALTER TABLE `dailyboost`.`externalcalendartask` 
ADD CONSTRAINT `extcal_task_fk1`
  FOREIGN KEY (`internaltaskid`)
  REFERENCES `dailyboost`.`tasks` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `dailyboost`.`categoriesusers` 
ADD CONSTRAINT `category1_fk1`
  FOREIGN KEY (`idcategory`)
  REFERENCES `dailyboost`.`categories` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `dailyboost`.`categoriesusers` 
ADD CONSTRAINT `catusr_user_fk1`
  FOREIGN KEY (`iduser`)
  REFERENCES `dailyboost`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `dailyboost`.`externalcalendartask` 
ADD CONSTRAINT `extcal_categoriesusers_fk1`
  FOREIGN KEY (`userid` , `categoryid`)
  REFERENCES `dailyboost`.`categoriesusers` (`iduser` , `idcategory`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `dailyboost`.`externalcalendartask` 
ADD COLUMN `externalcalendarid` VARCHAR(255) NOT NULL AFTER `categoryid`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`, `userid`, `categoryid`, `externaltaskid`, `externalcalendarid`);
;

ALTER TABLE `dailyboost`.`externalcalendarsuserscategories` 
ADD COLUMN `calendartype` VARCHAR(255) NOT NULL AFTER `categoryid`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`, `userid`, `categoryid`, `calendartype`, `externalaccountid`, `calendarid`);
;

ALTER TABLE `dailyboost`.`usersexternalaccounts` 
CHANGE COLUMN `ExternalAppType` `ExternalAccountType` VARCHAR(45) NOT NULL ,
CHANGE COLUMN `ExternalAppName` `ExternalAccountName` VARCHAR(45) NOT NULL ;

ALTER TABLE `dailyboost`.`externalcalendartask` 
ADD COLUMN `externalaccountid` INT NOT NULL AFTER `categoryid`,
ADD COLUMN `calendartype` VARCHAR(255) NOT NULL AFTER `externalaccountid`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`, `userid`, `categoryid`, `calendartype`, `externalcalendarid`, `externaltaskid`, `externalaccountid`);
;
ALTER TABLE `dailyboost`.`externalcalendartask` 
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`);
;
ALTER TABLE `dailyboost`.`externalcalendartask` 
DROP COLUMN `externalcalendarid`,
DROP COLUMN `calendartype`,
DROP COLUMN `externalaccountid`,
CHANGE COLUMN `internaltaskid` `internaltaskid` INT(11) NOT NULL AFTER `categoryid`;
ALTER TABLE `dailyboost`.`externalcalendartask` 
ADD COLUMN `externalcalendarid` INT NOT NULL AFTER `categoryid`;

ALTER TABLE `dailyboost`.`externalcalendartask` 
ADD INDEX `extcal_calext_fk1_idx` (`externalcalendarid` ASC) VISIBLE;
;
ALTER TABLE `dailyboost`.`externalcalendartask` 
ADD CONSTRAINT `extcal_calext_fk1`
  FOREIGN KEY (`externalcalendarid`)
  REFERENCES `dailyboost`.`externalcalendarsuserscategories` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
