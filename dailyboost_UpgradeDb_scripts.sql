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
