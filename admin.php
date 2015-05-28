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
//adm1nP4s5! - not a good practice...
/**
 * admin.php handles all the logic of routing an authenticated user around the
 * CMS admin panel.
 */
/*TODO: replace superglobals like filterinput(INPUT_POST, 'action', ...) etc...
more at: http://php.net/manual/en/function.filter-input.php
 *  */

require ('config.php');

session_start();
$action = isset($_GET['action']) ? $_GET['action'] : '';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

/* is the user authenticated? no? present the login page */
if ($action != 'login' && $action != 'logout' && !$username) {
    login();
    exit ;
}

/* basic routing for authenticated users */
switch ( $action ) {
    case 'login' :
        login();
        break;
    case 'logout' :
        logout();
        break;
    case 'newArticle' :
        newArticle();
        break;
    case 'editArticle' :
        editArticle();
        break;
    case 'deleteArticle' :
        deleteArticle();
        break;
    case 'listCategories' :
        listCategories();
        break;
    case 'editCategory' :
        editCategory();
        break;
    case 'newCategory' :
        newCategory();
        break;
    case 'deleteCategory' :
        deleteCategory();
        break;
    case 'manageUsers' :
        manageUsers();
        break;
    case 'newUser' :
        newUser();
        break;
    case 'editUser' :
        editUser();
        break;
    case 'deleteUser' :
        editUser();
        break;
    case 'listArticles' :
        listArticles();
        break;
    default :
        welcome();
        break;
}

/**
 * displays the admin panel page
 */
function welcome() {
    $results = array();
    $results['pageTitle'] = 'Administration Panel';
    require (TEMPLATE_PATH . '/admin/index.php');
}
/**
 * displays a list of all the users to the admin.
 */
function manageUsers() {
    $results = array();

    if (User::getById($_SESSION['userid']) -> access === 'Administrator') {

        $results['pageTitle'] = 'User Management';
        $results['users'] = array();
        $data = User::getList();
        $results['users'] = $data['results'];
        $results['totalRows'] = $data['totalRows'];

        if (isset($_GET['error'])) {
            if ($_GET['error'] === 'userNotFound') {
                $results['errorMessage'] = 'Error: User not found.';
            }
        }

        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'changesSaved') {
                $results['statusMessage'] = 'Your changes have been saved.';
            }
            if ($_GET['status'] === 'userDeleted') {
                $results['statusMessage'] = 'User deleted.';
            }
        }

        require (TEMPLATE_PATH . '/admin/listUsers.php');

    }
}

/**
 * Saves a new user to the database
 */
function newUser() {

    $results = array();
    $results['pageTitle'] = 'Create New User';
    $results['formAction'] = 'newUser';

    if (isset($_POST['saveChanges'])) {

        // User has posted the user edit form: save the new user
        $user = new User;
        if (!isset($_POST['username'])) {
            $_POST['errorMessage'] = 'The username field is required!';
            require (TEMPLATE_PATH . '/admin/newaccount.php');
        }
        if (isset($_POST['passcode'])) {
            $_POST['passcode'] = $user -> genHash($_POST['passcode']);
        } else {
            $_POST['errorMessage'] = 'The password field is required!';
            require (TEMPLATE_PATH . '/admin/newaccount.php');
        }
        $user -> storeUserForm($_POST);
        $user -> insert();
        header('Location: admin.php?action=manageUsers&amp;status=changesSaved');

    } elseif (isset($_POST['cancel'])) {

        // User has cancelled their edits: return to the user list
        header('Location: /admin.php?action=manageUsers');

    } else {

        // User has not posted the user edit form yet: display the form
        $results['user'] = new User;
        require (TEMPLATE_PATH . '/admin/newaccount.php');
    }

}

/**
 * displays the edit user webpage and saves the changes
 * @return void
 */
