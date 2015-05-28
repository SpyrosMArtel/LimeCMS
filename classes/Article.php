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
 * Class to handle all article CRUD operations.
 * The database operations are done with prepared statements.
 */
class Article implements database {

	/**
	 * @var int The article ID from the database
	 */
	public $id = null;

    /**
     * @var int The user ID from the database (who written the article)
     */
    public $userId = null;

    /**
     * @var int The category ID from the database that the article belongs
     */
    public $categoryId = null;
 
    /**
     * @var int The status of the article, published, draft...
     */
    public $status = null;

	/**
	 * @var string Full title of the article
	 */
	public $title = null;

	/**
	 * @var string A short summary of the article
	 */
	public $summary = null;

	/**
	 * @var string The HTML content of the article
	 */
	public $content = null;
    /**
     * @var int When the article was published
     */
    public $publicationDate = null;

	/**
	 * Sets the object's properties using the values in the supplied array
	 *
	 * @param assoc The property values
	 */
	public function __construct($data = array()) {
		if (isset($data['id'])) {
			$this -> id = (int)$data['id'];
		}
        if (isset($data['userId'])) {
            $this -> userId = (int)$data['userId'];
        }
        if (isset($data['categoryId'])) {
            $this -> categoryId = (int)$data['categoryId'];
        }
        if (isset($data['status'])) {
            $this -> status = preg_replace("/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['status']);
        }
		if (isset($data['publicationDate'])) {
			$this -> publicationDate = (int)$data['publicationDate'];
		}
		if (isset($data['title'])) {
			$this -> title = preg_replace("/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['title']);
		}
		if (isset($data['summary'])) {
			$this -> summary = preg_replace("/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['summary']);
		}
		if (isset($data['content'])) {
			$this -> content = $data['content'];
		}
	}

	public function storeFormValues($params) {

		// Store all the parameters
		$this -> __construct($params);

		// Parse and store the publication date
		if (isset($params['publicationDate'])) {
			$publicationDate = explode('-', $params['publicationDate']);

			if (count($publicationDate) == 3) {
				list($y, $m, $d) = $publicationDate;
				$this -> publicationDate = mktime(0, 0, 0, $m, $d, $y);
			}
		}
	}

	public function connect() {

		return new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

	}

	public function insert() {

		// Does the Article object already have an ID?
		if (!is_null($this -> id)) {

			trigger_error("Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR);

		} else {
			// Insert the Article
			$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
			$sql = "INSERT INTO articles (
			         userId, categoryId, status, title,
			         summary, content, publicationDate)
			        VALUES (
			        :userId, :categoryId, :status, :title,
			        :summary, :content, :publicationDate)";

			$st = $conn -> prepare($sql);
            $st -> bindValue(":userId", $this -> userId, PDO::PARAM_INT);
			$st -> bindValue(":categoryId", $this -> categoryId, PDO::PARAM_INT);
            $st -> bindValue(":status", $this -> status, PDO::PARAM_STR);
            $st -> bindValue(":title", $this -> title, PDO::PARAM_STR);
			$st -> bindValue(":summary", $this -> summary, PDO::PARAM_STR);
			$st -> bindValue(":content", $this -> content, PDO::PARAM_STR);
            $st -> bindValue(":publicationDate", $this -> publicationDate, PDO::PARAM_INT);
			$st -> execute();
			$this -> id = $conn -> lastInsertId();
			$conn = null;
		}
	}

	public function update() {

		// Does the Article object have an ID?
		if (is_null($this -> id)) {

			trigger_error("Article::update(): Attempt to update an Article object that does not have its ID property set.", E_USER_ERROR);

		} else {
			// Update the Article
			$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
			$sql = "UPDATE articles SET
			userId = :userId,
			categoryId = :categoryId,
			status = :status,
			title = :title,
			summary = :summary,
			content = :content,
			publicationDate = :publicationDate
			WHERE id = :id";
			$st = $conn -> prepare($sql);
            $st -> bindValue(":userId", $this -> userId, PDO::PARAM_STR);
            $st -> bindValue(":categoryId", $this -> categoryId, PDO::PARAM_STR);
            $st -> bindValue(":status", $this -> status, PDO::PARAM_STR);
			$st -> bindValue(":title", $this -> title, PDO::PARAM_STR);
			$st -> bindValue(":summary", $this -> summary, PDO::PARAM_STR);
			$st -> bindValue(":content", $this -> content, PDO::PARAM_STR);
            $st -> bindValue(":publicationDate", $this -> publicationDate, PDO::PARAM_INT);
			$st -> bindValue(":id", $this -> id, PDO::PARAM_INT);
			$st -> execute();
			$conn = null;
		}
	}

	public function delete() {

		// Does the Article object have an ID?
		if (is_null($this -> id)) {

			trigger_error("Article::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR);

		} else {
			// Delete the Article
			$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
			$st = $conn -> prepare("DELETE FROM articles WHERE id = :id LIMIT 1");
			$st -> bindValue(":id", $this -> id, PDO::PARAM_INT);
			$st -> execute();
			$conn = null;
		}
	}

	public static function getList($numRows = 1000000, $categoryId = null,
	                               $order = "publicationDate DESC") {
		$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $categoryClause = $categoryId ? "WHERE categoryId = :categoryId" : "";

		$sql = "SELECT SQL_CALC_FOUND_ROWS *, publicationDate
		        AS publicationDate FROM articles $categoryClause
                ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";

		$st = $conn -> prepare($sql);
		$st -> bindValue(":numRows", $numRows, PDO::PARAM_INT);
        if ($categoryId) {
            $st -> bindValue(":categoryId", $categoryId, PDO::PARAM_INT);
        }
		$st -> execute();
		$list = array();

		while ($row = $st -> fetch()) {
			$article = new Article($row);
			$list[] = $article;
		}

		// Now get the total number of articles that matched the criteria
		$sql = "SELECT FOUND_ROWS() AS totalRows";
		$totalRows = $conn -> query($sql) -> fetch();
		$conn = null;
		return ( array("results" => $list, "totalRows" => $totalRows[0]));
	}

	public static function getById($id) {
		$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
		$sql = "SELECT *, publicationDate AS publicationDate FROM articles WHERE id = :id";
		$st = $conn -> prepare($sql);
		$st -> bindValue(":id", $id, PDO::PARAM_INT);
		$st -> execute();
		$row = $st -> fetch();
		$conn = null;

		if ($row) {
			return new Article($row);
		}
	}

}
?>