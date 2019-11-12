
function func(a){
    var eId = a.getAttribute('eId');
    var eName = a.getAttribute('eName');
    var ePhone = a.getAttribute('ePhone');
    var eEmail = a.getAttribute('eEmail');
    var eTag = a.getAttribute('eTag');

   
   
    var id = document.getElementById("eId");
    var name = document.getElementById("e_name");
    var phone = document.getElementById("e_phone");
    var email = document.getElementById("e_email");
    var tagP = document.getElementsByClassName('tagP');
    
    var TagL = JSON.parse(eTag);
    var arrNum = [...TagL].map(e=>{
        return parseInt(e);
    });
    [...tagP].forEach(e=>{   
        e.checked = false;   
    });
    if(arrNum.length > 0){
        [...tagP].forEach(e=>{  

            if(arrNum.indexOf(parseInt(e.value)) > -1){  
                e.checked = true;
            }    
        });
    }
    id.value = eId;
    name.value = eName;
    phone.value = ePhone;
    email.value = eEmail;

}
function funcTag(a){
    var tId = a.getAttribute('tId');
    var tName = a.getAttribute('tName');
   
    
    var id = document.getElementById("e_idtag");
    var name = document.getElementById("e_tagname");

    
    id.value = tId;
    name.value = tName;
    
}

