<?php
//Here all objects used in slippery elm are defined

/**
 * Class elm_User
 * This class is used to store user data
 */
class elm_User {
    public $id;
    public $name;
    public $mail;
    public $password;
    public $roleId;
}

/**
 * Class elm_Page
 * This class is used to store page data
 */
class elm_Page {
    public $id;
    public $name;
    public $content;
    public $parentPage;
    public $keywords;
    public $sorting;
    public $created;
    public $modified;
    public $creatorId;
    public $modifierId;
}

?>