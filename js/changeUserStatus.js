window.addEventListener("load", () => {
    const contracts = document.querySelectorAll("#changeStatusButton");

    for (const contract of contracts){
        contract.addEventListener("click", function (e){            
            if(!confirm("Opravdu si přejete změnit status uživatele?")){
                e.preventDefault();
            }
        });
    }
});