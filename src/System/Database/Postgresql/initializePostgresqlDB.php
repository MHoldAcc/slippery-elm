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
    $sql = $conn->prepare("CREATE TABLE public.elm_pages (
                                      pageID INTEGER NOT NULL,
                                      pagename CHARACTER(255),
                                      pageContent TEXT,
                                      parentPage CHARACTER(255),
                                      pagesCreaterID INTEGER NOT NULL,
                                      pagesModifierID INTEGER NOT NULL,
                                      keywords CHARACTER(255),
                                      pageSorting char,
                                      pagesIsHome BOOLEAN,
                                      pagesCreated TIME DEFAULT CURRENT_TIMESTAMP,
                                      pagesModified TIME DEFAULT CURRENT_TIMESTAMP,
                                      CONSTRAINT elm_pages_pkey PRIMARY KEY (pageID));");
    $sql->execute();

    $sql = $conn->prepare("INSERT INTO elm_pages (pagesID, pagesName, pagesContent, pagesParentPage, pagesKeywords, pagesSorting, pagesIsHome, pagesCreated, pagesModified, pagesCreaterID, pagesModifierID) 
                                      VALUES
                                      (1, 'HOME', '', '', '', 1, 1,'2017-12-15 09:40:19', '2017-12-15 09:40:19', 0, 0)");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE public.elm_role (
                                      roleID INTEGER NOT NULL,
                                      roleName CHARACTER(255) NOT NULL,
                                      description TEXT,
                                      roleCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                      roleModified TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                      CONSTRAINT elm_role_pkey PRIMARY KEY (roleID));");
    $sql->execute();

    $sql = $conn->prepare("INSERT INTO elm_role (roleID, roleName, roleDescription, roleCreated, roleModified, roleCreaterID, roleModifierID) 
                                      VALUES
                                        (1, 'admin', 'administrator', '2017-12-15 09:42:04', '2017-12-15 09:42:04', 0, 0),
                                        (2, 'user', 'user', '2017-12-15 09:42:04', '2017-12-15 09:42:04', 0, 0);");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE public.elm_role_pages (
                                      role_FK INTEGER NOT NULL,
                                      pages_FK INTEGER NOT NULL,
                                      canEdit INTEGER NOT NULL,
                                      canView INTEGER NOT NULL,
                                      canDelete INTEGER NOT NULL,
                                      CONSTRAINT elm_role_pages_pages_FK_fkey FOREIGN KEY (pages_FK)
                                          REFERENCES public.elm_pages (pageID) MATCH SIMPLE
                                          ON UPDATE NO ACTION ON DELETE NO ACTION,
                                      CONSTRAINT elm_role_pages_role_FK_fkey FOREIGN KEY (role_FK)
                                          REFERENCES public.elm_role (roleID) MATCH SIMPLE
                                          ON UPDATE NO ACTION ON DELETE NO ACTION);");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE public.elm_setting (
                                      settingsID INTEGER NOT NULL,
                                      settingsKey TEXT,
                                      settingsValue TEXT,
                                      settingsModified INTEGER NOT NULL,
                                      settingsModifierID INTEGER NOT NULL,
                                      CONSTRAINT elm_setting_pkey PRIMARY KEY (settingsID));");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE public.elm_users (
                                      usersID INTEGER NOT NULL,
                                      username CHARACTER(255),
                                      email CHARACTER(255),
                                      password CHARACTER(255),
                                      isActive BOOLEAN,
                                      usersCreated TIME DEFAULT CURRENT_TIMESTAMP,
                                      usersModified TIME DEFAULT CURRENT_TIMESTAMP,
                                      usersCreaterID INTEGER NOT NULL,
                                      usersModifierID INTEGER NOT NULL,
                                      role_FK INTEGER NOT NULL,
                                      CONSTRAINT elm_users_pkey PRIMARY KEY (usersID),
                                      CONSTRAINT elm_users_role_FK_fkey FOREIGN KEY (role_FK)
                                          REFERENCES public.elm_role (roleID) MATCH SIMPLE
                                          ON UPDATE NO ACTION ON DELETE NO ACTION);");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE public.elm_version (
                                      versionID INTEGER NOT NULL,
                                      databaseVersion CHARACTER(255),
                                      scriptName CHARACTER(255),
                                      updated TIME DEFAULT CURRENT_TIMESTAMP,
                                      CONSTRAINT elm_version_pkey PRIMARY KEY (versionID));");
    $sql->execute();

}