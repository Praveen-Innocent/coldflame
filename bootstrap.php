<?php
/*
Author:Praveen Innocent (www.praveen-innocent.com).
This is a free code Licensed under the Apache License v2.0
 * http://www.apache.org/licenses/LICENSE-2.0
*/
session_start();
error_reporting();

require "config.php";

require_once("classes/generic.class.php");
//autoload other classes
foreach (glob("classes/*.php") as $filename)
{
    require_once($filename);
}



?>