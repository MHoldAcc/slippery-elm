<?php
/**
 * Created by PhpStorm.
 * User: Drake
 * Date: 28.12.2017
 * Time: 10:17
 * this function crates the table structure of the database
 */
function initializePostgresqlDB(){
    GLOBAL $conn;
    $sql = $conn->prepare("CREATE TABLE public.elm_pages(
                                      pageID INTEGER NOT NULL DEFAULT nextval('elm_pages_pageID_seq'::regclass),
                                      pagename CHARACTER(255),
                                      pageContent TEXT,
                                      parentPage CHARACTER(255),
                                      pagesCreaterID INTEGER NOT NULL DEFAULT nextval(elm_pages_pagesCreaterID_seq::regclass),
                                      pagesModifierID INTEGER NOT NULL DEFAULT nextval(elm_pages_pagesModifierID_seq::regclass),
                                      keywords CHARACTER(255),
                                      pageSorting char,
                                      pagesCreated TIME WITH TIME zone DEFAULT CURRENT_TIMESTAMP,
                                      pagesModified TIME WITH TIME zone DEFAULT CURRENT_TIMESTAMP,
                                      CONSTRAINT elm_pages_pkey PRIMARY KEY (pageID)
                                    ) WITH ( OIDS=FALSE);");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE public.elm_pages 
                                      OWNER TO elm;");
    $sql->execute();

    $sql = $conn->prepare("INSERT INTO elm_pages (pagesID, pagesName, pagesContent, pagesParentPage, pagesKeywords, pagesSorting, pagesIsHome, pagesCreated, pagesModified, pagesCreaterID, pagesModifierID) 
                                      VALUES
                                      (1, 'HOME', '', '', '', 1, 1,'2017-12-15 09:40:19', '2017-12-15 09:40:19', 0, 0)");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE public.elm_role
                                    (
                                      roleID INTEGER NOT NULL DEFAULT nextval(elm_role_roleID_seq::regclass),
                                      roleName CHARACTER(255) NOT NULL,
                                      description TEXT,
                                      roleCreated TIMESTAMP WITH TIME zone DEFAULT CURRENT_TIMESTAMP,
                                      roleModified TIMESTAMP WITH TIME zone DEFAULT CURRENT_TIMESTAMP,
                                      CONSTRAINT elm_role_pkey PRIMARY KEY (roleID)
                                    ) WITH (                                    
                                      OIDS=FALSE
                                    );");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE public.elm_role 
                                      OWNER TO elm;");
    $sql->execute();

    $sql = $conn->prepare("INSERT INTO `elm_role` (`roleID`, `roleName`, `roleDescription`, `roleCreated`, `roleModified`, `roleCreaterID`, `roleModifierID`) VALUES
            (1, 'admin', 'administrator', '2017-12-15 09:42:04', '2017-12-15 09:42:04', 0, 0),
            (2, 'user', 'user', '2017-12-15 09:42:04', '2017-12-15 09:42:04', 0, 0);");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE public.elm_role_pages (
                                      role_FK INTEGER NOT NULL DEFAULT nextval(elm_role_pages_role_FK_seq::regclass),
                                      pages_FK INTEGER NOT NULL DEFAULT nextval(elm_role_pages_pages_FK_seq::regclass),
                                      canEdit INTEGER NOT NULL DEFAULT nextval(elm_role_pages_canEdit_seq::regclass),
                                      canView INTEGER NOT NULL DEFAULT nextval(elm_role_pages_canView_seq::regclass),
                                      canDelete INTEGER NOT NULL DEFAULT nextval(elm_role_pages_canDelete_seq::regclass),
                                      CONSTRAINT elm_role_pages_pages_FK_fkey FOREIGN KEY (pages_FK)
                                          REFERENCES public.elm_pages (pageID) MATCH SIMPLE
                                          ON UPDATE NO ACTION ON DELETE NO ACTION,
                                      CONSTRAINT elm_role_pages_role_FK_fkey FOREIGN KEY (role_FK)
                                          REFERENCES public.elm_role (roleID) MATCH SIMPLE
                                          ON UPDATE NO ACTION ON DELETE NO ACTION
                                    ) WITH ( OIDS=FALSE );");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE public.elm_role_pages 
                                      OWNER TO elm;");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE public.elm_setting (
                                      settingsID INTEGER NOT NULL DEFAULT nextval(elm_setting_settingsID_seq::regclass),
                                      settingsKey TEXT,
                                      settingsValue TEXT,
                                      settingsModified INTEGER NOT NULL DEFAULT nextval(elm_setting_settingsModified_seq::regclass),
                                      settingsModifierID INTEGER NOT NULL DEFAULT nextval(elm_setting_settingsModifierID_seq::regclass),
                                      CONSTRAINT elm_setting_pkey PRIMARY KEY (settingsID)
                                    ) WITH ( OIDS=FALSE );");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE public.elm_setting 
                                      OWNER TO elm;");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE public.elm_users (
                                      usersID INTEGER NOT NULL DEFAULT nextval(elm_users_usersID_seq::regclass),
                                      username CHARACTER(255),
                                      email CHARACTER(255),
                                      password CHARACTER(255),
                                      isActive BOOLEAN,
                                      usersCreated TIME WITH TIME zone DEFAULT CURRENT_TIMESTAMP,
                                      usersModified TIME WITH TIME zone DEFAULT CURRENT_TIMESTAMP,
                                      usersCreaterID INTEGER NOT NULL DEFAULT nextval(elm_users_usersCreaterID_seq::regclass),
                                      usersModifierID INTEGER NOT NULL DEFAULT nextval(elm_users_usersModifierID_seq::regclass),
                                      role_FK INTEGER NOT NULL DEFAULT nextval(elm_users_role_FK_seq::regclass),
                                      CONSTRAINT elm_users_pkey PRIMARY KEY (usersID),
                                      CONSTRAINT elm_users_role_FK_fkey FOREIGN KEY (role_FK)
                                          REFERENCES public.elm_role (roleID) MATCH SIMPLE
                                          ON UPDATE NO ACTION ON DELETE NO ACTION
                                     ) WITH ( OIDS=FALSE );");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE public.elm_users 
                                      OWNER TO elm;");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE public.elm_version (
                                      versionID INTEGER NOT NULL DEFAULT nextval(elm_version_versionID_seq::regclass),
                                      databaseVersion CHARACTER(255),
                                      scriptName CHARACTER(255),
                                      updated TIME WITH TIME zone DEFAULT CURRENT_TIMESTAMP,
                                      CONSTRAINT elm_version_pkey PRIMARY KEY (versionID)
                                    ) WITH ( OIDS=FALSE );");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE public.elm_version 
                                      OWNER TO elm;");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_pages`
              ADD PRIMARY KEY (`pagesID`);");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_role`
              ADD PRIMARY KEY (`roleID`);");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_role_pages`
              ADD KEY `role_FK` (`role_FK`,`pages_FK`),
              ADD KEY `pages_FK` (`pages_FK`);");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_setting`
              ADD PRIMARY KEY (`settingsID`);");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_users`
              ADD PRIMARY KEY (`usersID`),
              ADD KEY `role_FK` (`role_FK`);");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_version`
              ADD PRIMARY KEY (`versionID`);");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_pages`
              MODIFY `pagesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_role`
              MODIFY `roleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_setting`
              MODIFY `settingsID` int(11) NOT NULL AUTO_INCREMENT;");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_users`
              MODIFY `usersID` int(11) NOT NULL AUTO_INCREMENT;");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_version`
              MODIFY `versionID` int(11) NOT NULL AUTO_INCREMENT;");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_role_pages`
              ADD CONSTRAINT `elm_role_pages_ibfk_1` FOREIGN KEY (`role_FK`) REFERENCES `elm_role` (`roleID`) ON DELETE CASCADE ON UPDATE CASCADE,
              ADD CONSTRAINT `elm_role_pages_ibfk_2` FOREIGN KEY (`pages_FK`) REFERENCES `elm_pages` (`pagesID`) ON DELETE CASCADE ON UPDATE CASCADE;");
    $sql->execute();

    $sql = $conn->prepare("ALTER TABLE `elm_users`
              ADD CONSTRAINT `elm_users_ibfk_1` FOREIGN KEY (`role_FK`) REFERENCES `elm_role` (`roleID`) ON DELETE CASCADE ON UPDATE CASCADE;");
    $sql->execute();
}