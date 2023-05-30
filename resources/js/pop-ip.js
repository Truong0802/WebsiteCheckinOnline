let divideall = document.getElementById("divideall");
let divide3 = document.getElementById("divide3");

divideall.addEventListener("change", function() {
    if (this.checked) 
    {
        divide3.checked = false;
    }
});

divide3.addEventListener("change", function() {
    if (this.checked) 
    {
        divideall.checked = false;
    }
});