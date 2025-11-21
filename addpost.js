document.getElementById("category").addEventListener("keyup",(e)=>{
    e.preventDefault();
    var input_title=document.getElementById("category");
    const REG=/^[A-Za-z]{3,}$/;
    if(REG.test(input_title.value)==false){
        document.getElementById("errMes").textContent="at least 3 characters"
        document.getElementById("errMes").style.color="red"
    }else {document.getElementById("errMes").textContent="success"
        document.getElementById("errMes").style.color="green"
    }
})