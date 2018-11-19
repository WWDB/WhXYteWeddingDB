<?php


include("ItemView.php");
/*
CommiteeView.php

This is the backend to all the commitee view frontends. It contains a TableView.php object
and a ItemView.php object

Everything is passed in and out of the CommiteeView via json, this will give us a lot of flexibility in how we design the frontend by giving us an 'api-like' interface between frontend and backend



Bolth the tableView.js and itemView.js classes dynamicly generate forms and tables from data passed from CommiteeView class


TODO: Add funcionality to take item_edit requests from a page containing ItemView.js
TODO: Add funcionality to take item_add requests from a page containing ItemView.js and possibly offload add SQL querys to a user defined function which will be passed into the constructor
TODO: Add SQL injection protection
TODO: Add XSS injection protection



NOTE: I just realized that with a dynamicly generated tableView and a dynamicly generated add form and edit form, I am builing a new PHPmyAdmin...


Depending on what is passed into the $baseTables arguement, the commiteeView may create multiple ItemViews
EX: if the user wants to view people who donated, and their donation items
the commitee view will create an itemView for the person showing all their donation items
and a seperate ItemView for donation_item in which the user can add, or edit to single donation items.
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



    

    function __construct($primary_key,$view_name,$hiddenColumns,$admin_deleting_is_allowed,$baseTables,$joinedOn,$servername,$username,$password,$dbname){
        $this->primary_key=$primary_key;
        $this->view_name=$view_name;
        
        $this->hiddenColumns=$hiddenColumns;
        $this->writableColumns=$writableColumns;
        $this->servername=$servername;
        $this->username=$username;
        $this->password=$password;
        $this->dbname=$dbname;
        //$baseTables and $joined on are only to be used if the view you are creating is derived from a join on 1 or more tables
        $this->baseTables=$baseTables;//if using a base table as the view, then you can keep this null
        $this->joinedOn=$joinedOn;//if using a base table as the view, then you can keep this null
        $link= new mysqli($servername,$username,$password,$dbname);


        //$this->datatypes=$this->getTableDatatypes();//We dont need to pass in the data types of the columns
        //$primary_key,$view_name,$hiddenColumns,$allowAdd,$admin_deleting_is_allowed,$link
        $this->tableView=new TableView($primary_key,$view_name,$hiddenColumns,True,$admin_deleting_is_allowed,$link,0);
        
        //create the main Itemview
        $this->itemView=new ItemView($primary_key,$hiddenColumns,$baseTables,$admin_deleting_is_allowed,$joinedOn,$link);
        $this->subItemViews=array();
        foreach ($this->baseTables as $table) {
            if($table[1]==2||$table[4]!=2){
                $newBT=$table;
                $newBT[1]=1;
                $name=$table[0];
                
                $this->subItemViews[$name]=new ItemView("Item_ID",array(),array($newBT),$admin_deleting_is_allowed,$joinedOn,$link);
            }
        }
//$view->process_request($obj);
        //We insted get them from a query
        
    }
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
    



    public function process_request($obj){
        
        if($obj->action=="view_table"){
            $this->tableView->applyViewTable();
            //die('{"item":{"Error":"BAD VIEW"}}');
        }elseif($obj->action=="view_item"){
            //$this->applyViewItem($obj->key);
            if($obj->subitem!=null){
                //echo $obj->subitem;
                $this->subItemViews[$obj->subitem]->applyViewItem($obj->key);
            }else{
                $this->itemView->applyViewItem($obj->key);
            }

        }elseif($obj->action=="prepare_add_item"){
            //$this->applyViewItem($obj->key);
            if($obj->subitem!=null){
                //echo $obj->subitem;
                $this->subItemViews[$obj->subitem]->prepareAddItem();
            }else{
                $this->itemView->prepareAddItem();
            }
        }elseif($obj->action=="get_add_selection"){
            if($obj->subitem!=null){
                
                $this->subItemViews[$obj->subitem]->getBaseTable();
            }  
            //$this->sendBaseDataTypes();
        }elseif($obj->action=="apply_edit"){
            //$this->applyEdit($request);
        }elseif($obj->action=="get_add"){
            //$this->sendBaseDataTypes();

        }elseif($obj->action=="apply_add"){

        }else{
            
            die('{"item":{"Error":"Bad Request"}}');
        }
    }
	

}


?>