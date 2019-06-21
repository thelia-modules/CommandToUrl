
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- command_url
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `command_url`;

CREATE TABLE `command_url`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `command` VARCHAR(255),
    `token` VARCHAR(255),
    `allowed_ips` VARCHAR(255),
    `active` TINYINT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
