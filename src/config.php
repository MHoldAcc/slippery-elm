<?php
/**
 * This variable is used to determine to what database type one wants to connect
 */
$elm_Settings_ConnectionHost = "mysql";  // value for mariaDB is "mysql", value for PostgreSQL is "pgsql"

/**
 * This variable is used to pass the location of the server, where the database is hosted, to the connection string
 */
$elm_Settings_ConnectionString = "localhost";

/**
 * This variable is used to tell the connection with which user to connect
 */
$elm_Settings_DbUser = "root"; //root

/**
 * This variable is used to tell the connection to which port to connect
 */
$elm_Settings_Port = "3306";


/**
 * This variable is used to tell the connection with which password the user uses identify himself to the server and the database
 */
$elm_Settings_DbPassword = ""; // ""

/**
 * This variable tells the connection string to which database to use
 */
$elm_Settings_Db = "slippery_elm";

/**
 * This variable is used to tel the PDO function which data source name to use, which is a concatanated sting, containing the information of $elm_Settings_ConnectionHost, $elm_Settings_ConnectionString and $elm_Settings_Db
 */
$elm_Settings_DSN = $elm_Settings_ConnectionHost.":host=".$elm_Settings_ConnectionString.":".$elm_Settings_Port.";dbname=".$elm_Settings_Db;
?>