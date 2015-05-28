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
interface database {

	/**
	 * Estabilises a connection to the database, and returns a new PDO.
	 */
	public function connect();

	/**
	 * Inserts the current object into the database, and sets its ID property.
	 */
	public function insert();

	/**
	 * Updates the current object in the database.
	 */
	public function update();

	/**
	 * Deletes the current object from the database.
	 */
	public function delete();

	/**
	 * Returns all (or a range of) objects in the DB
	 *
	 * @param int Optional The number of rows to return (default=all)
     * @param int Optional Return just articles in the category with this ID
	 * @param string Optional column by which to order the articles (default="publicationDate DESC")
	 * @return Array|false A two-element array : results => array, a list of objects; totalRows => Total number of objects
	 */
	public static function getList($numRows = 1000000, $categoryId = null, $order = "");

	/**
	 * Returns an object matching the given article ID
	 *
	 * @param int The article ID
	 * @return Article|false The article object, or false if the record was not found or there was a problem
	 */
	public static function getById($id);

}
?>