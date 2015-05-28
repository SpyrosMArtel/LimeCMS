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
/**
 * The Lime CMS gallery, not much going on here yet....
 */
class Gallery {

    function __construct($argument)
    {

    }

    function getImages($imgDirectory = null)
    {

        $images = array();
        $tmpDirectory = opendir($imgDirectory);

        while (($currentFile = readdir($tmpDirectory)) !== false)
        {
            if ($currentFile[0] !== '.')
            {
                $images[] = $currentFile;
            }
        }

        return ($images);
    }

}
?>