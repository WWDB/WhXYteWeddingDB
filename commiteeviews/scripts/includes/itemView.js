/*
The item view is a bit intresting

It resides in a single html file, and paramaters about what part of the database it access's
are passed in as url paramaters, which the object reads. The reason for this is to obvoid creating a seperate editSuchAndSuchTableRow.php file
and insted pack the edit functions into the commiteeView class. 

The paramaters it accesses are 
    the url of the controller
    which action its doing [view_item, edit_item,add_item]
    and the primary key of the item.

    The controller at the controller url is responsible for verifying that the user is authoirzed to view and edit records of that table




*/


//Helper function which takes SQL datatypes, enurmenations and sets, and converts them into html input elements.
//Takes a string like 'int(20) unsigned' or 'varchar(5)' or 'enum('active','inactive') and creates an html <input>, or <select> element based on that datatype
//Yes I know it is quite messy, 
function columnType2Input(colType,id,value){
    //get everything before the parens
    primaryType=colType.split("(")[0]
    
    secondaryType=colType.match(/\(([^)]+)\)/)[1]//get the things inside the parens
    teritaryType=colType.split(")")[1]//get everything after the parens
    
    switch(primaryType){
        case "int":
            //Create a number input if its an int, make a min=0 for the number type if its unsigned
            if(teritaryType=="unsigned"){
                return '<input id='+id+'type="number" min="0" value='+value+'>'
            }
            return '<input id='+id+' type="number" value='+value+'>'
            break;
        case "decimal":
            //Make a number type that allows 0.01 value increments should be all we need for money related data
            return '<input id='+id+' type=number step=0.01 value='+value+' >'
            break;
        case "varchar":
            //creates a text input
            return '<input id='+id+' type=text value='+value+'>'
            break;
        case "enum":
            //Creates a dropdown from the values in the enum
            var html='<select id='+id+'>'
            var options=secondaryType.split(',');
            for(o in options){
                option=options[o]
                if(value==option){
                    html+='<option selected="selected">'+option+'</option>' 
                }else{
                    html+='<option>'+option+'</option>' 
                }
            }
            html+='</select>'
            return html;
            break;
        case "set":
        //Creates a dropdown that allows you to select multiple things
        //TODO: make this an array of checkboxes so it is not a pain to use.
        var html='<select multiple id='+id+'>'
        var options=secondaryType.split(',');
        for(o in options){
            option=options[o]
            if(value==option){
                html+='<option selected="selected">'+option+'</option>' 
            }else{
                html+='<option>'+option+'</option>' 
            }
        }
        html+='</select>'
        return html;
            break;
    }
    
}

function ItemView(divID){

    
    var query = window.location.search.substring(1).split("&");
    var GET = {};
    for (var i = 0, max = query.length; i < max; i++)
    {
        if (query[i] === "") // check for trailing & with no param
            continue;

        var param = query[i].split("=");
        GET[decodeURIComponent(param[0])] = decodeURIComponent(param[1] || "");
    }
    //alert(GET["action"])

    this.key=GET["key"]
    
    this.isAdding=false;
    if(!GET["key"]){
        this.isAdding=true;
        this.postData={
            action:GET["action"]
        }
    }else{
        this.postData={
            action:GET["action"],
            key:GET["key"]
        }
    }
    //alert(GET["action"])
    this.action=GET["action"];
    //I know this is a bit messy, but we do this so we can get the data to 'view' the item that we are editing
    if(this.action=="edit_item"){
        this.postData.action="view_item";
    }
    //alert(GET["action"])
    this.controllerURL=GET["curl"];
    this.divID=divID
    this.div=document.getElementById(divID);
    this.table=null;
    
    this.item_data=null;
    this.type_data=null;
    this.curResults=null;
    this.sortToggle=1;
    this.sortText=null;
    this.sortingIndex=0;
    
    
}

ItemView.prototype.draw =function(){
    //alert(this.action)
    switch(this.action){
        case "view_item":
            this.div.innerHTML="<h1>Currently Viewing</h1>"
            var cols=Object.keys(this.item_data.item)
            console.log(cols);
        
            for(col in cols){
                
                
                
                this.div.innerHTML+="<b>"+cols[col]+"<b>:"+this.item_data.item[cols[col]]+"<br>"
                
            }

            
            
        
            break;
        case "edit_item":
        this.div.innerHTML="<h1>Currently Editing</h1>"
        var cols=Object.keys(this.item_data.item)
        console.log(cols);
    
        for(var i=0; i<cols.length; ++i){
            //col=cols[i];
            
            //columnType2Input(this.type_data.datatypes[i],i,this.item_data.item[cols[i]])
            this.div.innerHTML+="<b>"+cols[i]+"<b>:"+columnType2Input(this.type_data.datatypes[i],i,this.item_data.item[cols[i]])+"<br>"
            
        }

            break;
        case "add_item":


        break;
        default:
            this.div.innerHTML="Error, invalid action"
    }

    this.div.innerHTML+="<br><a href="+ document.referrer +">Go Back</a>"

};

ItemView.prototype.init =function(){
    //alert(this.controllerURL)
    if(this.div==null){
        console.log("invalid div")
    }else{
        
        //We send a post request to the server
        ;

        ajax("POST",this.controllerURL,this.postData)
        
        .then(data=>{
            this.item_data=JSON.parse(data);
            //once we get the data on the item, we request data on the datatypes of the comumns
            ajax("POST",this.controllerURL,{action:"get_datatypes"})
            .then(data=>{
                this.type_data=JSON.parse(data);
                this.draw()
            },data=>{
                this.div.innerHTML="Item Load error: "+data
            })
        }
        
        ,data=>{
            this.div.innerHTML="Item Load error: "+data
        })

            
        
        
        
    }
    //window.history.replaceState({}, document.title, "/" + "baseEdit.html");
};

//Internal function that sorts the table by a column
