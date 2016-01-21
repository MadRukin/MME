/* Main.js
   Funktionalität für Pfandhelfer.de 
   @author Katharina Bolinski, Florian Krüllke*/

//Sobald alle Inhalte geladen wurden, werden die Buttons
//initialisiert und das Pfandformular neu aufgesetzt.
$(document).ready(function(){

	$('[data-toggle="modal"]').on("click", openLogin);
	$("#plus-pfand").on("click", plusPfand);
	$('#reset').on("click", resetInputs);
	$(".about-skip").on("click", aboutSkip);
	$("#playTutorial").on("click", playTutorial);

	$(".form-person").find(".form-control").on("focusout",checkInput);
	$(".form-login").find(".form-control").on("focusout",checkInput);

	plusPfand();

	//Versteckt ersten Pfandauswahl-Entfernen-Button
	$("#pfandliste li span")[0].style.display = "none";

	//makeMap();
});
var barAni;
function stopTut(event){
	$("#bar").stop();
	clearTimeout(barAni);
}

function hreffer(event){
	window.location = event.target.href;
}

// map.js

function makeMap(){
var map = new L.Map('map');
  var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
  osmAttrib = 'Map data &copy; 2011 OpenStreetMap contributors',
  osm = new L.TileLayer(osmUrl, {maxZoom: 18, attribution: osmAttrib});

  map.setView(new L.LatLng(52.55050, 13.35900), 16).addLayer(osm);
}

	                  var map, osm;
//latitude: 52.5517544
//longitude: 13.3580867

function locateUser(event){
    if (navigator.geolocation) {
    	navigator.geolocation.getCurrentPosition(showPosition);
	} else {
	    alert("Geolocation is not supported by this browser.");
	}
}

 function showPosition(position){
    map.setView(new L.LatLng(position.coords.latitude, position.coords.longitude), 16).addLayer(osm);
    L.marker([position.coords.latitude, position.coords.longitude]).addTo(map)
	.bindPopup('<strong>Ist das deine aktuelle Position?</strong><br>'
		    + '<a href="#pfandspende"><button class="btn-xs btn-info">Ja</button></a>'
		    + '<a  href="#"><button class="btn-xs btn-danger">Nein</button></a></div>')
	.openPopup();
 }

// Initialisiert das Pfandspende-Formular mit Eventlistenern
function initPfandspende(){

	$(".pfand-auswahl").on("click", pfandAuswahl);
	$(".nummerfeld").on("keydown", function (e) {

		//Löschen der vorhandenen Error und Help-Block-Classes
	    $(this.parentNode.parentNode).removeClass("has-error");
	    $(this.parentNode.parentNode).find(".help-block").remove();

        // Erlaubt sind: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Erlaubt: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Erlaubt: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
             // Erlaubt: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
             // Erlaubt: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // Hier passiert nichts
                 return;
        }

        // Onfocusout: Entfernen der Help-Blöcke im Deine Pfandspende Formular
        e.currentTarget.onfocusout = function(){
	        		$(this.parentNode.parentNode).find(".help-block").remove();
	        		if(this.value.trim() === "" ) {
        				this.parentNode.parentNode.className= "form-group btn-group dropup";
        		}

	        	};

	    // Span-Element, um den späteren Success oder Error darzustellen
	    var x = document.createElement("span");

        // Nur Nummern-KeyCodes sind erlaubt, wenn nicht dann...
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {

            e.preventDefault();

            // ... werden Error und Help-Block-Classes an dieses Element gehängt...
        	if($(".form-pfand .help-block").length === 0) {

	        	x.className = "help-block keine-zahl";
	        	//x.innerHTML = '"'+e.key+'" ist keine Zahl';
	        	x.innerHTML = "Bitte nur Zahlen eingeben."
        		x.setAttribute("style", "");
        		e.currentTarget.parentNode.parentNode.className += " has-error";
        		e.currentTarget.parentNode.parentNode.appendChild(x);
	        	
	        } 
        } else {
        		// ... oder bei gültiger Angabe eine Success-Class angehängt.
		   		e.target.parentNode.parentNode.className += " has-success";
		   	} 	
        
    });
}

