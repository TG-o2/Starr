document.getElementById("postid").addEventListener("keyup",(e)=>{
    e.preventDefault();
    var input_title=document.getElementById("postid");
    const REG = /^[1-9]\d*$/;
    if(REG.test(String(input_title.value))==false){
        document.getElementById("errMes4").textContent="it needs to be positive"
        document.getElementById("errMes4").style.color="red"
    }else {document.getElementById("errMes4").textContent="success"
        document.getElementById("errMes4").style.color="green"
    }
})
document.getElementById("postid").addEventListener("change",(e)=>{
    e.preventDefault();
    var input_title=document.getElementById("postid");
    const REG = /^[1-9]\d*$/;
    if(REG.test(String(input_title.value))==false){
        document.getElementById("errMes4").textContent="it needs to be positive"
        document.getElementById("errMes4").style.color="red"
    }else {document.getElementById("errMes4").textContent="success"
        document.getElementById("errMes4").style.color="green"
    }
})