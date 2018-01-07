<?php

class elm_User {
    public $id;
    public $name;
    public $mail;
    public $password;
    public $roleId;
}

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