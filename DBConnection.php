<?php
if(!is_dir(__DIR__.'./db'))
    mkdir(__DIR__.'./db');
if(!defined('db_file')) define('db_file',__DIR__.'./db/recipe_db.db');
function my_udf_md5($string) {
    return md5($string);
}

Class DBConnection extends SQLite3{
    protected $db;
    function __construct(){
        $this->open(db_file);
        $this->createFunction('md5', 'my_udf_md5');
        $this->exec("PRAGMA foreign_keys = ON;");

        $this->exec("CREATE TABLE IF NOT EXISTS `admin_list` (
            `admin_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `fullname` INTEGER NOT NULL,
            `username` TEXT NOT NULL,
            `password` TEXT NOT NULL,
            `type` INTEGER NOT NULL Default 1,
            `status` INTEGER NOT NULL Default 1,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"); 

        //User Comment
        // Type = [ 1 = Administrator, 2 = Cashier]
        // Status = [ 1 = Active, 2 = Inactive]

        $this->exec("CREATE TABLE IF NOT EXISTS `category_list` (
            `category_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `name` TEXT NOT NULL,
            `description` TEXT NOT NULL 
        ) ");
        $this->exec("CREATE TABLE IF NOT EXISTS `user_list` (
            `user_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `fullname` INTEGER NOT NULL,
            `username` TEXT NOT NULL,
            `password` TEXT NOT NULL,
            `status` INTEGER NOT NULL Default 1,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"); 

        $this->exec("CREATE TABLE IF NOT EXISTS `recipe_list` (
            `recipe_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `category_id` INTEGER NOT NULL,
            `user_id` INTEGER NOT NULL,
            `title` TEXT NOT NULL,
            `description` TEXT NOT NULL,
            `ingredients` TEXT NOT NULL,
            `step` TEXT NOT NULL,
            `other_info` TEXT NOT NULL,
            `status` INTEGER NOT NULL,
            `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`category_id`) REFERENCES `category_list`(`category_id`) ON DELETE CASCADE
            FOREIGN KEY(`user_id`) REFERENCES `user_list`(`user_id`) ON DELETE CASCADE
        ) ");

        $this->exec("CREATE TABLE IF NOT EXISTS `comment_list` (
            `comment_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `recipe_id` TEXT NOT NULL,
            `user_id` TEXT NOT NULL,
            `message` TEXT NOT NULL,
            `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`recipe_id`) REFERENCES `recipe_list`(`recipe_id`) ON DELETE CASCADE
            FOREIGN KEY(`user_id`) REFERENCES `user_list`(`user_id`) ON DELETE CASCADE
        ) ");

        
        // $this->exec("CREATE TRIGGER IF NOT EXISTS updatedTime_prod AFTER UPDATE on `vacancy_list`
        // BEGIN
        //     UPDATE `vacancy_list` SET date_updated = CURRENT_TIMESTAMP where vacancy_id = vacancy_id;
        // END
        // ");

        $this->exec("INSERT or IGNORE INTO `admin_list` VALUES (1,'Administrator','admin',md5('admin123'),1,1, CURRENT_TIMESTAMP)");
        $this->exec("INSERT or IGNORE INTO `user_list` VALUES (1,'Try My Recipe Mgt','mgt',md5('mgt123'),1, CURRENT_TIMESTAMP)");

    }
    function __destruct(){
         $this->close();
    }
}

$conn = new DBConnection();