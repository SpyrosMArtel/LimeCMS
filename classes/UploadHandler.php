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
define('THUMBNAIL', '/thumbnails');
/**
 * Class to handle file uploads, upload script by CertaiN
 */
class UploadHandler {
    public $targetPath = null;
    public $username = null; 

    function __construct($data = array()) {

        $this -> username = $data['username'];

        $user = User::getByUsername($data['username']);
        $userName = md5($user -> userName . '-' . $user -> registered);
        $this -> targetPath = $data['path'] . '/' . $userName;

        if (!file_exists($this -> targetPath)) {
            mkdir($this -> targetPath, 0755);
            mkdir($this -> targetPath . THUMBNAIL, 0755);
        }
    }

    /**
     * creates a thumbnail of a given image
     */
    public function thumbnail($options = array()) {

        $iThumbnailWidth = (int)$options['tWidth'];
        $iThumbnailHeight = (int)$options['tHeight'];
        $iMaxWidth = (int)$options['mWidth'];
        $iMaxHeight = (int)$options['mHeight'];

        $img = NULL;

        $sExtension = strtolower(end(explode('.', $options['imagefile'])));
        switch ($sExtension) {
            case 'jpg': case 'jpeg':
                $img = @imagecreatefromjpeg($options['imagefile']) or die("Cannot create new JPEG image");
                break;
            case 'png':
                $img = @imagecreatefrompng($options['imagefile']) or die("Cannot create new PNG image");
                break;
            case 'gif':
                $img = @imagecreatefromgif($options['imagefile']) or die("Cannot create new GIF image");
                break;
            default:
                return '';
                break;
        }

        if ($img) {
            $iOrigWidth = imagesx($img);
            $iOrigHeight = imagesy($img);

            if ($iMaxWidth && $iMaxHeight) {
                // Get scale ratio
                $ratio = min($iMaxWidth / $iOrigWidth, $iMaxHeight / $iOrigHeight);
                if ($ratio < 1) {
                    $iNewWidth = floor($ratio * $iOrigWidth);
                    $iNewHeight = floor($ratio * $iOrigHeight);
                    $tmpimg = imagecreatetruecolor($iNewWidth, $iNewHeight);

                    if ($sExtension === 'png' || $sExtension === 'gif') {
                        imagealphablending($tmpimg, false);
                        imagesavealpha($tmpimg, true);
                        $transparent = imagecolorallocatealpha($tmpimg, 255, 255, 255, 127);
                        imagefilledrectangle($tmpimg, 0, 0, $iNewWidth, $iNewHeight, $transparent);
                    }

                    imagecopyresampled($tmpimg, $img, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $iOrigWidth, $iOrigHeight);
                    imagedestroy($img);
                    $img = $tmpimg;
                }

            } else if ($iThumbnailWidth && $iThumbnailHeight) {

                $ratio = max($iThumbnailWidth / $iOrigWidth, $iThumbnailHeight / $iOrigHeight);
                if ($ratio < 1) {

                    $iNewWidth = floor($ratio * $iOrigWidth);
                    $iNewHeight = floor($ratio * $iOrigHeight);
                    $tmpimg = imagecreatetruecolor($iNewWidth, $iNewHeight);
                    $tmp2img = imagecreatetruecolor($iThumbnailWidth, $iThumbnailHeight);
                    imagecopyresampled($tmpimg, $img, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $iOrigWidth, $iOrigHeight);

                    if ($iNewWidth == $iThumbnailWidth) {
                        $yAxis = ($iNewHeight / 2) - ($iThumbnailHeight / 2);
                        $xAxis = 0;
                    } else if ($iNewHeight == $iThumbnailHeight) {
                        $yAxis = 0;
                        $xAxis = ($iNewWidth / 2) - ($iThumbnailWidth / 2);
                    }
                    // testing....
                    if ($sExtension === 'png' || $sExtension === 'gif') {
                        imagealphablending($tmpimg, false);
                        imagesavealpha($tmpimg, true);
                        $transparent = imagecolorallocatealpha($tmpimg, 255, 255, 255, 127);
                        imagefilledrectangle($tmpimg, 0, 0, $iNewWidth, $iNewHeight, $transparent);
                    }

                    imagecopyresampled($tmp2img, $tmpimg, 0, 0, $xAxis, $yAxis, $iThumbnailWidth, $iThumbnailHeight, $iThumbnailWidth, $iThumbnailHeight);
                    imagedestroy($img);
                    imagedestroy($tmpimg);
                    $img = $tmp2img;
                }
            }

            switch ($sExtension) {
                case 'jpg': case 'jpeg':
                    imagejpeg($img, $this -> targetPath . THUMBNAIL, 100);
                    break;
                case 'png': imagepng($img, $this -> targetPath . THUMBNAIL, 0);
                    break;
                case 'gif': imagegif($img, $this -> targetPath . THUMBNAIL);
                    break;
                default: break;
            }
        }
    }

    public function upload() {
        try {
            $results = array();
            if (!isset($_FILES['image']['error']) || is_array($_FILES['image']['error'])) {
                throw new RuntimeException('Invalid Parameters');
            }

            switch ($_FILES['image']['error']) {
                case UPLOAD_ERR_OK :
                    break;
                case UPLOAD_ERR_NO_FILE :
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE :
                case UPLOAD_ERR_FORM_SIZE :
                    throw new RuntimeException('Exceeded filesize limit.');
                default :
                    throw new RuntimeException('Unknown errors.');
            }

            // You should also check filesize here.
            if ($_FILES['image']['size'] > 1000000) {
                throw new RuntimeException('Exceeded filesize limit.');
            }
            // DO NOT TRUST $_FILES['image']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search($finfo -> file($_FILES['image']['tmp_name']), array('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', ), true)) {
                throw new RuntimeException('Invalid file format.');
            }
            // You should name it uniquely.
            // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
            // On this example, obtain safe unique name from its binary data.
            if (!move_uploaded_file($_FILES['image']['tmp_name'], sprintf($this -> targetPath . '/%s.%s', sha1_file($_FILES['image']['tmp_name']), $ext))) {
                throw new RuntimeException('Failed to move uploaded file.');
            }

            $results['errorMessage'] = 'File(s) uploaded successfully.';

        } catch (RuntimeException $uploadEx) {
            $results['errorMessage'] = $uploadEx -> getMessage();
        }
    }

}
?>