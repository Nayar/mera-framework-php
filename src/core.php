<?php
/**
	Copyright 2012 by Nayar Joolfoo
	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation, version 3 of the License.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU Lesser General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  File Description: 
 *	This is where all the magic starts. We include all the classes and initialise them.
 *	We write codes which are supposed to run globally on all pages here.
 */
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

// Define some constants which can be used inside any classes/functions without the need to globalise them
define("ROOTDIR",dirname(dirname(__FILE__))."/");
//define("ROOTURL","http://".$_SERVER['SERVER_NAME']."/".basename(dirname(dirname(__FILE__)))."/");

//ini_set("log_errors", 1);
//ini_set("error_log", ROOTDIR."/php-error.log");

require ROOTDIR."src/class_db.php"; // Include MySQL class file
$db = new MysQL(); // Initialising the MySQL class in object $db
// require ROOTDIR."inc/class_users.php";
//require ROOTDIR.'src/functions.php';

require ROOTDIR.'src/class_mera_entities.php';