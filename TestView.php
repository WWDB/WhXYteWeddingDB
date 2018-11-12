<?php
/*How to use this file to create your own views
1. Copy this file and rename is as SuchAndSuchView.php
2. Change the varibles below to match whatever view you are operating on.
3. Create a new file called suchAndSuchView.html
4. Ensure your new html file contains the same things as testview.html
5. Ensure your new html file includes a script containing the following
    window.onload = function () {
    
    var users_table=new TableView("users_table","URL_FOR_SuchAndSuchView.php");
    users_table.init();
 }

 6. You just created a new view congrats!

*/


//TODO: add user/session verification here so that only authorized users can invoke functions of the CommiteeView
require_once 'sessionhelpers.php';
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !checkSession()) {
    header('HTTP/1.0 403 Forbidden');

	die('<h1><i>Forbidden!!</i></h1>');
}

require_once 'CommiteeView.php';
/*CONNECTION VARIBLES
    used to connect to the database
*/
include("config.php");


/*QUERY VARIBLES


Thease are used to specify what data is getting sent back to the commitee view

set $key to the primary key of the view. Or any unique value in the view. This is used in the edit, and view_item functions
so that the CommiteeView class can send you information on a single record that you can view and edit.

set $view to the name of the table or view as shown in phpMyAdmin. I have not tested it with putting a raw query in it.
It might work but It would be safer to create a view in phpMyAdmin by using the query CREATE OR REPLACE VIEW viewName AS <Insert select query here>
*/
$key="Person_ID";
$view="Donor_Name_Test";


/*

*/
$hidden_Columns=array("Person_ID");
$baseTables=array("Person","donation_item");
$joinedOn="Person_ID";
$writable_columns=array("First_Name","Last_Name");
$adding_is_allowed=FALSE;




//We are now using json insted of url paramaters
header("Content-Type: application/json; charset=UTF-8");
$json_str = file_get_contents('php://input');
$obj = json_decode($json_str);



$view=new CommiteeView($key,$view,$hidden_Columns,$writable_columns,$adding_is_allowed,$baseTables,$joinedOn,DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

$view->process_request($obj);
?>