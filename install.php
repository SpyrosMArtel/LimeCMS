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

/* needs heavy work, it was mainly for automating the installation */
if (false) {
?>
    $piResults = array();
    $piResults['noPHP'] = 'PHP is not enabled';
    header('Content-type: application/json');
    echo json_encode($piResults);
<?php
}
$step = filter_input(INPUT_GET, 'step', FILTER_SANITIZE_NUMBER_INT);

switch ($step) {
    case 1:
        break;
    case 2:
        break;
    default:
        break;
}
if (!file_exists('config.php')) {
    $piResults = array();
    $piResults['versionError'] = false;
    if (phpversion() < 5) {
        $piResults['versionError'] = true;
        $piResults['versionErrorMsg'] = 'PHP version is <b>' . phpversion() .
        '</b> - too old';
    } else {
        $piResults['versionErrorMsg'] = 'PHP version is <b>' . phpversion() .
        '</b> - good!';
    }

    if (!function_exists('mail')) {
        $piResults['mailErrorMsg'] = 'PHP Mail function is not enabled!';
    } else {
        $piResults['mailErrorMsg'] = 'PHP Mail function is enabled!';
    }
    if (!extension_loaded('gd')) {
        $piResults['gdErrorMsg'] = 'GD is not available.';
    } else {
        $piResults['gdErrorMsg'] = 'GD is available.';
    }
    /*  if(!extension_loaded('imagick')) {
     $piResults['gdErrorMsg'] = 'ImageMagick is not available.';
     }
     */
    if (ini_get("safe_mode")) {
        $piResults['safeModeError'] = true;
        $piResults['safeModeErrorMsg'] = 'Please switch off PHP Safe Mode';
    } else {
        $piResults['safeModeError'] = false;
        $piResults['safeModeErrorMsg'] = 'PHP Safe Mode is off - good!';
    }

    // db credentials
    $piResults['dberrorMsg'] = false;
    // try to connect to the DB, if not display error
    try {
      $conn = new PDO("mysql:host={$_POST['dbhost']};dbname={$_POST['dbname']}",
      $_POST['dbuser'], $_POST['dbpass']);

      $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $piResults['dbVersion'] = $conn -> getAttribute(PDO::ATTR_CLIENT_VERSION);
      /*        // let's run the sql script to create our database
      $sql = file_get_contents('limecms.sql');
      $conn -> prepare($sql);
      // $piResults['dbCreated'] either 0 or 1
      $piResults['dbCreated'] = $conn -> exec($sql);*/

    } catch(PDOException $e) {
        $piResults['dberror'] = true;
        $piResults['dberrorMsg'] = 'Sorry, these details are not correct.
            Here is the exact error: ' . $e -> getMessage();
    }

    // try to create the config file and let the user continue
    $configcode = "
    <?php
        define('DBSERVER','" . $_POST['dbhost'] . "');
        define('DBNAME','" . $_POST['dbname'] . "');
        define('DBUSER','" . $_POST['dbuser'] . "');
        define('DBPASS','" . $_POST['dbpass'] . "');
    ?>";

    if (!is_writable('config.php')) {
        $piResults['configWriteError'] = "<p>Sorry, I can't write to
        <b>inc/db_connect.php</b>. You will have to edit the file yourself. Here
         is what you need to insert in that file:<br /><br />";
    } else {
        $fp = fopen('db_connect.php', 'wb');
        fwrite($fp, $connect_code);
        fclose($fp);
        chmod('config.php', 0644);
    }

    if (!empty($piResults['versionError'])) {
        echo('<li>' . $piResults['versionErrorMsg'] . '</li>');
    }
    echo('<li>' . $piResults['mailErrorMsg'] . '</li>');
    echo('<li>' . $piResults['gdErrorMsg'] . '</li>');
    if (!empty($piResults['safeModeError'])) {
        echo('<li>' . $piResults['safeModeErrorMsg'] . '</li>');
    }
    if (!empty($piResults['dberror'])) {
        echo('<li>' . $piResults['dberrorMsg'] . '</li>');
    }
    echo('<li>' . $piResults['dbVersion'] . '</li>');
    if (!empty($piResults['configWriteError'])) {
        echo $piResults['configWriteError'];
        echo '<textarea rows=\'5\' cols=\'50\' onclick=\'this.select();\'>' .
        $configcode . '</textarea></p>';
    }
}
?>