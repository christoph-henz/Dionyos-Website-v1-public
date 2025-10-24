// JavaScript Document
function isElementInViewport(element) {
 var rect = element.getBoundingClientRect();
 return (
  rect.top <= window.innerHeight - 100
 
 );
}

function isElementCompletelyInViewport(element) {
 var rect = element.getBoundingClientRect();
 return (
  rect.top <= window.innerHeight && rect.bottom >= 0
 );
}

var elements = document.querySelectorAll(".content-container");
var nav = document.querySelectorAll(".nav-item");
function callbackFunc() {
 for (var i = 0; i < elements.length; i++) {
  if (isElementCompletelyInViewport(elements[i])) {
 		elements[i].classList.add("content-container-v");
	  	nav[i].classList.add("nav-item-active");
  } else { 
   elements[i].classList.remove("content-container-v");
	  nav[i].classList.remove("nav-item-active");
  }
 }
}
 
window.addEventListener("load", callbackFunc);
window.addEventListener("scroll", callbackFunc);