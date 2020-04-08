/**
 * 
 * @formId {STRING : ID of the form element} formId  
 * @res {CallableFunction : callback for response json} res
 * @fullDetail {int : show all details of response?}
 * 
 */

function ajax_request(formId ,res,fullDetail=0){
    const req=new XMLHttpRequest();
    const form=document.getElementById(formId);
    const formData=new FormData();

    const inputs=document.querySelectorAll("#"+formId+" input")
    
    for(var a=0;a<inputs.length;a++){
        if(inputs[a].name && inputs[a].value){
            formData.append(inputs[a].name, inputs[a].value);
        }
    }




    
        
    req.open(form.attributes.method.value,form.attributes.action.value);  
    req.send(formData);

    form.addEventListener("submit",function (e) {
        e.preventDefault();
        return 0;
    });

    req.onreadystatechange=function (e) {
        e=e.srcElement;


        if(e.readyState == 4 && e.status == 200 ){
            
            
            if(fullDetail){
                res(e);
            } else {

                var _json_return={};
    
                _json_return["responseURL"]=e["responseURL"];
                _json_return["response"]=e["response"];
                try{
    
                    _json_return['json']=JSON.parse(e.responseText);
                
                } catch {
    
                    _json_return['json']={};
                
                }
    
    
                res(_json_return);
            }

        }
        
    }
    
}