function editUser() {
    $results = array();
    $results['pageTitle'] = 'Edit User';
    $results['formAction'] = 'editUser';

    if (isset($_POST['saveChanges'])) {

        if (!$user = User::getById($_POST['userId'])) {

            header('Location: admin.php?action=manageUsers&amp;error=userNotFound');
            return;

        } else {
            // User has posted the user edit form: save the new/edited user
            $user -> registerUser($_POST);
            $user -> update();
            header('Location: admin.php?action=manageUsers&amp;status=changesSaved');
            return;
        }

    } elseif (isset($_POST['cancel'])) {

        // User has cancelled their edits: return to the user list
        header('Location: /admin.php?action=manageUsers');
        return;

    } else {

        // User has not posted the article edit form yet: display the form
        $results['user'] = User::getById((int)$_GET['userId']);
        require (TEMPLATE_PATH . '/admin/edituser.php');
    }
}

function deleteUser() {
    if (!$user = User::getById((int)$_GET['userId'])) {
        header('Location: admin.php?action=manageUsers&amp;error=userNotFound');
        return;
    }

    $user -> delete();
    header('Location: admin.php?action=manageUsers&amp;status=userDeleted');
}

/**
 * handles the login logic and displays the corresponding web pages.
 */
function login() {

    $results = array();
    $results['pageTitle'] = 'Admin Login | WEBSITE TITLE';

    if (isset($_POST['login'])) {
        $user = User::getByUsername($_POST['username']);
        // User has posted the login form: attempt to log the user in
        if (password_verify($_POST['password'], $user -> passCode)) {
            // Login successful: Create a session and redirect to the admin homepage
            $_SESSION['username'] = $user -> userName;
            $_SESSION['userid'] = $user -> userId;
            header('Location: admin.php');

        } else {
            // Login failed: display an error message to the user
            $results['errorMessage'] = 'Incorrect username or password. Please try again.';
            //$user -> passCode;
            $user = null;
            require (TEMPLATE_PATH . '/admin/loginForm.php');
        }

    } else {
        // User has not posted the login form yet: display the form
        require (TEMPLATE_PATH . '/admin/loginForm.php');
    }

}

function logout() {
    unset($_SESSION['username']);
    unset($_SESSION['userid']);
    session_destroy();
    header('Location: admin.php');
}

/**
 * displays the web page to create a new article and has logic
 * for saving the articles or discarding the changes.
 */
function newArticle() {

    $results = array();
    $results['formName'] = 'Post New Article';
    $results['pageTitle'] = 'New Article';
    $results['formAction'] = 'newArticle';

    if (isset($_POST['saveChanges'])) {
        // User has posted the article edit form: save the new article
        $article = new Article;
        $article -> storeFormValues($_POST);
        $article -> insert();
        header('Location: ?action=listArticles&amp;status=changesSaved');

    } elseif (isset($_POST['cancel'])) {

        // User has cancelled their edits: return to the admin panel
        header('Location: admin.php');
    } else {

        // User has not posted the article edit form yet: display the form
        $results['article'] = new Article;
        $data = Category::getList();
        $results['categories'] = $data['results'];
        require (TEMPLATE_PATH . '/admin/editArticle.php');
    }

}

/**
 * displays the edit article web page and has logic for updating or discarding
 * changes.
 * @return void
 */
function editArticle() {

    $results = array();
    $results['formName'] = 'Edit Article';
    $results['pageTitle'] = 'Edit Article';
    $results['formAction'] = 'editArticle';

    if (isset($_POST['saveChanges'])) {

        // User has posted the article edit form: save the article changes
        if (!$article = Article::getById((int)$_POST['articleId'])) {
            header('Location: admin.php?action=listArticles&amp;error=articleNotFound');
            return;
        } else {
            $article -> storeFormValues($_POST);
            $article -> update();
            header('Location: admin.php?action=listArticles&amp;status=changesSaved');
        }

    } elseif (isset($_POST['cancel'])) {
        // User has cancelled their edits: return to the article list
        header('Location: admin.php?action=listArticles');

    } else {
        // User has not posted the article edit form yet: display the form
        $results['article'] = Article::getById((int)$_GET['articleId']);
        $data = Category::getList();
        $results['categories'] = $data['results'];
        require (TEMPLATE_PATH . '/admin/editArticle.php');
    }

}

function deleteArticle() {

    if (!$article = Article::getById((int)$_GET['articleId'])) {
        header('Location: admin.php?action=listArticles&amp;error=articleNotFound');
        return;
    }

    $article -> delete();
    header('Location: admin.php?action=listArticles&amp;status=articleDeleted');
}

