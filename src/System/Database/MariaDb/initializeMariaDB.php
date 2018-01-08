<?php
/**
 * Created by PhpStorm.
 * User: Drake
 * Date: 28.12.2017
 * Time: 10:17
 */
function initializeMariaDB(){
    GLOBAL $conn;
    $sql = "CREATE TABLE `elm_pages` (
              `pagesID` int(11) NOT NULL,
              `pagesName` varchar(255) NOT NULL,
              `pagesContent` text,
              `pagesParentPage` varchar(255),
              `pagesKeywords` varchar(255) NOT NULL,
              `pagesSorting` int(11) NOT NULL,
              `pagesCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `pagesModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `pagesCreaterID` int(11) NOT NULL,
              `pagesModifierID` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    if ($conn->query($sql) === TRUE) {
        echo "table elm_pages created successfully";
        $sql = "CREATE TABLE `elm_role` (
                  `roleID` int(11) NOT NULL,
                  `roleName` varchar(255) NOT NULL,
                  `roleDescription` text NOT NULL,
                  `roleCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `roleModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `roleCreaterID` int(11) NOT NULL,
                  `roleModifierID` int(11) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        if ($conn->query($sql) === TRUE) {
            echo "table elm_role created successfully";
            $sql = "CREATE TABLE `elm_role_pages` (
                      `role_FK` int(11) NOT NULL,
                      `pages_FK` int(11) NOT NULL,
                      `canEdit` int(11) NOT NULL,
                      `canView` int(11) NOT NULL,
                      `canDelete` int(11) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            if ($conn->query($sql) === TRUE) {
                echo "table elm_role_pages created successfully";
                $sql = "CREATE TABLE `elm_setting` (
                          `settingsID` INT(11) NOT NULL,
                          `settingsKey` VARCHAR(255) NOT NULL,
                          `settingsValue` VARCHAR(255) NOT NULL,
                          `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          `settingsModifierID` INT(11) NOT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                if ($conn->query($sql) === TRUE) {
                    echo "table elm_setting created successfully";
                    $sql = "CREATE TABLE `elm_users` (
                              `usersID` INT(11) NOT NULL,
                              `username` VARCHAR(255) NOT NULL,
                              `password` VARCHAR(255) NOT NULL,
                              `email` VARCHAR(255) NOT NULL,
                              `isActive` TINYINT(1) NOT NULL,
                              `usersCreated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                              `usersModified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                              `usersCreaterID` INT(11) NOT NULL,
                              `usersModifierID` INT(11) NOT NULL,
                              `role_FK` INT(11) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                    if ($conn->query($sql) === TRUE) {
                        echo "table elm_users created successfully";
                        $sql = "CREATE TABLE `elm_version` (
                                    `versionID` INT(11) NOT NULL,
                                    `databaseVersion` VARCHAR(255) NOT NULL,
                                    `scriptName` VARCHAR(255) NOT NULL,
                                    `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                        if ($conn->query($sql) === TRUE) {
                            echo "table elm_version created successfully";
                            $sql = "INSERT INTO `elm_pages` 
                                      (`pagesName`, `pagesContent`, `pagesParentPage`, `pagesKeywords`, `pagesSorting`, `pagesCreated`, `pagesModified`, `pagesCreaterID`, `pagesModifierID`) 
                                    VALUES 
                                      ('HOME', 'hello world', '', 'Start', 1, '2017-12-15 09:40:19', '2017-12-15 09:40:19', 0, 0);";
                            if ($conn->query($sql) === TRUE) {
                                echo "data successfully inserted into elm_pages";
                                $sql = "INSERT INTO `elm_role` 
                                          (`roleID`, `roleName`, `roleDescription`, `roleCreated`, `roleModified`, `roleCreaterID`, `roleModifierID`) 
                                        VALUES
                                          (1, 'admin', 'administrator', '2017-12-15 09:42:04', '2017-12-15 09:42:04', 0, 0), 
                                          (2, 'user', 'user', '2017-12-15 09:42:04', '2017-12-15 09:42:04', 0, 0);";
                                if ($conn->query($sql) === TRUE) {
                                    echo "data successfully inserted into elm_role";
                                    $sql = "ALTER TABLE `elm_pages` 
                                              ADD PRIMARY KEY (`pagesID`);";
                                    if ($conn->query($sql) === TRUE) {
                                        echo "successfully altered elm_pages";
                                        $sql = "ALTER TABLE `elm_role` 
                                                  ADD PRIMARY KEY (`roleID`);";
                                        if ($conn->query($sql) === TRUE) {
                                            echo "successfully altered elm_role";
                                            $sql = "ALTER TABLE `elm_role_pages` 
                                                      ADD KEY `role_FK` (`role_FK`,`pages_FK`),
                                                      ADD KEY `pages_FK` (`pages_FK`);";
                                            if ($conn->query($sql) === TRUE) {
                                                echo "successfully altered elm_role_pages";
                                                $sql = "ALTER TABLE `elm_setting` 
                                                          ADD PRIMARY KEY (`settingsID`);";
                                                if ($conn->query($sql) === TRUE) {
                                                    echo "successfully altered elm_setting";
                                                    $sql = "ALTER TABLE `elm_users`
                                                              ADD PRIMARY KEY (`usersID`),
                                                              ADD KEY `role_FK` (`role_FK`);";
                                                    if ($conn->query($sql) === TRUE) {
                                                        echo "successfully altered elm_users";
                                                        $sql = "ALTER TABLE `elm_version` 
                                                                  ADD PRIMARY KEY (`versionID`);";
                                                        if ($conn->query($sql) === TRUE) {
                                                            echo "successfully altered elm_version";
                                                            $sql = "ALTER TABLE `elm_role_pages`
                                                                      ADD CONSTRAINT `elm_role_pages_ibfk_1` FOREIGN KEY (`role_FK`) REFERENCES `elm_role` (`roleID`) ON DELETE CASCADE ON UPDATE CASCADE,
                                                                      ADD CONSTRAINT `elm_role_pages_ibfk_2` FOREIGN KEY (`pages_FK`) REFERENCES `elm_pages` (`pagesID`) ON DELETE CASCADE ON UPDATE CASCADE;";
                                                            if ($conn->query($sql) === TRUE) {
                                                                echo "successfully altered elm_role_pages";
                                                                $sql = "ALTER TABLE `elm_pages`
                                                                              MODIFY `pagesID` int(11) NOT NULL AUTO_INCREMENT;";
                                                                if ($conn->query($sql) === TRUE) {
                                                                    echo "successfully altered elm_users";
                                                                    $sql = "ALTER TABLE `elm_role`
                                                                                  MODIFY `roleID` int(11) NOT NULL AUTO_INCREMENT;";
                                                                    if ($conn->query($sql) === TRUE) {
                                                                        echo "successfully altered elm_pages";
                                                                        $sql = "ALTER TABLE `elm_role_pages`
                                                                                  ADD CONSTRAINT `elm_role_pages_ibfk_1` FOREIGN KEY (`role_FK`) REFERENCES `elm_role` (`roleID`) ON DELETE CASCADE ON UPDATE CASCADE,
                                                                                  ADD CONSTRAINT `elm_role_pages_ibfk_2` FOREIGN KEY (`pages_FK`) REFERENCES `elm_pages` (`pagesID`) ON DELETE CASCADE ON UPDATE CASCADE;";
                                                                        if ($conn->query($sql) === TRUE) {
                                                                            echo "successfully altered elm_role";
                                                                            $sql = "ALTER TABLE `elm_setting`
                                                                                      MODIFY `settingsID` int(11) NOT NULL AUTO_INCREMENT;";
                                                                            if ($conn->query($sql) === TRUE) {
                                                                                echo "successfully altered elm_setting";
                                                                                $sql = "ALTER TABLE `elm_users`
                                                                                          MODIFY `usersID` int(11) NOT NULL AUTO_INCREMENT;";
                                                                                if ($conn->query($sql) === TRUE) {
                                                                                    echo "successfully altered elm_users";
                                                                                    $sql = "ALTER TABLE `elm_version`
                                                                                              MODIFY `versionID` int(11) NOT NULL AUTO_INCREMENT;";
                                                                                    if ($conn->query($sql) === TRUE) {
                                                                                        echo "successfully altered elm_version";
                                                                                    } else {
                                                                                        $str = "failed to alter elm_version: " . $conn->error . "\n";
                                                                                        echo nl2br($str);
                                                                                    }
                                                                                } else {
                                                                                    $str = "failed to alter elm_users: " . $conn->error . "\n";
                                                                                    echo nl2br($str);
                                                                                }
                                                                            } else {
                                                                                $str = "failed to alter elm_setting: " . $conn->error . "\n";
                                                                                echo nl2br($str);
                                                                            }
                                                                        } else {
                                                                            $str = "failed to alter elm_role: " . $conn->error . "\n";
                                                                            echo nl2br($str);
                                                                        }
                                                                    } else {
                                                                        $str = "failed to alter elm_pages: " . $conn->error . "\n";
                                                                        echo nl2br($str);
                                                                    }
                                                                } else {
                                                                    $str = "failed to alter elm_users: " . $conn->error . "\n";
                                                                    echo nl2br($str);
                                                                }
                                                            } else {
                                                                $str = "failed to alter elm_role_pages: " . $conn->error . "\n";
                                                                echo nl2br($str);
                                                            }
                                                        } else {
                                                            $str = "failed to alter elm_version: " . $conn->error . "\n";
                                                            echo nl2br($str);
                                                        }
                                                    } else {
                                                        $str = "failed to alter elm_users: " . $conn->error . "\n";
                                                        echo nl2br($str);
                                                    }
                                                } else {
                                                    $str = "failed to alter elm_setting: " . $conn->error . "\n";
                                                    echo nl2br($str);
                                                }
                                            } else {
                                                $str = "failed to alter elm_role_pages: " . $conn->error . "\n";
                                                echo nl2br($str);
                                            }
                                        } else {
                                            $str = "failed to alter elm_role: " . $conn->error . "\n";
                                            echo nl2br($str);
                                        }
                                    } else {
                                        $str = "failed to alter elm_pages: " . $conn->error . "\n";
                                        echo nl2br($str);
                                    }
                                } else {
                                    $str = "failed to insert data into elm_role: " . $conn->error . "\n";
                                    echo nl2br($str);
                                }
                            } else {
                                $str = "failed to insert data into elm_pages: " . $conn->error . "\n";
                                echo nl2br($str);
                            }
                        } else {
                            $str = "failed to create table elm_version: " . $conn->error . "\n";
                            echo nl2br($str);
                        }
                    } else {
                        $str = "failed to create table elm_users: " . $conn->error . "\n";
                        echo nl2br($str);
                    }
                } else {
                    $str = "failed to create table elm_version: " . $conn->error . "\n";
                    echo nl2br($str);
                }
            } else {
                $str = "failed to create table elm_role_pages: " . $conn->error . "\n";
                echo nl2br($str);
            }
        } else {
            $str = "failed to create table elm_role: " . $conn->error . "\n";
            echo nl2br($str);
        }
    } else {
        $str = "failed to create table elm_pages: " . $conn->error . "\n";
        echo nl2br($str);
    }
}