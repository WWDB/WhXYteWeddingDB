<?php
/*
ItemView.php 

This class serves as the backend for the ItemView.js instances
The paramaters passed into the constructor specifify what the itemView will show

    EX:
    $primary_key="PersonID"
    $hiddenColumns
    
    $baseTables=array(
        array("Person",1,array("Street",'Apt_Type','City','State','Zip','Phone_Num')),
        array("Donation_Item",2,array("Status"))
    );
    
    $joinedOn="PersonID"
    $link ='Ignore this, the parent commitee view passses the sql link into this for us

    The example paramaters will create a form showing the person info, as well as a list of all the items
    They have donated. The user will be able to edit the status of each item they donated


    
*/
include("TableView.php");
class ItemView {
    
    //Connection information
    


    private $primary_key;//the name of the 
    private $view_name;//the name of the table or view to operate on.
    private $datatypes;
    private $hiddenColumns;//thease columns are hidden from the user but are still passed to the TableView in the users browser.
    //For example if you dont wanna always show the Person_ID of a person you can put ["Person_ID"] in the constructor as a hidden column
    
    //When the user clicks the MORE button next to a record, a seperate page will show ALL the columns, including the hidden columns.

    private $writableColumns;//Used to restrict what the user can edit. A good example of somthing we do NOT want the user to edit is primary keys
    private $link;
    private $mainTables;
    private $foreignTables;
    //Another example is restricting the event commitee from changing the ammount of tickets that was purchused before the event, and requesting the guest pay for their tickets again.



    

    function __construct($primary_key,$hiddenColumns,$baseTables,$admin_deleting_is_allowed,$joinedOn,$link){
        $this->primary_key=$primary_key;
        
        
        $this->hiddenColumns=$hiddenColumns;
        $this->writableColumns=$writableColumns;
       
        $this->link=$link;
        //$baseTables and $joined on are only to be used if the view you are creating is derived from a join on 1 or more tables
        $this->baseTables=$baseTables;//if using a base table as the view, then you can keep this null
        $this->joinedOn=$joinedOn;//if using a base table as the view, then you can keep this null



        //$this->datatypes=$this->getTableDatatypes();//We dont need to pass in the data types of the columns
        
        
        
        
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
    //Gets the data type of all the columns in the view. Only used in tableView.js
    private function getTableDatatypes($table){
        $result=$this->applyQuery("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$table."'");
        $rows = array();
   	    while($r = mysqli_fetch_array($result)) {
            $rows[] = $r[0];
            //echo $r[0]."<br>";
            
        }
        //echo $rows[0]."<br>";
        return $rows;
    }
    private function getTableColumnNames($table){
        $result=$this->applyQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$table."'");
        $rows = array();
   	    while($r = mysqli_fetch_array($result)) {
            $rows[] = $r[0];
            //echo $r[0]."<br>";
            
        }
        //echo $rows[0]."<br>";
        return $rows;
    }

    //If the add mode is 1 or 3 then the user must be able to select an item from a pre existing table
    //we use the tableView backend to dump the information from this table
    public function getBaseTable(){
        $forginPrimary=$this->applyQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME LIKE '". $this->baseTables[0][0] ."' AND CONSTRAINT_NAME LIKE 'P%'");
        $key="";
        while($r1 = mysqli_fetch_assoc($forginPrimary)) {
                     
            $key = $r1["COLUMN_NAME"];
            
        }

        $tableView=new TableView($key,$this->baseTables[0][0],array(),True,0,$this->link,1);
        $result=$this->applyQuery("SELECT * FROM ".$this->baseTables[0][0]." WHERE 1");
        
        $tableView->applyViewTableFromQuery($result);


    }
    public function prepareAddItem(){
        header('Content-Type: application/json');
            echo '{ "tables":';
        $allTables=array();
        foreach ($this->baseTables as $table) {
            $curTable=array();
            $curTable["name"]=$table[0];
            $curTable["datatypes"]=$this->getTableDatatypes($table[0]);
            $curTable["writable"]=$table[2];
            $curTable["hidden"]=array($this->joinedOn);
            $curTable["col_names"]=$this->getTableColumnNames($table[0]);
            $curTable["displayMode"]=$table[1];
            $curTable["addMode"]=$table[3];
            $allTables[]=$curTable;
        }
        print json_encode($allTables);
        echo "}";
    }

    
    /*
        Returns a json containing the first 3 columns of each row in each of the forgin tables.
        
        This is used by ItemView.js to create a list of the forgin items in the view.
        EX: it will create a list of donation_items to assign to a winner.
    */
    public function applyViewItem($key){
        header('Content-Type: application/json');
            echo '{ "tables":';
        $allTables=array();
        foreach ($this->baseTables as $table) {
            
            //echo "eee";
            $curTable=array();
            $curTable["name"]=$table[0];
            $curTable["displayMode"]=$table[1];
            //echo $table1[1];
            if($table[1]==2){
                $result=$this->applyQuery("SELECT * FROM ".$table[0]." WHERE ".$table[0].".".$this->joinedOn." = ".$key);
                
                $rows = array();
                //$types=array();
                while($r = mysqli_fetch_assoc($result)) {
                    
                    $rows[] = $r;
                    
                }
                $forginPrimary=$this->applyQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME LIKE '". $table[0] ."' AND CONSTRAINT_NAME LIKE 'P%'");
                while($r1 = mysqli_fetch_assoc($forginPrimary)) {
                     
                    $curTable["key"] = $r1["COLUMN_NAME"];
                    
                }
                $curTable["rows"]=$rows;
                
                
               
                
                //print json_encode($curTable);
            }else{
                $res=array();
                $result=$this->applyQuery("SELECT * FROM ".$table[0]." WHERE ".$this->primary_key."=".$key);
        	

                $r = mysqli_fetch_assoc($result);
                foreach($r as $item){
                    array_push($res,$item);
                }
                
            }
            $curTable["item"]=$res;
            $curTable["datatypes"]=$this->getTableDatatypes($table[0]);
            $curTable["writable"]=$table[2];
            $curTable["hidden"]=array($this->joinedOn);
            $curTable["col_names"]=$this->getTableColumnNames($table[0]);
            $allTables[]=$curTable;
        }
        print json_encode($allTables);
        echo ',"key":';
        print json_encode($this->joinedOn);
        
        echo "}";
    }

    //Used by itemView.js when applying a change to a row
    private function applyEdit($request){

    }

    

    //Used to by ItemView.js to view or edit a related item to the current CommiteeView
    
    

    /*
    When called this function sends all the datatypes of the joined base tables

    */
    
    
    

    
    
	

}


?>