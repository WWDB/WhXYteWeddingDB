/*
TableView Class intended for the Whxyte Wedding Database



*/

//Constructor for tableView, takes in the id of the div element and a url to the controller or file
//it gets its data from
function TableView(divID,controllerURL){
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
    
}

//Internal function that sorts the table by a column
TableView.prototype.sortbycolumn = function() {
    //alert(colIndex)
    //this.data.rows.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
	var colIndex=this.sortingIndex;
    var attribute=Object.keys(this.curResults[0])[colIndex]
    //alert(attribute)
    this.sortToggle=!this.sortToggle
    
    var test=this.sortToggle
    this.sortText.textContent="Sorting by "+attribute+(this.sortToggle? " Descending":" Ascending")
    this.curResults.sort(function(a,b){
        if( test){
            
            if(a[attribute].toUpperCase()<b[attribute].toUpperCase()){
                return 1
            }else{
                return -1
            }
        }else{
            
            if(a[attribute].toUpperCase()>b[attribute].toUpperCase()){
                return 1
            }else{
                return -1
            }
        }
    })
    
    this.updateTable()
    return 1
};

//internal function that searchs in the table, invoked by the search button
TableView.prototype.search = function() {
    //alert("aaa")
    //alert(this.selectSearchBy.value)
    //console.log(this.data.rows)
    var index=this.selectSearchBy.value;
    var rows=this.data.rows
    var attribute=Object.keys(rows[0])[index]
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
function test(){
    alert("eee")
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
            
        
            var sortButton=document.createElement('button') 
            
            sortButton.innerHTML=  cols[col]
            
            sortButton.id=+col+" "+this.divID+"_sortBtn "

            sortButton.addEventListener("click",function(event){
                curTable.sortingIndex=parseInt(this.id);
                curTable.sortbycolumn()
                //alert(parseInt(this.id))
            });
            cell = hrow.insertCell()
            cell.appendChild(sortButton)
        
        
        }
    
            
           
    }
    hrow.insertCell()
    var tbody=this.table.createTBody()

    //Draw the rows
    for(var i=0; i<this.curResults.length; ++i){
        var htmlRow= tbody.insertRow();
        var row=this.curResults[i]
        //alert(JSON.stringify(tableRows[i]))
        var key=0
        
        for(att in row){
            
            if(!this.data.hidden.includes(att)){

                var cell=htmlRow.insertCell()
                cell.innerHTML=row[att]
            }
        }
        var cell=htmlRow.insertCell()
        var key=row[this.data.key]
        
        var MoreLink="<a href='baseEdit.html?curl="+this.controllerURL+"&action=view_item&key="+key+"'>More</a>"
        var EditLink="<br><a href='baseEdit.html?curl="+this.controllerURL+"&action=edit_item&key="+key+"'>Edit</a>"
        
        cell.innerHTML=MoreLink+EditLink  
    }    
};

//This function draws the table
TableView.prototype.draw=function(){
    this.div.innerHTML=""
    this.searchAndAdd=document.createElement('span');
    this.searchBox=document.createElement('input');
    this.searchBox.type="text";
    this.searchAndAdd.appendChild(this.searchBox);

    this.searchAndAdd.appendChild(document.createTextNode(" Search by: "))
    this.selectSearchBy=document.createElement('select');
    var cols=Object.keys(this.data.rows[0])
	console.log(cols);
	
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
    addButton=document.createElement("a")
    addButton.innerHTML="Add"
    addButton.href='baseEdit.html'
    this.searchAndAdd.appendChild(addButton);
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
TableView.prototype.init = function() {
    
    if(this.div==null){
        console.log("invalid div")
    }else{
        
        //We send a post request to the server
        
        ajax("POST",this.controllerURL,{action:"view_table"})
        //Once we recieve the server response to our rquest, we draw our table with the data we got
        .then(data=>{
            this.data=JSON.parse(data);
            this.draw()
        },data=>{
            this.div.innerHTML="Table Load error: "+data
        })
            
        
        
        
    }
    
    
};



