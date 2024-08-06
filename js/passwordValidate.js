function validate(){
    var pass = document.getElementById("firstPassword");
    var message = document.getElementById("validateMessage");

    if(pass.value.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,}$/)){
        message.style.display = "none";
    }else{
        message.style.display = "block";
    }
}

function conform(){
    var pass1 = document.getElementById("firstPassword");
    var pass2 = document.getElementById("secondPassword");

    if(pass1.value != pass2.value){
        document.getElementById("conformMessage").style.display = "block";
    }else{
        document.getElementById("conformMessage").style.display = "none";
    }
}