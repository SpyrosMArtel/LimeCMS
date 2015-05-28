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
 * Class to handle all category CRUD operations.
 * The database operations are done with prepared statements.
 */
class Category implements database {

    /**
     * @var int The user's id from the database
     */
    public $cat_id = null;
    /**
     * @var string The user's first name
     */
    public $name = null;
    /**
     * @var string The user's last name
     */
    public $description = null;

    public function __construct($data = array()) {
        if (isset($data['cat_id'])) {
            $this -> cat_id = (int) $data['cat_id'];
        }
        if (isset($data['name'])) {
            $this -> name = $data['name'];
        }
        if (isset($data['description'])) {
            $this -> description = $data['description'];
        }
    }

    public function addCategory($params) {

        // Store all the parameters
        $this -> __construct($params);

    }

    public function connect() {

        return new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    }

    public function insert() {
        // Does the User object already have an ID?
        if (!is_null($this -> cat_id)) {

            trigger_error("User::insert(): Attempt to insert an Category object that already has its ID property set (to $this->cat_id).", E_USER_ERROR);

        } else {
            // Insert the User
            $conn = User::connect();
            // $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $sql = "INSERT INTO categories (
                    name, description
                    )
                    VALUES (
                    :name, :description
                    )";
            $st = $conn -> prepare($sql);
            $st -> bindValue(":name", $this -> name, PDO::PARAM_INT);
            $st -> bindValue(":description", $this -> description, PDO::PARAM_STR);
            $st -> execute();
            $this -> cat_id = $conn -> lastInsertId();
            $conn = null;
        }
    }

    public function update() {
        // Does the User object already have an ID?
        if (!is_null($this -> cat_id)) {

            trigger_error("User::update(): Attempt to update a Category object that does not have its ID property set.", E_USER_ERROR);

        } else {
            // Insert the User
            $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $sql = "UPDATE categories SET name = :name,
                    description = :description WHERE cat_id = :id";
            $st = $conn -> prepare($sql);
            $st -> bindValue(":name", $this -> registered, PDO::PARAM_INT);
            $st -> bindValue(":description", $this -> firstName, PDO::PARAM_STR);
            $st -> execute();
            $this -> cat_id = $conn -> lastInsertId();
            $conn = null;
        }
    }

    public function delete() {
        // Does the User object have an ID?
        if (is_null($this -> cat_id)) {

            trigger_error("User::delete(): Attempt to delete a Category object that does not have its ID property set.", E_USER_ERROR);

        } else {
            // Delete the User
            $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $st = $conn -> prepare("DELETE FROM categories WHERE cat_id = :id LIMIT 1");
            $st -> bindValue(":id", $this -> cat_id, PDO::PARAM_INT);
            $st -> execute();
            $conn = null;
        }
    }

    public static function getById($id) {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM categories WHERE cat_id = :id";
        $st = $conn -> prepare($sql);
        $st -> bindValue(":id", $id, PDO::PARAM_INT);
        $st -> execute();
        $row = $st -> fetch();
        $conn = null;

        if ($row) return new Category($row);
    }

    public static function getByName($cat_name) {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM categories WHERE name = :name";
        $st = $conn -> prepare($sql);
        $st -> bindValue(":name", $cat_name, PDO::PARAM_INT);
        $st -> execute();
        $row = $st -> fetch();
        $conn = null;

        if ($row) return new Category($row);
    }

    public static function getList($numRows = 1000000, $categoryId = null,
                                   $order = "name ASC") {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM categories
                ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";

        $st = $conn -> prepare($sql);
        $st -> bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st -> execute();
        $list = array();

        while ($row = $st -> fetch()) {
            $category = new Category($row);
            $list[] = $category;
        }

        // Now get the total number of categories that matched the criteria
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn -> query($sql) -> fetch();
        $conn = null;
        return ( array("results" => $list, "totalRows" => $totalRows[0]));
    }
}
?>