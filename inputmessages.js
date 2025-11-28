document.getElementById("content").addEventListener("keyup",(e)=>{
    e.preventDefault();
    var input_title=document.getElementById("content");
    const REG=/^[A-Za-z]{3,}$/;
    if(REG.test(input_title.value)==false){
       
         document.getElementById("errMes3").textContent="at least 3 characters"
        document.getElementById("errMes3").style.color="red"
    }else {document.getElementById("errMes3").textContent="success"
        document.getElementById("errMes3").style.color="green"
    }
})
document.getElementById("mesid").addEventListener("keyup",(e)=>{
    e.preventDefault();
    var input_title=document.getElementById("mesid");
    const REG = /^[1-9]\d*$/;
    if(REG.test(String(input_title.value))==false){
        document.getElementById("errMes4").textContent="it needs to be positive"
        document.getElementById("errMes4").style.color="red"
    }else {document.getElementById("errMes4").textContent="success"
        document.getElementById("errMes4").style.color="green"
    }
})
document.getElementById("mesid").addEventListener("change",(e)=>{
    e.preventDefault();
    var input_title=document.getElementById("mesid");
    const REG = /^[1-9]\d*$/;
    if(REG.test(String(input_title.value))==false){
        document.getElementById("errMes4").textContent="it needs to be positive"
        document.getElementById("errMes4").style.color="red"
    }else {document.getElementById("errMes4").textContent="success"
        document.getElementById("errMes4").style.color="green"
    }
})