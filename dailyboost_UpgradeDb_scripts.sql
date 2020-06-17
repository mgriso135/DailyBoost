CREATE TABLE `dailyboost`.`taskstimespans` (
  `id` INT NOT NULL,
  `userid` INT NULL,
  `taskid` INT NULL,
  `starteventid` BIGINT(20) NULL,
  `starteventdate` DATETIME NULL,
  `starteventtype` CHAR NULL,
  `endeventid` BIGINT(20) NULL,
  `endeventdate` DATETIME NULL,
  `endeventtype` CHAR NULL,
  `timezone` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  INDEX `taskstimespans_FK1_idx` (`userid` ASC),
  INDEX `taskstimespans_FK2_idx` (`taskid` ASC),
  INDEX `taskstimespans_FK3_idx` (`starteventid` ASC)  ,
  INDEX `taskstimespans_FK4_idx` (`endeventid` ASC)  ,
  CONSTRAINT `taskstimespans_FK1`
    FOREIGN KEY (`userid`)
    REFERENCES `dailyboost`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `taskstimespans_FK2`
    FOREIGN KEY (`taskid`)
    REFERENCES `dailyboost`.`tasks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `taskstimespans_FK3`
    FOREIGN KEY (`starteventid`)
    REFERENCES `dailyboost`.`tasksevents` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `taskstimespans_FK4`
    FOREIGN KEY (`endeventid`)
    REFERENCES `dailyboost`.`tasksevents` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

ALTER TABLE `dailyboost`.`taskstimespans` 
DROP FOREIGN KEY `taskstimespans_FK1`,
DROP FOREIGN KEY `taskstimespans_FK2`,
DROP FOREIGN KEY `taskstimespans_FK3`,
DROP FOREIGN KEY `taskstimespans_FK4`;
ALTER TABLE `dailyboost`.`taskstimespans` 
CHANGE COLUMN `userid` `userid` INT(11) NOT NULL ,
CHANGE COLUMN `taskid` `taskid` INT(11) NOT NULL ,
CHANGE COLUMN `starteventid` `starteventid` BIGINT(20) NOT NULL ,
CHANGE COLUMN `starteventdate` `starteventdate` DATETIME NOT NULL ,
CHANGE COLUMN `starteventtype` `starteventtype` CHAR(1) NOT NULL ,
CHANGE COLUMN `endeventid` `endeventid` BIGINT(20) NOT NULL ,
CHANGE COLUMN `endeventdate` `endeventdate` DATETIME NOT NULL ,
CHANGE COLUMN `endeventtype` `endeventtype` CHAR(1) NOT NULL ,
CHANGE COLUMN `timezone` `timezone` VARCHAR(255) NOT NULL ;
ALTER TABLE `dailyboost`.`taskstimespans` 
ADD CONSTRAINT `taskstimespans_FK1`
  FOREIGN KEY (`userid`)
  REFERENCES `dailyboost`.`users` (`id`),
ADD CONSTRAINT `taskstimespans_FK2`
  FOREIGN KEY (`taskid`)
  REFERENCES `dailyboost`.`tasks` (`id`),
ADD CONSTRAINT `taskstimespans_FK3`
  FOREIGN KEY (`starteventid`)
  REFERENCES `dailyboost`.`tasksevents` (`id`),
ADD CONSTRAINT `taskstimespans_FK4`
  FOREIGN KEY (`endeventid`)
  REFERENCES `dailyboost`.`tasksevents` (`id`);

ALTER TABLE `dailyboost`.`taskstimespans` 
ADD UNIQUE INDEX `starteventid_UNIQUE` (`starteventid` ASC)  ,
ADD UNIQUE INDEX `endeventid_UNIQUE` (`endeventid` ASC)  ;
;
ALTER TABLE `dailyboost`.`taskstimespans` 
DROP INDEX `endeventid_UNIQUE` ,
DROP INDEX `starteventid_UNIQUE` ;
;
