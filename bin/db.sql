CREATE TABLE `cms` (
  `id`       INT(11)   NOT NULL AUTO_INCREMENT,
  `hidden`   TINYINT(1)         DEFAULT '0',
  `tag`      CHAR(250)          DEFAULT NULL,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `title`    TEXT,
  `body`     MEDIUMTEXT,
  PRIMARY KEY (`id`),
  KEY `hidden` (`hidden`) USING HASH,
  KEY `tag` (`tag`) USING HASH
)
  ENGINE = InnoDB
  DEFAULT CHARSET = `utf8`;

CREATE TABLE `cms_menu` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `name`        CHAR(250)        DEFAULT NULL,
  `description` CHAR(250)        DEFAULT NULL,
  `maxdepth`    INT(11)          DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING HASH
)
  ENGINE = InnoDB
  DEFAULT CHARSET = `utf8`;

CREATE TABLE `cms_menu_items` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `menu`       INT(11) NOT NULL,
  `position`   INT(11)          DEFAULT NULL,
  `parent`     INT(11)          DEFAULT NULL,
  `visible`    TINYINT(1)       DEFAULT '1',
  `page`       INT(11)          DEFAULT NULL,
  `link`       VARCHAR(2048)    DEFAULT NULL,
  `link_label` CHAR(250)        DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu`) USING HASH,
  KEY `page_id` (`page`) USING HASH,
  KEY `position` (`position`) USING BTREE,
  CONSTRAINT `fk_cms_menu_items_menu` FOREIGN KEY (`menu`) REFERENCES `cms_menu` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_cms_menu_items_page` FOREIGN KEY (`page`) REFERENCES `cms` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
  ENGINE = InnoDB
  DEFAULT CHARSET = `utf8`;

CREATE TABLE `cms_snippets` (
  `id`          INT(11)   NOT NULL AUTO_INCREMENT,
  `name`        CHAR(250) NOT NULL,
  `description` CHAR(250)          DEFAULT NULL,
  `text`        TEXT,
  PRIMARY KEY (`id`),
  KEY `name` (`name`) USING HASH
)
  ENGINE = InnoDB
  DEFAULT CHARSET = `utf8`;
