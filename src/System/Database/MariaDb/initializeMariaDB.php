<?php
/**
 * Created by PhpStorm.
 * User: Drake
 * Date: 28.12.2017
 * Time: 10:17
 */
function initializeMariaDB(){
    $conn2 = new mysqli("localhost", "root", "");
    if ($conn2->connect_error) {
        die('Connect Error (' . $conn2->connect_errno . ') '. $conn2->connect_error);
    }
    $sql = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
            SET AUTOCOMMIT = 0;
            START TRANSACTION;
            SET time_zone = \"+00:00\";
            
            /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
            /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
            /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
            /*!40101 SET NAMES utf8mb4 */;
            
            CREATE DATABASE IF NOT EXISTS `slippery_elm` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
            USE `slippery_elm`;
            
            CREATE TABLE `elm_pages` (
              `pagesID` int(11) NOT NULL,
              `pagesName` varchar(255) NOT NULL,
              `pagesContent` text NOT NULL,
              `pagesParentPage` varchar(255) NOT NULL,
              `pagesKeywords` varchar(255) NOT NULL,
              `pagesSorting` int(11) NOT NULL,
              `pagesCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `pagesModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `pagesCreaterID` int(11) NOT NULL,
              `pagesModifierID` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            INSERT INTO `elm_pages` (`pagesID`, `pagesName`, `pagesContent`, `pagesParentPage`, `pagesKeywords`, `pagesSorting`, `pagesCreated`, `pagesModified`, `pagesCreaterID`, `pagesModifierID`) VALUES
            (1, 'HOME', '', '', '', 1, '2017-12-15 09:40:19', '2017-12-15 09:40:19', 0, 0),
            (2, 'HOME', '', '', '', 0, '2017-12-15 09:41:05', '2017-12-15 09:41:05', 0, 0);         
            
            CREATE TABLE `elm_role` (
              `roleID` int(11) NOT NULL,
              `roleName` varchar(255) NOT NULL,
              `roleDescription` text NOT NULL,
              `roleCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `roleModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `roleCreaterID` int(11) NOT NULL,
              `roleModifierID` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            INSERT INTO `elm_role` (`roleID`, `roleName`, `roleDescription`, `roleCreated`, `roleModified`, `roleCreaterID`, `roleModifierID`) VALUES
            (1, 'admin', 'administrator', '2017-12-15 09:42:04', '2017-12-15 09:42:04', 0, 0),
            (2, 'user', 'user', '2017-12-15 09:42:04', '2017-12-15 09:42:04', 0, 0);
            
            CREATE TABLE `elm_role_pages` (
              `role_FK` int(11) NOT NULL,
              `pages_FK` int(11) NOT NULL,
              `canEdit` int(11) NOT NULL,
              `canView` int(11) NOT NULL,
              `canDelete` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            CREATE TABLE `elm_setting` (
              `settingsID` int(11) NOT NULL,
              `settingsKey` varchar(255) NOT NULL,
              `settingsValue` varchar(255) NOT NULL,
              `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `settingsModifierID` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            CREATE TABLE `elm_users` (
              `usersID` int(11) NOT NULL,
              `username` varchar(255) NOT NULL,
              `password` varchar(255) NOT NULL,
              `email` varchar(255) NOT NULL,
              `isActive` tinyint(1) NOT NULL,
              `usersCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `usersModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `usersCreaterID` int(11) NOT NULL,
              `usersModifierID` int(11) NOT NULL,
              `role_FK` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            CREATE TABLE `elm_version` (
              `versionID` int(11) NOT NULL,
              `databaseVersion` varchar(255) NOT NULL,
              `scriptName` varchar(255) NOT NULL,
              `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            ALTER TABLE `elm_pages` ADD PRIMARY KEY (`pagesID`);
            
            ALTER TABLE `elm_role` ADD PRIMARY KEY (`roleID`);
            
            ALTER TABLE `elm_role_pages`
              ADD KEY `role_FK` (`role_FK`,`pages_FK`),
              ADD KEY `pages_FK` (`pages_FK`);
            
            ALTER TABLE `elm_setting` ADD PRIMARY KEY (`settingsID`);
            
            ALTER TABLE `elm_users`
              ADD PRIMARY KEY (`usersID`),
              ADD KEY `role_FK` (`role_FK`);
            
            ALTER TABLE `elm_version` ADD PRIMARY KEY (`versionID`);
            
            ALTER TABLE `elm_pages` MODIFY `pagesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
            
            ALTER TABLE `elm_role` MODIFY `roleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
            
            ALTER TABLE `elm_setting` MODIFY `settingsID` int(11) NOT NULL AUTO_INCREMENT;
            
            ALTER TABLE `elm_users` MODIFY `usersID` int(11) NOT NULL AUTO_INCREMENT;
            
            ALTER TABLE `elm_version` MODIFY `versionID` int(11) NOT NULL AUTO_INCREMENT;
            
            ALTER TABLE `elm_role_pages`
              ADD CONSTRAINT `elm_role_pages_ibfk_1` FOREIGN KEY (`role_FK`) REFERENCES `elm_role` (`roleID`) ON DELETE CASCADE ON UPDATE CASCADE,
              ADD CONSTRAINT `elm_role_pages_ibfk_2` FOREIGN KEY (`pages_FK`) REFERENCES `elm_pages` (`pagesID`) ON DELETE CASCADE ON UPDATE CASCADE;
            
            ALTER TABLE `elm_users` ADD CONSTRAINT `elm_users_ibfk_1` FOREIGN KEY (`role_FK`) REFERENCES `elm_role` (`roleID`) ON DELETE CASCADE ON UPDATE CASCADE;
            COMMIT;
            
            /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
            /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
            /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
    $conn2->query($sql);
    $conn2->close();
}