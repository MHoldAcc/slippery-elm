<?php
/**
 * created by PhpStorm.
 * User: Drake
 * Date: 28.12.2017
 * Time: 10:17
 * this function crates the table structure of the database
 */
function initializeMariaDB(){
    GLOBAL $conn;
    $sql = $conn->prepare("CREATE TABLE `elm_pages` (
              `pagesid` int(11) NOT NULL,
              `pagesname` varchar(255) NOT NULL,
              `pagescontent` text NOT NULL,
              `pagesparentpage` varchar(255) NOT NULL,
              `pageskeywords` varchar(255) NOT NULL,
              `pagessorting` int(11) NOT NULL,
              `pagesishome` tinyint(1),
              `pagescreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `pagesmodified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `pagescreaterid` int(11) NOT NULL,
              `pagesmodifierid` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    $sql->execute();

    $sql = $conn->prepare("INSERT INTO `elm_pages` (`pagesid`, `pagesname`, `pagescontent`, `pagesparentpage`, `pageskeywords`, `pagessorting`, `pagesishome`, `pagescreated`, `pagesmodified`, `pagescreaterid`, `pagesmodifierid`) VALUES
            (1, 'HOME', '', '', '', 1, 1,'2017-12-15 09:40:19', '2017-12-15 09:40:19', 0, 0)");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE `elm_role` (
              `roleid` int(11) NOT NULL,
              `roleName` varchar(255) NOT NULL,
              `roleDescription` text NOT NULL,
              `rolecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `rolemodified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `rolecreaterid` int(11) NOT NULL,
              `rolemodifierid` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    $sql->execute();

    $sql = $conn->prepare("INSERT INTO `elm_role` (`roleid`, `rolename`, `roledescription`, `rolecreated`, `rolemodified`, `rolecreaterid`, `rolemodifierid`) VALUES
            (1, 'admin', 'administrator', '2017-12-15 09:42:04', '2017-12-15 09:42:04', 0, 0),
            (2, 'user', 'user', '2017-12-15 09:42:04', '2017-12-15 09:42:04', 0, 0);");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE `elm_role_pages` (
              `role_fk` int(11) NOT NULL,
              `pages_fk` int(11) NOT NULL,
              `canedit` int(11) NOT NULL,
              `canview` int(11) NOT NULL,
              `candelete` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE `elm_setting` (
             `settingsid` int(11) NOT NULL,
             `settingsKey` varchar(255) NOT NULL,
             `settingsValue` varchar(255) NOT NULL,
             `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
             `settingsmodifierid` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE `elm_users` (
              `usersid` int(11) NOT NULL,
              `username` varchar(255) NOT NULL,
              `password` varchar(255) NOT NULL,
              `email` varchar(255) NOT NULL,
              `isactive` tinyint(1),
              `userscreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `usersmodified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `userscreaterid` int(11) NOT NULL,
              `usersmodifierid` int(11) NOT NULL,
              `role_fk` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE `elm_version` (
              `versionid` int(11) NOT NULL,
              `databaseVersion` varchar(255) NOT NULL,
              `scriptName` varchar(255) NOT NULL,
              `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_pages`
              ADD PRIMARY KEY (`pagesid`);");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_role`
              ADD PRIMARY KEY (`roleid`);");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_role_pages`
              ADD KEY `role_fk` (`role_fk`,`pages_fk`),
              ADD KEY `pages_fk` (`pages_fk`);");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_setting`
              ADD PRIMARY KEY (`settingsid`);");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_users`
              ADD PRIMARY KEY (`usersid`),
              ADD KEY `role_fk` (`role_fk`);");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_version`
              ADD PRIMARY KEY (`versionid`);");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_pages`
              MODIFY `pagesid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_role`
              MODIFY `roleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_setting`
              MODIFY `settingsid` int(11) NOT NULL AUTO_INCREMENT;");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_users`
              MODIFY `usersid` int(11) NOT NULL AUTO_INCREMENT;");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_version`
              MODIFY `versionid` int(11) NOT NULL AUTO_INCREMENT;");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_role_pages`
              ADD CONSTRAINT `elm_role_pages_ibfk_1` FOREIGN KEY (`role_fk`) REFERENCES `elm_role` (`roleid`) ON DELETE CASCADE ON UPDATE CASCADE,
              ADD CONSTRAINT `elm_role_pages_ibfk_2` FOREIGN KEY (`pages_fk`) REFERENCES `elm_pages` (`pagesid`) ON DELETE CASCADE ON UPDATE CASCADE;");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_users`
              ADD CONSTRAINT `elm_users_ibfk_1` FOREIGN KEY (`role_fk`) REFERENCES `elm_role` (`roleid`) ON DELETE CASCADE ON UPDATE CASCADE;");
    $sql->execute();
}