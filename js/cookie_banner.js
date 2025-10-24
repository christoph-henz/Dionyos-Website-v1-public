// JavaScript Document


function showCookieBanner($google, $order){
	"use strict";
	var body = document.getElementsByTagName("body")[0];
	var container = document.createElement("div");
	var text = document.createElement("p");
	
	var form = document.createElement("form");
	var labelGoogle = document.createElement("label");
	var labelOrder = document.createElement("label");
	var checkboxGoogleHidden = document.createElement("input");
	var checkboxGoogle = document.createElement("input");
	var checkboxOrderHidden = document.createElement("input");
	var checkboxOrder = document.createElement("input");
	var submit = document.createElement("input");
	
	container.id = "banner";
	
	text.textContent = "Wir nutzen Cookies, um Ihnen ein möglichst angenehmes Erlebnis auf unserer Webpräsenz bieten zu können. Wir nutzen auf dieser Webseite Google Fonts und Google Maps, auf deren Cookieverwaltung wir keinen Einfluss haben. Um die Bestellfunktion zu nutzen, werden ebenfalls Cookies benötigt, um Bestellungen zu übermitteln. Wenn Sie ensprechende Funktionen deaktivieren, können Sie diese nicht weiter nutzen. Im Footerbereich der Webseite können Sie jederzeit Ihre Einstellungen anpassen";
	
	labelGoogle.textContent = "Cookies über Google akzeptieren";
	labelGoogle.setAttribute("for", "accept-google");

	labelOrder.textContent = "Cookies über Bestellsystem akzeptieren";
	labelOrder.setAttribute("for", "accept-order");

	// checkbox google init
	checkboxGoogle.setAttribute("value", "1");
	checkboxGoogle.setAttribute("type", "checkbox");
	if($google){
		checkboxGoogle.setAttribute("checked", "checked");
	}
	checkboxGoogle.setAttribute("name", "accept-google");

	//checkbox order init
	checkboxOrder.setAttribute("value","1");
	checkboxOrder.setAttribute("type", "checkbox");
	if($order){
		checkboxOrder.setAttribute("checked", "checked");
	}
	checkboxOrder.setAttribute("name", "accept-order");

	//hidden init
	checkboxOrderHidden.setAttribute("type", "hidden");
	checkboxOrderHidden.setAttribute("name", "accept-order");
	checkboxOrderHidden.setAttribute("value", "0");

	checkboxGoogleHidden.setAttribute("type", "hidden");
	checkboxGoogleHidden.setAttribute("name", "accept-google");
	checkboxGoogleHidden.setAttribute("value", "0");

	//submit init
	submit.setAttribute("type", "submit");
	submit.setAttribute("value", "Einstellungen übernehmen");
	
	form.setAttribute("method", "post");
	
	body.appendChild(container);
	container.appendChild(text);
	container.appendChild(form);
	
	form.appendChild(labelGoogle);
	form.appendChild(checkboxGoogleHidden);
	form.appendChild(checkboxGoogle);
	
	form.appendChild(labelOrder);
	form.appendChild(checkboxOrderHidden);
	form.appendChild(checkboxOrder);
	form.appendChild(submit);
	container.style.bottom = (-1) * window.pageYOffset + "px";
		
	window.onscroll = function(){
		
		var banner = document.getElementById("banner");
		banner.style.bottom = (-1) * window.pageYOffset + "px";
	};
	
	form.addEventListener("submit", function(){
		
	});
	
}