// Überprüft die Eingabefelder des Pfandspende-Formulars auf Fehler 
// und gibt dem User Fehlerhinweise via Help-Blcok-Classes
function checkInput(event){

	initPfandspende();

	// Fehlertext und Boolean-Variable
	var text;
	var bool = false;

	// Je nach Event.Target.Id wird der Text und die Aussagenüberprüfung druchgeführt
	if(event.currentTarget.id.indexOf("mail") > -1){
		text = 'Keine gültige Email-Adresse.';
		bool = checkEmail(event.currentTarget.value.trim());

	} else if(event.currentTarget.id.indexOf("vorname") > -1 || event.currentTarget.id.indexOf("nachname") > -1) {
		text = 'Kein gültiger Name. (min. 2 Zeichen)';
		bool = checkName(event.currentTarget.value.trim());

	} else if(event.currentTarget.id.indexOf("telefon") > -1) {
		text = 'Keine gültige Telefonnummer.';
		bool = checkTelefon(event.currentTarget.value.trim());

	} else if(event.currentTarget.id.indexOf("strasse") > -1) {
		text = 'Keine gültige Strasse. (min. 2 Zeichen)';
		bool = checkName(event.currentTarget.value.trim());

	} else if(event.currentTarget.id.indexOf("nummerzusatz") > -1) {
		text = 'Keine gültiger Nummerzusatz. (min. 2 Zeichen)';
		bool = checkZusatz(event.currentTarget.value.trim());

	} else if(event.currentTarget.id.indexOf("hausnummer") > -1) {
		text = 'Keine gültige Hausnummer.';
		bool = checkHaus(event.currentTarget.value.trim());

	} else if(event.currentTarget.id.indexOf("plz") > -1) {
		text = 'Keine gültige Postleitzahl.';
		bool = checkPLZ(event.currentTarget.value.trim());

	} else if(event.currentTarget.id.indexOf("Passwort") > -1) {
		text = 'Ungültiges Passwort.';
		bool = checkPWD(event.currentTarget.value.trim());

	}

	// Variablen zum Verwalten des Inputs-Beinhaltenden Form-Group-Div's
	var element = $(event.currentTarget.parentNode.parentNode);
	var DOMelement = event.currentTarget.parentNode.parentNode;

	// Verhindert doppelte Help-Block-Classes beim sleben ELement
	if(bool === false && element.find(".help-block").length > 0){
		return;
	}

	// Verwaltet Error und Success-Classes
	if(event.currentTarget.value.trim().length === 0) {
		return
	} else if(bool === false && parseInt(element.find(".help-block").length) === 0){

			DOMelement.className += " has-error";
			var x = document.createElement("span");
			x.className = "help-block input-falsch";
	    	x.innerHTML = text;

	    	if(event.currentTarget.id.indexOf("login") > -1){
				x.setAttribute("style", "");
			} else {
				x.setAttribute("style", "");
			}

			DOMelement.className += " has-error";
			DOMelement.appendChild(x);

		} else {

			element.find(".help-block").remove();
			element.removeClass("has-error").addClass("has-success has-feedback");
			var span = document.createElement("span");
			span.className = "glyphicon glyphicon-ok form-control-feedback";
			DOMelement.appendChild(span);

		}

}

// CheckInputs - > checkPWD: Überprüft das Passwort
function checkPWD(pwd){
	var re = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/;
	return re.test(pwd);
}

// CheckInputs - > checkPWD: Überprüft die Postleitzahl
function checkPLZ(plz){
	var re = /^[0-9]{5}$/;
	return re.test(plz);
}

// CheckInputs - > checkPWD: Überprüft die Hausnummer
function checkHaus(haus){
	var re = /^[0-9]+[a-zA-Z]*$/;
	return re.test(haus);
}

