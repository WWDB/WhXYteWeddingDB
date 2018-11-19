/*
    ItemView.js
*/
var script = document.createElement("script");  // create a script DOM node
script.src = "scripts/includes/autoForm.js";  // set its src to the provided URL
document.head.appendChild(script); 
script = document.createElement("script");  // create a script DOM node
script.src = "scripts/includes/tableView.js";  // set its src to the provided URL
document.head.appendChild(script); 

function ItemView(divID){

    this.subitem=null;
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
        if(GET["hassub"]){
            this.postData={
                action:GET["action"],
                subitem:GET["subitem"]
            }
        }else{
        this.postData={
            action:GET["action"]
        }
    }
    }else{
        if(GET["hassub"]){
            this.postData={
                action:GET["action"],
                subitem:GET["subitem"],
                key:GET["key"]
            }
        }else{
            this.postData={
                action:GET["action"],
                key:GET["key"]
            }
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
    
    this.data=null;
    
    this.curResults=null;
    this.sortToggle=1;
    this.sortText=null;
    this.sortingIndex=0;

    this.addMenuStep=0;
    this.isEnteringNew=false;
    
}
ItemView.prototype.draw =function(){
    this.div.innerHTML="";
    //console.log(this.data);
    var tableViews=[];
    
    //this.div.appendChild(itemForm)
    //curTitle=document.createElement("H1");
    //curTitle.innerHTML="Multivalued attributes for this item";
    //this.div.appendChild(curTitle)
    if(this.data.tables){
       
        var ft=this.data.tables;
        if(this.action=="edit_item"||this.action=="view_item"){
            for(var i=0; i<this.data.tables.length; ++i){
                //console.log("created table")
                var curTitle=document.createElement("H2");
                curTitle.innerHTML=ft[i].name;
                //this.div.appendChild(curTitle)
                var curDiv=document.createElement("DIV");
                curDiv.id=i
                this.div.appendChild(curDiv)
                if(ft[i].displayMode==2){
                    var cur_table=new TableView(curDiv.id,"",0,ft[i].name);
                    //console.log(ft[i])
                    cur_table.init(ft[i],this.controllerURL,ft[i].name);
                }else{
                    var itemForm=document.createElement("DIV");
                    console.log(this.action)
                    
                    var form=new AutoForm(itemForm,this.action,ft[i].item,ft[i].col_names,ft[i].datatypes,ft[i].writable)
                    this.div.appendChild(itemForm);
                    form.draw();
                }

            }
        }else{
            var curItemView=this;
            if(this.postData.action=="prepare_add_item"){
                
                /*ADD_PRIVILAGE options
                if set to 0 then adding is disabled for that base table
                if set to a 1 then the user cannot add a new item but can select a pre existing item(whose forgin key in the relationship is null)
                if set to a 2 then the user cannot add a new item but can select multiple pre existing items(whose forgin key in the relationship is null)
                if set to a 3 then the user is allowed to create a new item only
                if set to a 4 the user is given a choice to select a pre existing item(again whose forgin key is null) or they can add a new item
               
                */

               
               if(this.addMenuStep<=this.data.tables.length){
                   var ft=this.data.tables[this.addMenuStep];
                    switch(ft.addMode){
                        case 0:
                            this.div.innerHTML="<h1>adding to this table is disabled</h1>"
                        break;
                        case 1:
                            this.div.innerHTML="<h1>Please select a "+ft.name+"</h1><br>"
                            var tv=document.createElement("DIV")
                            tv.id="tv"
                            this.div.appendChild(tv);
                            var cur_table=new TableView("tv",this.controllerURL,1,ft.name);
                            cur_table.init();
                        break;
                        case 2:
                            this.div.innerHTML="<h1>Please select a "+ft.name+"</h1><br>"
                            var tv=document.createElement("DIV")
                            tv.id="tv"
                            this.div.appendChild(tv);
                            var cur_table=new TableView("tv",this.controllerURL,2,ft.name);
                            cur_table.init();
                        break;
                        case 3:
                            
                                //console.log("created table")
                            
                            var itemForm=document.createElement("DIV")
                                    
                            var form=new AutoForm(itemForm,this.action,"",ft.col_names,ft.datatypes,ft.col_names)

                            this.div.appendChild(itemForm);
                            form.draw();
                            
                            
                                
                            
                        break;
                        case 4:
                            this.div.innerHTML="<h1>Please select a "+ft.name+" or click here to add a new one</h1><br>"
                            var tv=document.createElement("DIV")
                            tv.id="tv"
                            this.div.appendChild(tv);
                            var cur_table=new TableView("tv",this.controllerURL,1,ft.name);
                            cur_table.init();
                        break;
                   }
                }
            }
                   
                
            
            
        }
        //this.div.appendChild()

    }
    
}


ItemView.prototype.init =function(){
    //alert(this.controllerURL)
    if(this.div==null){
        console.log("invalid div")
    }else{
        
        //We send a post request to the server
        var pd=this.postData;
        
       

        ajax("POST",this.controllerURL,pd)
        
        .then(data=>{
            
            this.data=JSON.parse(data);
            curItemView=this;
    
            curItemView.draw();
        }
        
        ,data=>{
            this.div.innerHTML="ItemView.js AJAX Load error: "+data+"<br> Request info: "+JSON.stringify(this.postData) +"<br> Controller URL:"+this.controllerURL;
        })

        
            
        
        
        
    }
    //window.history.replaceState({}, document.title, "/" + "baseEdit.html");
};
