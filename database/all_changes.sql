/*
-- Date: 2020-09-10 19:06
*/

-- admin table start

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ;

INSERT INTO admin (`id`,`username`,`password`) VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3');

-- admin table centered

-- user table start

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `contactno` varchar(11) DEFAULT NULL,
  `posting_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bank_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
)
---- user table end

--- bank table start

CREATE TABLE `banks` (
  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`bank_id`),
  UNIQUE KEY `bank_name_UNIQUE` (`bank_name`)
);

-- bank table end


---- branch table start

CREATE TABLE `branches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(50) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ;

--- branch table end

--- file table start

CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `downloads` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ;

--- file table end
