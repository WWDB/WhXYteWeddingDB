<?php
/*
    TableView.php

    This class is used in commiteeView.php to handle populating the tableView.js instances

    It has a constructor and 1 public function: applyViewTable(), which sends the neccessary data to the TableView.js
    Instance so it can render the view.

    When applyViewTable() is called the server prints out a json containing all the neccessary 
    data to populate the table

    the json contains
    {
        "rows":[
            {"col1Name":"valueOfCol1Row1","col2name":"valueOfCol2Row1"....},
            {"col1Name":"valueOfCol1Row2","col2name":"valueOfCol2Row2"....},
            .
            .
            .

        ]
    }

*/
class TableView {
    
    //Connection information
    private $servername;
	private $username;
	private $password;
	private $dbname;



    private $primary_key;//the name of the primary key. This is used when the user clicks more or edit
    //so we have somthing to pass to itemView.php
    private $view_name;//the name of the table or view to operate on.
    
    private $hiddenColumns;//thease columns are hidden from the user but are still passed to the TableView in the users browser.
    //For example if you dont wanna always show the Person_ID of a person you can put ["Person_ID"] in the constructor as a hidden column
    private $allowAdd;
    //When the user clicks the MORE button next to a record, a seperate page will show ALL the columns, including the hidden columns.
    private $link;

    //add_only is used when the user is adding an item to a view. Sometimes the user is required to select a preexisting item to add to a relationship
    //if set to 1 then the tableView will change to disable the more,edit,delete(if aplicable), and add buttons
    //and will insted put a checkbox by the row. The user can only select 1 checkbox.
    //if set to 2 then the user can select multiple checkboxes.
    private $add_only;

    
    //
    function __construct($primary_key,$view_name,$hiddenColumns,$allowAdd,$admin_deleting_is_allowed,$link,$add_only){
        $this->primary_key=$primary_key;
        $this->view_name=$view_name;
        $this->admin_deleting_is_allowed=$admin_deleting_is_allowed;
        $this->hiddenColumns=$hiddenColumns;
        $this->link=$link;
        $this->servername=$servername;
        $this->username=$username;
        $this->password=$password;
        $this->dbname=$dbname;
        //$baseTables and $joined on are only to be used if the view you are creating is derived from a join on 1 or more tables
        $this->baseTables=$baseTables;//if using a base table as the view, then you can keep this null
        $this->joinedOn=$joinedOn;//if using a base table as the view, then you can keep this null
        $this->allowAdd=$allowAdd;
        $this->add_only=$add_only;


        
        
    }
	
    //connects to the server and applys an sql query
    private function applyQuery($sql){

        

        if($this->link->connect_errno) {
            die("Connection failed : " . $mysqli->connect_error);
        }
        //$sql="SELECT * FROM volunteerstest";
        $result=$this->link->query($sql);
        if(!$result){
			
            
			
			die('{"item":{"Error":"ERROR: Could not execute'. $sql." ".mysqli_error($this->link).'"}}');
        }else{
        	return $result;
		}
    }


    //applys a select * from <VIEW_NAME> and returns the results a a .json
    //containing all the columns and rows. Used to help populate TableView.js instances.
    public function applyViewTableFromQuery($result)
    {
        
        //$result=$this->applyQuery("SELECT * FROM ".$this->view_name);
        
        header('Content-Type: application/json');
        echo '{ "rows":';
        $rows = array();
        //$types=array();
   	    while($r = mysqli_fetch_assoc($result)) {
            
            $rows[] = $r;
            
   	    }
        
        print json_encode($rows);
        if(!$this->add_only){
            echo ',"add_only":0';
        }else{
            echo ',"add_only":1';
        }
        
        echo ',"hidden":';
        print json_encode($this->hiddenColumns);
        echo ',"key":';
        print json_encode($this->primary_key);
        echo ', "admin_deleting_is_allowed":'.$this->admin_deleting_is_allowed;
        echo "}";
    }
    //applys a select * from <VIEW_NAME> and returns the results a a .json
    //containing all the columns and rows. Used to help populate TableView.js instances.
    public function applyViewTable()
    {
        
        $result=$this->applyQuery("SELECT * FROM ".$this->view_name);
        $this->applyViewTableFromQuery($result);
        
    
    }
}


?>