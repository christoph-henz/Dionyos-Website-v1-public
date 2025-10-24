// JavaScript Document

function onClickMen(i){


	var a_elem = document.getElementsByClassName("content-display-container")[i];
	var active = document.getElementsByClassName("content-active")[0];
	
	var a_it = document.getElementsByClassName("item-active")[0];
	var it = document.getElementsByClassName("content-item")[i];
	
	active.classList.remove("content-active");
	active.classList.add("content-inactive");
	
	a_elem.classList.remove("content-inactive");
	a_elem.classList.add("content-active");
	
	a_it.classList.remove("item-active");
	it.classList.add("item-active");

	var foldbutton = a_it.parentElement.parentElement.previousElementSibling;
	var cbox = a_it.parentElement.parentElement;
	cbox.style.position="relative";
	cbox.style.top="0";

}

function alertMessage(message){
	alert(message);
}

function getMaxHeight(displays){
	var maxHeight = 0;
	for(var elem in displays){
		if(elem.clientHeight > maxHeight){
			maxHeight = elem.clientHeight;
		}
	}
	return maxHeight;
}