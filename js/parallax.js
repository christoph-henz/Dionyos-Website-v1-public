// JavaScript Document
"use strict";
var offset1;
var paral;
window.addEventListener("load", function (){
	paral = document.getElementsByClassName("parallax")[0];
	offset1 = paral.offsetTop;
});
window.addEventListener("scroll", function(){
	var offset = window.pageYOffset;
	paral.style.backgroundPositionY = (offset - offset1) * 0.5 + "px";

});