// CheckInputs - > checkPWD: Überprüft die Telefonnummer
function checkTelefon(tel) {
  var re = /^((\+[0-9]{2,4}([ -][0-9]+[ -]| ?\([0-9]+?\) ?))|(\(0[0-9 ]+?\) ?)|(0[0-9]+? ?( |-|\/) ?))([0-9]+?[ \/-]?)+?[0-9]$/;
  return re.test(tel);
}

// CheckInputs - > checkPWD: Überprüft den Namen
function checkName(name){
	var re = /\b([äöüÄÖÜßa-zA-Z]+){2,}\b/g;
	return re.test(name);
}

// CheckInputs - > checkPWD: Überprüft den Namen
function checkZusatz(name){
	var re = /\b([äöüÄÖÜßa-zA-Z]+){1,}\b/g;
	return re.test(name);
}

// CheckInputs - > checkPWD: Überprüft die Email
function checkEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}

// Setzt alle Inputs auf Default zurück
function resetInputs(){

	$(".form-person").find(".glyphicon").remove();

	$(".form-control").val("");

	$("#pfandliste").empty();

	$(".form-group").removeClass("has-feedback").removeClass("has-success").removeClass("has-error");

	$(".help-block").remove();

	plusPfand();

	$("#pfandliste li span")[0].style.display = "none";
}

// Sorgt für das automatisierte Abspielen des Tutorials
function playTutorial(event){
	//$(".progress-bar").css("margin-top", "0%");
	var e = $("#about").find("ul").find("li");
	var activeLi;
	for(var i = 0; i < e.length; i++){
		if(e[i].className.indexOf("active") >= 0){
			activeLi = i;
		}
	}

	var bar = 25*(activeLi+1);
	bar = bar + "%";
	$("#pro").animate({width: bar},{duration: 2500});

	if(activeLi < 3){
    		window.setTimeout(function(){

    			$("#about ul").find('[class="active"]').removeClass("active");
    			$("#about .tab-content").find('[class*="active"]').removeClass("active").removeClass("in");

    			activeLi++;

    			$("#about ul a")[activeLi].parentNode.className = "active";
    			$("#about .tab-content div")[activeLi].className+=" active in";
    			//$(".progress-bar").css("margin-top", "0%");
    			playTutorial(event);

    		}, 2500);
    	}
    	if(activeLi === 3){

    		barAni = window.setTimeout(function(){
    			$("#about ul").find('[class="active"]').removeClass("active");
                $("#about .tab-content").find('[class*="active"]').removeClass("active").removeClass("in");

    			$("#about ul a")[0].parentNode.className = "active";
    			$("#about .tab-content div")[0].className+=" active in";

    			$("#pro").animate({width: "0%"},{duration: 2500});
    			$('#aniButton').click();
    		}, 2500);
    	}
}

// Klappt die Karte auf und zu
function showMap(){
	$("#map-frame").toggle();
}

// Sorgt für das Blättern im Tutorial -> #about
function aboutSkip(event){

	var num = parseInt($("#about ul").find('[class="active"]').find("a")[0].innerHTML.replace("Schritt ", ""));

	if(num >= 1 && num <= 4){
		if(event.currentTarget.id.indexOf("left") > -1 && num > 1) {

			num = num-2;


			$("#about ul").find('[class="active"]').removeClass("active");
			$("#about .tab-content").find('[class*="active"]').removeClass("active").removeClass("in");

			$("#about ul a")[num].parentNode.className = "active";
			$("#about .tab-content div")[num].className+=" active in";

		} else if(event.currentTarget.id.indexOf("right") > -1 && num < 4){

			$("#about ul").find('[class="active"]').removeClass("active");
			$("#about .tab-content").find('[class*="active"]').removeClass("active").removeClass("in");

			$("#about ul a")[num].parentNode.className = "active";
			$("#about .tab-content").find("div")[num].className+=" active in";
		}
	}
}

