CREATE TABLE `module_instance` (
  `id`            INT(11)      NOT NULL AUTO_INCREMENT,
  `instance_code` VARCHAR(255)          DEFAULT NULL,
  `module_code`   VARCHAR(45)  NOT NULL,
  `config_class`  VARCHAR(255) NOT NULL,
  `config`        JSON                  DEFAULT NULL,
  `is_active`     INT(11)      NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = utf8_unicode_ci;

CREATE TABLE `action` (
  `id`       INT(11) NOT NULL AUTO_INCREMENT,
  `code`     VARCHAR(45)      DEFAULT NULL,
  `detail`   JSON             DEFAULT NULL,
  `datetime` DATETIME         DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 7
  DEFAULT CHARSET = utf8_unicode_ci;