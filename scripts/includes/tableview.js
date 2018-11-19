/*
TableView Class intended for the Whxyte Wedding Database

This is used in all of the commitee views, 
and is also used in the itemView to show relationships and to select pre existing items to add to relationships

*/

//Constructor for tableView, takes in the id of the div element and a url to the controller or file
//it gets its data from


function TableView(divID,controllerURL,add_only,name){
    if(name!=null){
        this.subItemName=name;
    }
    
    this.add_only=add_only;
    this.controllerURL=controllerURL
    this.divID=divID
    this.div=document.getElementById(divID);
    this.table=null;
    this.controllerURL=controllerURL;
    this.data=null;
    this.curResults=null;
    this.sortToggle=1;
    this.sortText=null;
    this.sortingIndex=0;
    
    this.checkedItems=[]//used if add_only is enabled
}


TableView.prototype.exportCSV = function() {

    /*
    here are all the rows currently displayed in the table
    it changes depending on what the table is being sorted by
    and what is being searched

    Hidden columns are also in the curResult, you can filter them out if you want 
    */
    var rows=this.curResults;
    alert("Not implemented yet, edit line 44 in tableView.js");
}


//Internal function that sorts the table by a column
//TODO: fix issue when sorting by columns that contain null values
TableView.prototype.sortbycolumn = function() {
    //alert(colIndex)
    //this.data.rows.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
	var colIndex=this.sortingIndex;
    var attribute=Object.keys(this.curResults[0])[colIndex];
    //alert(attribute)
    this.sortToggle=!this.sortToggle;
    
    /*
    Sorting in javascript is messy, the sort function takes in a -1 or 1 insted of a simple
    less-then function like most other languages. So we create a funcction that returns 1 
    if a<b and -1 otherwise, it stitches weather the 'sort toggle' is enabled
    the sort toggle is switched by clicking the column header button again
    */
    var test=this.sortToggle
    this.sortText.textContent="Sorting by "+attribute+(this.sortToggle? " Descending":" Ascending");
    this.curResults.sort(function(a,b){
        if(test){
            
            if(a[attribute].toUpperCase()<b[attribute].toUpperCase()){
                return 1;
            }else{
                return -1;
            }
        }else{
            
            if(a[attribute].toUpperCase()>b[attribute].toUpperCase()){
                return 1;
            }else{
                return -1;
            }
        }
    })
    
    //After we sort we update the table to show the new sorted rows
    this.updateTable()
    
};

//internal function that searchs in the table, invoked by the search button
TableView.prototype.search = function() {
    //alert("aaa")
    //alert(this.selectSearchBy.value)
    //console.log(this.data.rows)
    var index=this.selectSearchBy.value;
    var rows=this.data.rows;
    var attribute=Object.keys(rows[0])[index];
    var query=this.searchBox.value;
    var results=[]
    for(var i=0; i<rows.length; ++i){

        //console.log(rows[i][attribute])
        if(rows[i][attribute].match(query)){
            results.push(rows[i])
        }
    }
    
    
    this.curResults=results;
    this.updateTable()
    //console.log(results)
};



TableView.prototype.updateChecked=function(id){
    //this.checkedItems[key] = event.value;
    var isChecked=document.getElementById(id).checked;
    if(isChecked){
        if(curTable.add_only==1){
            
            for(key in curTable.checkedItems){

                document.getElementById(curTable.checkedItems[key]).checked=false;
                
            }
            curTable.checkedItems=[]

           // this.checkedItems=[]
        }
        curTable.checkedItems.push(id);
    }else{
        
        curTable.checkedItems=curTable.checkedItems.filter(function(value, index, arr){
           
            return value !=id;
        
        });
        
       
    }
    //alert(event.value)
}
TableView.prototype.getChecked=function(){
    return this.checkedItems;
}

TableView.prototype.updateTable = function() {
    this.table.innerHTML=""
    var head=this.table.createTHead();
    var hrow = head.insertRow();
    //Draw the columns in the header
    curTable=this
	var cols=Object.keys(this.data.rows[0])
    for(col in cols){
        if(!this.data.hidden.includes(cols[col])){
            
        
            var sortButton=document.createElement('button');
            
            sortButton.innerHTML=  cols[col];
            
            sortButton.id=+col+" "+this.divID+"_sortBtn ";

            sortButton.addEventListener("click",function(event){
                curTable.sortingIndex=parseInt(this.id);
                curTable.sortbycolumn();
                //alert(parseInt(this.id))
            });
            cell = hrow.insertCell();
            cell.appendChild(sortButton);
        
        
        }
            
           
    }
    if(this.add_only){
        hrow.insertCell().innerHTML="Selected";
    }else{
        hrow.insertCell().innerHTML="Options";
    }
	
    var tbody=this.table.createTBody();

    //Draw the rows
    for(var i=0; i<this.curResults.length; ++i){
        var htmlRow= tbody.insertRow();
        var row=this.curResults[i];
        //alert(JSON.stringify(tableRows[i]))
        var key=0;
        
        for(att in row){
            
            if(!this.data.hidden.includes(att)){

                var cell=htmlRow.insertCell();
                cell.innerHTML=row[att];
            }
        }
        var cell=htmlRow.insertCell();
        var key=row[this.data.key];
        if(!this.add_only){
            if(!this.subItemName){
                var MoreLink="<a href='baseEdit.html?curl="+this.controllerURL+"&action=view_item&key="+key+"'>More</a>";
                var EditLink="<br><a href='baseEdit.html?curl="+this.controllerURL+"&action=edit_item&key="+key+"'>Edit</a>";
                if(this.data.admin_deleting_is_allowed==1){
                    var DeleteLink="<br><a href='baseEdit.html?curl="+this.controllerURL+"&action=delete_item&key="+key+"'>Delete</a>";
                    cell.innerHTML=MoreLink+EditLink+DeleteLink;  
                }else{
                    cell.innerHTML=MoreLink+EditLink;
                }
            }else{
                var MoreLink="<a href='baseEdit.html?curl="+this.controllerURL+"&action=view_item&hassub=1&subitem="+this.subItemName+"&key="+key+"'>More</a>";
                var EditLink="<br><a href='baseEdit.html?curl="+this.controllerURL+"&hassub=1&subitem="+this.subItemName+"&action=edit_item&key="+key+"'>Edit</a>";
                if(this.data.admin_deleting_is_allowed==1){
                    var DeleteLink="<br><a href='baseEdit.html?curl="+this.controllerURL+"&hassub=1&action=delete_item&subitem="+this.subItemName+"&key="+key+"'>Delete</a>";
                    cell.innerHTML=MoreLink+EditLink+DeleteLink;  
                }else{
                    cell.innerHTML=MoreLink+EditLink;
                }
            }
        }else{
            
            
            //console.log(curTable.checkedItems)
            var checkBox=document.createElement("input")
            if(this.checkedItems.includes(key)){
                checkBox.checked=true;
            }
            checkBox.type="checkbox";
            checkBox.id=key;
            cell.appendChild(checkBox)
            //curTable=this;
            //let hash = require('string-hash')
            var curTable=this;
            checkBox.addEventListener("click",function(event){
                curTable.updateChecked(event.target.id)
            });

        }
    }  
};

