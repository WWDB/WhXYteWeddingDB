<?php
/*
CommiteeView.php


This is the backend to the tableView.js please take a look at TestView.php for an example of its use

What the CommiteeView does
The commiteeView class serves as a portal between some table or view in the database
and a webpage running tableView.js or itemView.js

Everything is passed in and out of the CommiteeView via json, this will give us a lot of flexibility in how we design the frontend by giving us an 'api-like' interface between frontend and backend



Bolth the tableView.js and itemView.js classes dynamicly generate forms and tables from data passed from CommiteeView class


TODO: Add funcionality to take item_edit requests from a page containing ItemView.js
TODO: Add funcionality to take item_add requests from a page containing ItemView.js and possibly offload add SQL querys to a user defined function which will be passed into the constructor
TODO: Add SQL injection protection
TODO: Add XSS injection protection



For complex views that are created from the join of 2 tables, adding and editing can get a bit ...messy
Right now my solution is to take in the 2 base tables as input to the constructor, and the key they were joined on.

I am still trying to think of the logic to generate a dropdown when adding, 

I think for most instances it will work just fine


NOTE: I just realized that with a dynamicly generated tableView and a dynamicly generated add form and edit form, I am builing a new PHPmyAdmin...
*/
class CommiteeView {
    
    //Connection information
    private $servername;
	private $username;
	private $password;
	private $dbname;



    private $primary_key;//the name of the 
    private $view_name;//the name of the table or view to operate on.
    private $datatypes;
    private $hiddenColumns;//thease columns are hidden from the user but are still passed to the TableView in the users browser.
    //For example if you dont wanna always show the Person_ID of a person you can put ["Person_ID"] in the constructor as a hidden column
    
    //When the user clicks the MORE button next to a record, a seperate page will show ALL the columns, including the hidden columns.

    private $writableColumns;//Used to restrict what the user can edit. A good example of somthing we do NOT want the user to edit is primary keys
    
    //Another example is restricting the event commitee from changing the ammount of tickets that was purchused before the event, and requesting the guest pay for their tickets again.



    

    function __construct($primary_key,$view_name,$hiddenColumns,$writableColumns,$allowAdd,$servername,$username,$password,$dbname){
        $this->primary_key=$primary_key;
        $this->view_name=$view_name;
        
        $this->hiddenColumns=$hiddenColumns;
        $this->writableColumns=$writableColumns;
        $this->servername=$servername;
        $this->username=$username;
        $this->password=$password;
        $this->dbname=$dbname;
        $this->datatypes=$this->getTableDatatypes();//We dont need to pass in the data types of the columns
        
        //We insted get them from a query
        
    }
    //connects to the server and applys an sql query
    private function applyQuery($sql){

        $link= new mysqli($this->servername,$this->username,$this->password,$this->dbname);

        if($link->connect_errno) {
            die("Connection failed : " . $mysqli->connect_error);
        }
        //$sql="SELECT * FROM volunteerstest";
        $result=$link->query($sql);
        if(!$result){
            echo "ERROR: Could not execute $sql.".mysqli_error($link);
        }
        return $result;
    }


    //Gets the data type of all the columns in the view. Can be used for serverside form validation.
    private function getTableDatatypes(){
        $result=$this->applyQuery("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->view_name."'");
        $rows = array();
   	    while($r = mysqli_fetch_array($result)) {
            $rows[] = $r[0];
            //echo $r[0]."<br>";
            
        }
        //echo $rows[0]."<br>";
        return $rows;
    }
    //applys a select * from <VIEW_NAME> and returns the results a a .json
    //containing all the columns and rows. Used to help populate TableView.js instances.
    private function applyViewTable(){
        $result=$this->applyQuery("SELECT * FROM ".$this->view_name);
        header('Content-Type: application/json');
        echo '{ "rows":';
        $rows = array();
        //$types=array();
   	    while($r = mysqli_fetch_assoc($result)) {
            
            $rows[] = $r;
            
   	    }
        
        print json_encode($rows);
        echo ',"hidden":';
        print json_encode($this->hiddenColumns);
        echo ',"key":';
        print json_encode($this->primary_key);
        echo "}";
    }

    //Returns a json containing all the attributes of a specific row forom the view
    //Used to help populate ItemView.js instances
    private function applyViewItem($key){
        $result=$this->applyQuery("SELECT * FROM ".$this->view_name." WHERE ".$this->primary_key."=".$key);
        $r = mysqli_fetch_assoc($result);
        header('Content-Type: application/json');
        echo '{ "item":';
        print json_encode($r);
        echo '}';
        
    }

    //Used by ItemView.js to display the right form types when editing or adding an item
    private function sendDataTypes(){
        header('Content-Type: application/json');
        echo '{"datatypes": ';
        print json_encode($this->datatypes);
        echo ',"writable": ';
        print json_encode($this->writableColumns);
        echo "}";
    }

    //Used by itemView.js when applying a change to a row
    private function applyEdit($request){

    }

    //Used by itemView.js when adding to a row
    private function applyAdd($request){

    }

    public function process_request($obj){
        
        if($obj->action=="view_table"){
            $this->applyViewTable();
        }elseif($obj->action=="view_item"){
            $this->applyViewItem($obj->key);
        }elseif($obj->action=="get_datatypes"){
            $this->sendDataTypes();
        }elseif($obj->action=="apply_edit"){
            $this->applyEdit($request);
        }elseif($obj->action=="apply_add"){

        }else{
            echo "Bad request";
            die("Bad request");
        }
    }
	

}


?>