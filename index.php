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
require 'config.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'archive' :
        archive();
        break;

    case 'viewArticle' :
        viewArticle();
        break;

    default :
        homepage();
        break;
}

function archive() {
    $results = array();
    if (isset($_GET['categoryId']) && $_GET['categoryId']) {
        $categoryId = (int)$_GET['categoryId'];
    } elseif (isset($_GET['categoryName']) && $_GET['categoryName']) {
        $categoryName = (int)$_GET['categoryName'];
    } else {
        $categoryId = null;
        $categoryName = null;
    }
    $results['category'] = (isset($categoryId)) ? Category::getById($categoryId) : Category::getByName($categoryName);
    $data = Article::getList(100000, $results['category'] ? $results['category'] -> cat_id : null);
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];

    $data = Category::getList();
    $results['categories'] = array();

    $results['categories'] = $data['results'];
    foreach ($results['categories'] as $category) {
        $results['categories'][$category -> cat_id] = $category;
    }

    $results['pageHeading'] = $results['category'] ? $results['category'] -> name : "Article Archive";

    $results['pageTitle'] = "Article Archive | Curly Bracket";
    require (WEBSITE_FOLDER . "/archive.php");
}

function viewArticle() {
    if (!isset($_GET["articleId"]) || !$_GET["articleId"]) {
        homepage();
        return;
    }

    $results = array();
    $results['article'] = Article::getById((int)$_GET["articleId"]);
    $results['category'] = Category::getById($results['article'] -> categoryId);
    $results['pageTitle'] = $results['article'] -> title . " | Curly Bracket";
    require (WEBSITE_FOLDER . "/viewArticle.php");
}

function homepage() {
    $results = array();
    $data = Article::getList(HOMEPAGE_NUM_ARTICLES);
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];

    $results['users'] = array();
    if ($results['articles']) {
        foreach ($results['articles'] as $Article) {
            $user = User::getById($Article -> userId);
            $results['users'][$user -> userId] = $user -> userName;
        }
    }

    $data = Category::getList();
    $results['categories'] = array();

    $results['categories'] = $data['results'];
    foreach ($results['categories'] as $category) {
        $results['categories'][$category -> cat_id] = $category;
    }

    $results['pageTitle'] = "Curly Bracket";
    require (WEBSITE_FOLDER . "/home.php");
}
?>