/**
 * display a list of all the articles
 */
function listArticles() {
    $results = array();
    $data = Article::getList();
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $data = Category::getList();
    $results['categories'] = array();

    /* go through the categories*/
    foreach ($data['results'] as $category) {
        $results['categories'][$category -> cat_id] = $category;
    }

    $results['pageTitle'] = 'All Articles';

    if (isset($_GET['error'])) {
        if ($_GET['error'] === 'articleNotFound') {
            $results['errorMessage'] = 'Error: Article not found.';
        }
    }

    if (isset($_GET['status'])) {
        if ($_GET['status'] === 'changesSaved') {
            $results['statusMessage'] = 'Your changes have been saved.';
        }
        if ($_GET['status'] === 'articleDeleted') {
            $results['statusMessage'] = 'Article deleted.';
        }
    }

    require (TEMPLATE_PATH . '/admin/listArticles.php');
}

/*
 * Categories handling
 *
 */
function listCategories() {
    $results = array();
    $data = Category::getList();
    $results['categories'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = 'Article Categories';

    if (isset($_GET['error'])) {
        if ($_GET['error'] === 'categoryNotFound') {
            $results['errorMessage'] = 'Error: Category not found.';
        }
        if ($_GET['error'] === 'categoryContainsArticles') {
            $results['errorMessage'] = 'Error: Category contains articles. Delete the articles, or assign them to another category, before deleting this category.';
        }
    }

    if (isset($_GET['status'])) {
        if ($_GET['status'] === 'changesSaved') {
            $results['statusMessage'] = 'Your changes have been saved.';
        }
        if ($_GET['status'] === 'categoryDeleted') {
            $results['statusMessage'] = 'Category deleted.';
        }
    }

    require (TEMPLATE_PATH . '/admin/listCategories.php');
}

/**
 * handles the creation of new categories
 */
function newCategory() {

    $results = array();
    $results['pageTitle'] = 'New Article Category';
    $results['formAction'] = 'newCategory';

    if (isset($_POST['saveChanges'])) {

        // User has posted the category edit form: save the new category
        $category = new Category;
        $category -> storeFormValues($_POST);
        $category -> insert();
        header('Location: admin.php?action=listCategories&status=changesSaved');

    } elseif (isset($_POST['cancel'])) {

        // User has cancelled their edits: return to the category list
        header('Location: admin.php?action=listCategories');

    } else {

        // User has not posted the category edit form yet: display the form
        $results['category'] = new Category;
        require (TEMPLATE_PATH . '/admin/editCategories.php');

    }

}

function editCategory() {

    $results = array();
    $results['pageTitle'] = 'Edit Article Category';
    $results['formAction'] = 'editCategory';

    if (isset($_POST['saveChanges'])) {

        // User has posted the category edit form: save the category changes

        if (!$category = Category::getById((int)$_POST['categoryId'])) {
            header('Location: admin.php?action=listCategories&error=categoryNotFound');
            return;
        }

        $category -> storeFormValues($_POST);
        $category -> update();

        header('Location: admin.php?action=listCategories&status=changesSaved');

    } elseif (isset($_POST['cancel'])) {

        // User has cancelled their edits: return to the category list
        header('Location: admin.php?action=listCategories');

    } else {
        // User has not posted the category edit form yet: display the form
        $results['category'] = Category::getById((int)$_GET['categoryId']);
        require (TEMPLATE_PATH . '/admin/editCategories.php');
    }

}

/**
 * handles the deletion of categories.
 * @return void
 */
function deleteCategory() {

    if (!$category = Category::getById((int)$_GET['categoryId'])) {
        header('Location: admin.php?action=listCategories&error=categoryNotFound');
        return;
    }

    $articles = Article::getList(1000000, $category -> id);

    if ($articles['totalRows'] > 0) {
        header('Location: admin.php?action=listCategories&error=categoryContainsArticles');
        return;
    }

    $category -> delete();
    header('Location: admin.php?action=listCategories&status=categoryDeleted');
}
