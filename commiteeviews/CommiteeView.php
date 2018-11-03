<?php
/*
CommiteeView.php


This is the backend to the tableView.js please take a look at TestView.php for an example of its use

What the CommiteeView does
    The commiteeView class serves as a portal between some table or view in the database
    and a webpage running tableView.js or itemView.js

    Bolth the tableView.js and itemView.js classes dynamicly generate forms and tables from data passed from CommiteeView class

    

*/
class CommiteeView {
    
    //Connection information
    private $servername;
	private $username;
	private $password;
	private $dbname;



    private $primary_key;//the name of the 
    private $view_name;//the name of the table or view to operate on.
    private $update_query; 
    private $datatypes;
    private $hiddenColumns;//thease columns are hidden from the user but are still passed to the TableView in the users browser.
    //For example if you dont wanna always show the Person_ID of a person you can put ["Person_ID"] in the constructor as a hidden column
    
    //When the user clicks the MORE button next to a record, a seperate page will show ALL the columns, including the hidden columns.



    private $writableColumns;


    

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
    private function getTableDatatypes(){
        $result=$this->applyQuery("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->view_name."'");
        $rows = array();
   	    while($r = mysqli_fetch_array($result)) {
            $rows[] = $r[0];
            //echo $r[0]."<br>";
            
        }
        //echo $rows[0]."<br>";
        return $rows;
    }
    //applys a select * from <VIEW_NAME> and returns the results a a .json
    //containing all the columns
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
    //
    private function applyViewItem($key){
        $result=$this->applyQuery("SELECT * FROM ".$this->view_name." WHERE ".$this->primary_key."=".$key);
        $r = mysqli_fetch_assoc($result);
        header('Content-Type: application/json');
        echo '{ "item":';
        print json_encode($r);
        echo ',"datatypes": ';
        print json_encode($this->datatypes);
        echo ',"writable": ';
        print json_encode($this->writableColumns);
        echo "}";
        
    }
    
    

    public function process_request($request){
        if($request["action"]=="view_table"){
            $this->applyViewTable();
        }elseif($request["action"]=="view_item"){
            $this->applyViewItem($request["key"]);
        }elseif($request["action"]=="get_datatypes"){
            $this->applyViewItem($request["key"]);
        }elseif($request["action"]=="apply_edit"){

        }elseif($request["action"]=="apply_add"){

        }else{
            echo "Bad request";
            die("Bad request");
        }
    }
	

}


?>