//This function draws the table
TableView.prototype.draw=function(){
    this.div.innerHTML="";
    this.searchAndAdd=document.createElement('span');
    this.searchBox=document.createElement('input');
    this.searchBox.type="text";
    this.searchAndAdd.appendChild(this.searchBox);

    this.searchAndAdd.appendChild(document.createTextNode(" Search by: "))
    this.selectSearchBy=document.createElement('select');
    var cols=Object.keys(this.data.rows[0])
	//console.log(cols);
	
    for(col in cols){
        
        var option=document.createElement('option');
        option.value=col
        option.innerHTML=cols[col]
        this.selectSearchBy.appendChild(option)
    }
    
    
    //this.searchBox=document.createElement('input');
    this.searchAndAdd.appendChild(this.selectSearchBy);

    searchButton=document.createElement("button")
    searchButton.innerHTML="Search"
    this.searchAndAdd.appendChild(searchButton);
	clearButton=document.createElement("button")
    clearButton.innerHTML="Clear"
	
    this.searchAndAdd.appendChild(clearButton);

    exportButton=document.createElement("button")
    exportButton.innerHTML="Export as CSV"
    
    this.searchAndAdd.appendChild(exportButton);
    if(!this.add_only){
        addButton=document.createElement("a")
        addButton.innerHTML="Add"
        if(this.subItemName){
        addButton.href="baseEdit.html?curl="+this.controllerURL+"&action=prepare_add_item&hassub=1&subitem="+this.subItemName
        }else{
            addButton.href="baseEdit.html?curl="+this.controllerURL+"&action=prepare_add_item"
        }
        this.searchAndAdd.appendChild(addButton);

    }

    this.sortText=document.createTextNode("")
    this.searchAndAdd.appendChild(this.sortText)
    this.div.appendChild(this.searchAndAdd);
    this.table=document.createElement('table')
    this.div.appendChild(this.table);
    var rows=this.data.rows
    this.curResults=rows
	this.sortbycolumn();
    this.updateTable()
    curTable=this
    
    searchButton.addEventListener("click",function(event){
        curTable.search()
    });
	clearButton.addEventListener("click",function(event){
		curTable.searchBox.value="";
		
        curTable.search();
    });

    exportButton.addEventListener("click",function(event){
		//curTable.searchBox.value="";
		
        curTable.exportCSV();
    });

    this.searchBox.onkeypress=function(event){
        var key=event.keyCode||event.which
        console.log(key)
        if(key==13){
            console.log("searching")
            curTable.search()
        }
    }
    
}


//this function querys for the data to populate the table, then draws the
//table, search bar, add button, and sortingByMessage
//we can pass in a dataOveride which will cause the table to use that data insted of getting it from the server via ajax
//the dataOveride,urlOveride, and is_subitem paramaters
//are used in tableViews that are embedded in an item view, in which the item view already has the data needed
//and the view and edit functions need to look at a related item which uses the same backend as the parent item being viewed in the itemView

TableView.prototype.init = function(dataOveride,urlOveride) {
    
    
    if(urlOveride!=null){
        this.controllerURL=urlOveride;
    }
    if(this.div==null){
        console.log("invalid div")
    }else{
        if(dataOveride==null){
            /*If add_only is selected then the tableView
            turns into a giant select or select_multiple
            where the user can select somthing from the table
            searching sorting, and exporting are still kept for convience'

            the add_only is only used in itemViews in which the tableView is used to select a pre-existing item to add to a relationship

            using a tableView insted of a giant dropdown or select-multi will make adding things from giant tables eaiser.

            */
            if(this.add_only){
                
                pd={

                    action:"get_add_selection",
                    subitem:this.subItemName
                }
            }else{
                pd={action:"view_table"}
            }
            //We send a post request to the server
            
            ajax("POST",this.controllerURL,pd)
            //Once we recieve the server response to our rquest, we draw our table with the data we got
            .then(data=>{
                this.data=JSON.parse(data);
                this.draw()
            },data=>{
                this.div.innerHTML="Table Load error: "+data

            })
            
        
    }else{

        this.data=dataOveride;
        this.draw()
    }
        
    }
    
    
};



