<?php
/*
Copyright (c) Spyridon Marinis Artelaris, All rights reserved.

This file is part of LimeCMS.

LimeCMS is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 3.0 of the License, or (at your option) any later version.

LimeCMS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with LimeCMS.
*/
require_once 'database.php';

/**
 * Class to handle all user CRUD operations.
 * The database operations are done with prepared statements.
 */
class User implements database {

    /**
     * @var int The user's id from the database
     */
    public $userId = null;
    /**
     * @var string The user's first name
     */
    public $firstName = null;
    /**
     * @var string The user's last name
     */
    public $lastName = null;
    /**
     * @var string The user's username
     */
    public $userName = null;
    /**
     * @var string The user's password
     */
    public $passCode = null;
    /**
     * @var string The user's role
     */
    public $access = null;
    /**
     * @var int When the user has joined (unix timestamp)
     */
    public $registered = null;
    /**
     * @var int password hash cost
     */
    private $cost = 12;

    public function __construct($data = array()) {
        if (isset($data['userId'])) {
            $this -> userId = (int)$data['userId'];
        }
        if (isset($data['firstname'])) {
            $this -> firstName = $data['firstname'];
        }
        if (isset($data['lastname'])) {
            $this -> lastName = $data['lastname'];
        }
        if (isset($data['username'])) {
            $this -> userName = preg_replace("/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['username']);
        }
        if (isset($data['passcode'])) {
            $this -> passCode = $data['passcode'];
        }
        if (isset($data['access'])) {
            $this -> access = $data['access'];
        }
        if (isset($data['registered'])) {
            if (empty($data['registered'])) {
                $this -> registered = time();
            } else {
                $this -> registered = (int)$data['registered'];
            }
        }
    }

    public function genHash($plaintext) {
        return (password_hash($plaintext, PASSWORD_BCRYPT, array('cost' => $this -> cost)));
    }

    public function storeUserForm($params) {
        // Store all the parameters
        // TODO find a way to make sure that password is a hash
        $this -> __construct($params);
    }

    public function userSince() {
        return date("F j, Y - g:i a", $this -> registered);
    }

    public function connect() {
        return PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    }

    public function insert() {
        // Does the User object already have an ID?
        if (!empty($this -> userId)) {// was (!is_null)

            trigger_error("User::insert(): Attempt to insert an User object that already has its ID property set (to $this->userId).", E_USER_ERROR);

        } else {
            // Insert the User
            $conn = User::connect();
            // $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $sql = "INSERT INTO users (
                    firstname, lastname, username,
                    passcode, access, registered
                    )
                    VALUES (
                    :firstname, :lastname, :username,
                    :passcode, :access, :registered
                    )";
            $st = $conn -> prepare($sql);
            $st -> bindValue(":registered", $this -> registered, PDO::PARAM_INT);
            $st -> bindValue(":firstname", $this -> firstName, PDO::PARAM_STR);
            $st -> bindValue(":lastname", $this -> lastName, PDO::PARAM_STR);
            $st -> bindValue(":username", $this -> userName, PDO::PARAM_STR);
            $st -> bindValue(":passcode", $this -> passCode, PDO::PARAM_STR);
            $st -> bindValue(":access", $this -> access, PDO::PARAM_STR);
            $st -> execute();
            $this -> userId = $conn -> lastInsertId();
            $conn = null;
        }
    }

    public function update() {
        // Does the User object already have an ID?
        if (empty($this -> userId)) {// was (is_null)

            trigger_error("User::update(): Attempt to update a User object that does not have its ID property set.", E_USER_ERROR);

        } else {
            // Insert the User
            $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $sql = "UPDATE users SET  firstname = :firstname,
                    lastname = :lastname, username = :username,
                    passcode = :passcode, access = :access
                    registered = :registered WHERE userId = :id";
            $st = $conn -> prepare($sql);
            $st -> bindValue(":registered", $this -> registered, PDO::PARAM_INT);
            $st -> bindValue(":firstname", $this -> firstName, PDO::PARAM_STR);
            $st -> bindValue(":lastname", $this -> lastName, PDO::PARAM_STR);
            $st -> bindValue(":username", $this -> userName, PDO::PARAM_STR);
            $st -> bindValue(":passcode", $this -> passCode, PDO::PARAM_STR);
            $st -> bindValue(":access", $this -> access, PDO::PARAM_STR);
            $st -> execute();
            $this -> userId = $conn -> lastInsertId();
            $conn = null;
        }
    }

    public function delete() {
        // Does the User object have an ID?
        if (empty($this -> userId)) {// was (!is_null)

            trigger_error("User::delete(): Attempt to delete a User object that does not have its ID property set.", E_USER_ERROR);

        } else {
            // Delete the User
            $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $st = $conn -> prepare("DELETE FROM users WHERE userId = :id LIMIT 1");
            $st -> bindValue(":id", $this -> userId, PDO::PARAM_INT);
            $st -> execute();
            $conn = null;
        }
    }

    /**
     * To be used by the admin in order to manage accounts
     */
    public static function getList($numRows = 1000000, $categoryId = null, $order = "username ASC") {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM users
                ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";

        $st = $conn -> prepare($sql);
        $st -> bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st -> execute();
        $list = array();

        while ($row = $st -> fetch()) {
            $user = new User($row);
            $list[] = $user;
        }

        // Now get the total number of categories that matched the criteria
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn -> query($sql) -> fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]));
    }

    public static function getById($id) {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT *, registered AS registered FROM users WHERE userId = :id";
        $st = $conn -> prepare($sql);
        $st -> bindValue(":id", $id, PDO::PARAM_INT);
        $st -> execute();
        $row = $st -> fetch();
        $conn = null;

        if ($row) {

            return new User($row);

        }
    }

    /**
     * Finds a user in the database by his/her username
     */
    public static function getByUsername($username) {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $st = $conn -> prepare($sql);
        $st -> bindValue(":username", $username, PDO::PARAM_STR);
        $st -> execute();
        $row = $st -> fetch();
        $conn = null;

        if ($row) {

            return new User($row);

        }
    }

}
?>