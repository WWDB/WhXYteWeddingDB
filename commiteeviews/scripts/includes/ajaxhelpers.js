//This makes an asyncrous call to the server
//It can be used to send data to the server without having to redirect the user to that page


const ajax=(method,url,data)=>{

    return new Promise((resolve,reject)=>{
        //We prepare a request
        const request=new XMLHttpRequest();
        //give it an event that will occur when the request completed(or fails)
        request.addEventListener('load',event=>{
        
                if(event.target.status>=400){
                    
                    reject(event.target.status+" "+event.target.statusText)

                }else{
                    resolve(event.target.response)
                }
            }
        

        )
        
    
    
        //we specifiy where to send the request, along with the method either GET or POST
        request.open(method,url)
        //If we want to send some data to the server then we pack it in our request
        if(data){
            //alert("eee")
            request.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            //alert(JSON.stringify(data))
            request.send(JSON.stringify(data));
            
        }else{
        //and send it off
            request.send()
        }
    })
    
}



/*

example use, requests somthing from some url
then does somthing cool with it...

ajax("GET","SOME_COOL_URL")
        .then(data=>{
            doSomthingCool(data)
        })
*/

