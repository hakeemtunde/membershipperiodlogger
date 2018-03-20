DROP TABLE IF EXISTS `civicrm_membership_period_payment`;
DROP TABLE IF EXISTS `civicrm_membership_period`;

-- /*******************************************************
-- *
-- * civicrm_membership_period
-- *
-- * Stores membership period log
-- *
-- *******************************************************/
CREATE TABLE `civicrm_membership_period` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique Membership Period ID',
     `membership_id` int unsigned    COMMENT 'FK to Membership',
     `period` int unsigned    COMMENT 'period count',
     `start_date` date    COMMENT 'start date',
     `end_date` date    COMMENT 'end date',
     `has_payment` int unsigned    COMMENT 'indicationki membership period has payment ' 
,
        PRIMARY KEY (`id`)
 
 
,          CONSTRAINT FK_civicrm_membership_period_membership_id FOREIGN KEY (`membership_id`) REFERENCES `civicrm_membership`(`id`) ON DELETE CASCADE  
)  ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci  ;

-- /*******************************************************
-- *
-- * civicrm_membership_period_payment
-- *
-- * Membership period payment
-- *
-- *******************************************************/
CREATE TABLE `civicrm_membership_period_payment` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique Membership Period Payment ID',
     `membership_period_id` int unsigned    COMMENT 'FK to Membership Period ',
     `contribution_id` int unsigned    COMMENT 'FK to Contribution' 
,
        PRIMARY KEY (`id`)
 
 
,          CONSTRAINT FK_civicrm_membership_period_payment_membership_period_id FOREIGN KEY (`membership_period_id`) REFERENCES `civicrm_membership_period`(`id`) ON DELETE CASCADE  
)  ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci  ;

