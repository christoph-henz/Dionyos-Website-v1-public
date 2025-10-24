function toggleFoldSection(caller){
    "use strict";
    var foldable = caller.parentElement.parentElement;
    var foldbutton = caller.parentElement;
    var content = foldbutton.nextElementSibling;
    if(caller.textContent === "aufklappen"){
        foldable.classList.remove("foldable");
        content.classList.remove("fold");
        foldbutton.style.display = "none";
    }
}

function calculateMenuHeight(){

}

window.addEventListener("load", function(){
   calculateMenuHeight();
});
