window.addEventListener("load", () => {
    const contracts = document.querySelectorAll("#deleteContractButton");

    for (const contract of contracts){
        contract.addEventListener("click", function (e){            
            if(!confirm("Opravdu si přejete smazat objednávku?")){
                e.preventDefault();
            }
        });
    }
});