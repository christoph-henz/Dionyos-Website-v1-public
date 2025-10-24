// JavaScript Document


var containers = document.getElementsByClassName('menu-item');

for(let i = 0; i < containers.length; i++){
	if(containers[i].children.length >= 5){
		var number = containers[i].children[0].childNodes[0].nodeValue;
		var amount = containers[i].children[3].childNodes[1].value;
		var bnt = containers[i].children[4].childNodes[1];
		
		(function (){
			var n = number;
			var a = amount;
			
			bnt.addEventListener("click", function(){
			handleCartButton(n,a);
		});
		}());
		
	}

}




function goRight(){
	
	var active = document.getElementsByClassName('menu-site menu-site-active');
	var cur = active[0]; 
	
	if(cur.nextSibling.nextSibling != null && cur.nextSibling.nextSibling.id != "btnl" ){
		cur.classList.remove('menu-site-active');
		cur.classList.add('menu-site-inactive');
		var next = cur.nextSibling.nextSibling;
		next.classList.remove('menu-site-inactive');
		next.classList.add('menu-site-active');
	}
	
}

function goLeft(){
	var active = document.getElementsByClassName('menu-site menu-site-active');
	var cur = active[0]; 

	if(cur.previousSibling.previousSibling !== null){
		cur.classList.remove('menu-site-active');
		cur.classList.add('menu-site-inactive');
		var next = cur.previousSibling.previousSibling;
		next.classList.remove('menu-site-inactive');
		next.classList.add('menu-site-active');
	}
	
}


function initTouchEvents(){
	let display = document.getElementsByClassName("menu-display");
	let startX;
	let endX;

	display[0].addEventListener("touchstart", function(eve){
		let touchobj = eve.changedTouches[0]; // erster Finger
		startX = parseInt(touchobj.clientX); // X/Y-Koordinaten relativ zum Viewport
	});
	display[0].addEventListener("touchend", function(eve){
		let touchobj = eve.changedTouches[0]; // erster Finger
		endX = parseInt(touchobj.clientX); // X/Y-Koordinaten relativ zum Viewport

		let touchOffset= (getDeviceWidth() / 10).toFixed(0);
		if(startX - endX < - touchOffset){
			goLeft();
		}else if((startX - endX) > touchOffset){
			goRight();
		}

	});
	console.log(getBrowserAsString());
}

function getDeviceWidth(){
	return (window.innerWidth > 0) ? window.innerWidth : screen.width;
}

function getDeviceHeight(){
	return (window.innerHeight > 0) ? window.innerHeight : screen.height;
}

function getBrowserAsString(){
	let userAgent = navigator.userAgent;
	let browserName;

	if(userAgent.match(/chrome|chromium|crios/i)){
		browserName = "chrome";
	}else if(userAgent.match(/firefox|fxios/i)){
		browserName = "firefox";
	}  else if(userAgent.match(/safari/i)){
		browserName = "safari";
	}else if(userAgent.match(/opr\//i)){
		browserName = "opera";
	} else if(userAgent.match(/edg/i)){
		browserName = "edge";
	}else{
		browserName="No browser detection";
	}

	return browserName;

}

function setMenuHeight(){
	let display1 = document.getElementsByClassName("menu-display")[0];
	display1.style.height = getDeviceHeight() + "px";
	display1.style.maxHeight = getDeviceHeight() + "px";
	display1.style.minHheight = getDeviceHeight() + "px";
}

window.addEventListener("load", function(){
	initTouchEvents();
});



