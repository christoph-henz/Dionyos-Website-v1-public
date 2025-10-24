// JavaScript Document

function processDeliveryOrder(form, name, tel, email, note, dsgvo, agb, str, plz, city){
	
	if(name.value == '' || tel.value == '' || !dsgvo.checked || !agb.checked || str.value == '' || plz.value == '' || city.value == '' ){
		alert("Daten unvollständig");
		return false;
	}else{
		form.submit();
		return true;
	}
}

function processPickupOrder(form, name, tel, email, note, dsgvo, agb){
	

	
	if(name.value == '' || tel.value == '' || !dsgvo.checked || !agb.checked){
		alert("Daten unvollständig");
		return false;
	}else{
		form.submit();
		return true;
	}
	
}

function toggleDeliveryType(){
	let delSec = document.getElementsByClassName("delivery-elem");
	let pcpbtn = document.getElementById("pcpbtn");
	for(let i of delSec){
		i.classList.toggle("visible");
	}

	pcpbtn.classList.toggle("invisible");
}