// Popover-Methode ( Für spätere Inputs ohne HTML5-required-Attribute )
function checkPopover(event){

	var e = $(".popover");
	for(var i = 0; i < e.length; i++){

		$(e[i]).popover("hide");

	}
}

// Öffnet den Login-Modal-Dialog
function openLogin(event){
	$('#myModal li a').each(function(){
		if(this.href.indexOf(event.currentTarget.name) > -1) {
			this.parentNode.className = "active";
		} else {
			this.parentNode.setAttribute('class', '' );
		}
	});
	$('#myModal .tab-pane').each(function(){
		if(this.id.indexOf(event.currentTarget.name) > -1) {
			this.className = "tab-pane fade active in";
		} else {
			this.className = "tab-pane fade";
		}
	});
}

// Fügt die Pfandart als Text in den Button vor der Auswahl ein
function pfandAuswahl(event){
	event.currentTarget.value = document.getElementById(event.currentTarget.parentNode.parentNode.children[1].id).value;
}

// Erzeugt eine neues li-Element für die "Deine Pfandspende"-Form
function plusPfand(event){

	// Länge für richtige ID
	var counter = $(".dropup").length;

	// li-Item
	var listItem = document.createElement("li");

	// Form-Group-Div
	var form = document.createElement("div");
	form.className = "form-group btn-group dropup";

	// Col-SM-12-Div
	var group = document.createElement("div");
	group.className = "col-sm-12";

	// Input Pfandzahl
	var input = document.createElement("input");
	input.className = "pfandzahl form-control nummerfeld";
	input.type = "text";
	input.setAttribute("placeholder", "Anz.");
	input.setAttribute("name", "pfandanzahl" + counter);
	input.setAttribute("required", "");
	input.setAttribute("aria-describedby", "...");
	input.setAttribute("style", 'width: 50px;-webkit-appearance: none; outline: none;text-align: left; border-radius: 4px;');

	group.appendChild(input);

	var ul = document.createElement("select");
	ul.setAttribute("name", "pfandart" + counter);
	ul.className = "btn btn-default minimal";
	ul.setAttribute("required", "");
	ul.id = "pfand" + counter;

	li = document.createElement("option");
	li.className = "dropdown-header";
	li.innerHTML = "Art des Pfands";
	li.value = "";

	ul.appendChild(li);

	li = document.createElement("option");
	li.className = "pfand-auswahl";
	li.innerHTML = "Bierflaschen 0.5l";
	li.value = "Bierflaschen 0.5l";

	ul.appendChild(li);

	li = document.createElement("option");
	li.className = "pfand-auswahl";
	li.innerHTML = "Bierflaschen 0.33l";
	li.value = "Bierflaschen 0.33l";

	ul.appendChild(li);

	li = document.createElement("option");
	li.className = "pfand-auswahl";
	li.innerHTML = "Mischgetränke 0.5l";
	li.value = "Mischgetränke 0.5l";

	ul.appendChild(li);

	li = document.createElement("option");
	li.className = "pfand-auswahl";
	li.innerHTML = "Dosen/PET-Pfand";
	li.value = "PET/Dosen";

	ul.appendChild(li);

	group.appendChild(ul);
	// Ende Select

	form.appendChild(group);
	// Ende Col-SM-12

	var span = document.createElement("span");
	span.className = "glyphicon glyphicon-remove pull-right";
	span.setAttribute("style", " margin-top: -30px; font-size: 12px; cursor: pointer; color: rgba(255,0,0,0.6);");
	span.title = "Entferne diese Pfandart."
	span.onclick = function(){
		$(this.parentNode).remove();
	}
	form.appendChild(span);
	listItem.appendChild(form);
	// Ende Form-Group

	document.getElementById("pfandliste").appendChild(listItem);

	// Buttoninitialisierung
	initPfandspende();
}
