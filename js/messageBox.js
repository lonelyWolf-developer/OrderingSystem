var messageB = document.getElementById("messageBoxTimer");
var icoDiv = document.getElementById("icons");

if(icoDiv != null && messageB != null){
    icoDiv.addEventListener("click", function(){            
        messageB.style.display = "none";
    });

    setTimeout(function(){
        messageB.style.display = "none";
    }, 3000);
}