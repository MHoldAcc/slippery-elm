<?php
/**
 * Created by PhpStorm.
 * User: Drake
 * Date: 28.12.2017
 * Time: 10:17
 * this function crates the table structure of the database
 */
function initializePostgresqlDB($conn){
    $sql = $conn->prepare("CREATE TABLE public.elm_pages (
                                      pagesID SERIAL NOT NULL,
                                      pagesName CHARACTER(255),
                                      pagesContent TEXT,
                                      pagesParentPage CHARACTER(255),
                                      pagesCreaterID INTEGER,
                                      pagesModifierID INTEGER,
                                      pagesKeywords CHARACTER(255),
                                      pagesSorting char,
                                      pagesIsHome BOOLEAN,
                                      pagesCreated TIME DEFAULT CURRENT_TIMESTAMP,
                                      pagesModified TIME DEFAULT CURRENT_TIMESTAMP,
                                      CONSTRAINT elm_pages_pkey  PRIMARY KEY (pagesID));");
    $sql->execute();

    $sql = $conn->prepare("INSERT INTO public.elm_pages (pagesID, pagesName, pagesContent, pagesParentPage, pagesKeywords, pagesSorting, pagesIsHome, pagesCreated, pagesModified, pagesCreaterID, pagesModifierID) 
                                      VALUES
                                      (1, 'HOME', '', '', '', 1, TRUE,'2017-12-15 09:40:19', '2017-12-15 09:40:19', 0, 0);");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE public.elm_role (
                                      roleID SERIAL NOT NULL,
                                      roleName CHARACTER(255) NOT NULL,
                                      roleDescription TEXT,
                                      roleCreaterID INTEGER,
                                      roleModifierID INTEGER,
                                      roleCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                      roleModified TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                      CONSTRAINT elm_role_pkey PRIMARY KEY (roleID));");
    $sql->execute();

    $sql = $conn->prepare("INSERT INTO public.elm_role (roleID, roleName, roleDescription, roleCreated, roleModified, roleCreaterID, roleModifierID) 
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
                                          REFERENCES public.elm_pages (pagesID) MATCH SIMPLE
                                          ON UPDATE NO ACTION ON DELETE NO ACTION,
                                      CONSTRAINT elm_role_pages_role_FK_fkey FOREIGN KEY (role_FK)
                                          REFERENCES public.elm_role (roleID) MATCH SIMPLE
                                          ON UPDATE NO ACTION ON DELETE NO ACTION);");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE public.elm_setting (
                                      settingsID SERIAL NOT NULL,
                                      settingsKey TEXT,
                                      settingsValue TEXT,
                                      settingsModified INTEGER,
                                      settingsModifierID INTEGER,
                                      CONSTRAINT elm_setting_pkey PRIMARY KEY (settingsID));");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE public.elm_users (
                                      usersID SERIAL NOT NULL,
                                      username CHARACTER(255),
                                      email CHARACTER(255),
                                      password CHARACTER(255),
                                      isActive BOOLEAN,
                                      usersCreated TIME DEFAULT CURRENT_TIMESTAMP,
                                      usersModified TIME DEFAULT CURRENT_TIMESTAMP,
                                      usersCreaterID INTEGER,
                                      usersModifierID INTEGER,
                                      role_FK INTEGER NOT NULL,
                                      CONSTRAINT elm_users_pkey PRIMARY KEY (usersID),
                                      CONSTRAINT elm_users_role_FK_fkey FOREIGN KEY (role_FK)
                                          REFERENCES public.elm_role (roleID) MATCH SIMPLE
                                          ON UPDATE NO ACTION ON DELETE NO ACTION);");
    $sql->execute();

    $sql = $conn->prepare("CREATE TABLE public.elm_version (
                                      versionID SERIAL NOT NULL,
                                      databaseVersion CHARACTER(255),
                                      scriptName CHARACTER(255),
                                      updated TIME DEFAULT CURRENT_TIMESTAMP,
                                      CONSTRAINT elm_version_pkey PRIMARY KEY (versionID));");
    $sql->execute();

}