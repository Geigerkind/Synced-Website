var utilToggle = {};
utilToggle['general-unedit'] = true;
utilToggle['char-unedit'] = true;
utilToggle['general'] = true;
utilToggle['char-selector'] = true;
function toggle(a){
	var b = document.getElementById(a);
		if(utilToggle[a] == null){
			utilToggle[a] = false;
		}
		if(utilToggle[a]){
			utilToggle[a] = false;
			b.style.display = "none";
		}else{
			utilToggle[a] = true;
			b.style.display = "block";
		}
}
function toggleUrl(a, b, c){
	toggle(a);
	if(utilToggle[a] == false){
		deleteArrayElement(b, c);
	}else{
		addArrayElement(b, c);
	}
}
Array.prototype.contains = function(obj) {
	var i = this.length;
	while (i--) {
		if (this[i] === obj) {
			return true;
		}
	}
	return false;
}

function submitOnClick(formName){
    document.forms[formName].submit();
}

var toClass = [];
var toPlaye = [];
function deleteArrayElement(a, b){
	if(a == 'toClass'){
		a = toClass;
	}else{
		a = toPlaye;
	}
	var i = a.indexOf(b);
	if(i != -1){
		a.splice(i, 1);
		buildURL();
	}
}
function addArrayElement(a, b){
	var i = true;
	if(a == 'toClass'){
		i = toClass.contains(b);
		a = toClass;
	}else{
		i = toPlaye.contains(b);
		a = toPlaye;
	}
	if(i == false){
		a.push(b);
		buildURL();
	}
}
function buildURL(){
	var i = '/loot-system/?class=' + toClass.toString() + '&player=' + toPlaye.toString();
	window.history.pushState("Synced", "Synced - Loot-System", i);
	document.cookie="url="+i;
}
function restoreLS(a, b){
	var classes = a.split(',');
	var player = b.split(',');
	
	for(var i = 0; i < classes.length; i++){
		toggleUrl('ls-class-'+classes[i], 'toClass', classes[i]);
	}
	
	for(var d = 0; d < player.length; d++){
		toggleUrl('ls-player-'+player[d], 'toPlayer', player[d]);
	}
}

function util_switch(a){
	var b = a.split(',');
	for(var i = 0; i < b.length; i++){
		toggle(b[i]);
	}
}

function accSelect(f,g){
	var e = document.getElementById("char-select");
	var z = document.getElementById("char-selector-add");
	var u = document.getElementById("char-selector");
	var strUser = e.options[e.selectedIndex].value;
	
	if(strUser == "Add an character"){
		util_switch('char-selector-add,char-selector');
		var options = e.options;
		for (var i= 0; i<options.length; i++) {
			if (options[i].value == f) {
				options[i].selected= true;
				break;
			}
		}
	}else if(f != strUser){
		Redirect("http://synced-kronos.net/account/?uid="+g+"&char="+strUser);
	}
}
function Redirect(url){
	location.href = url;
}

function hackEventButton(a){
	var a = document.getElementById(a);
	var b = document.getElementById('roles');
	var c = document.getElementById('classes');
	var d = document.getElementById('signedOut');
	var e = document.getElementById('notintime');
	var f = document.getElementById('notsignedinout');
	var handler = document.getElementById('u-handler');
	
	b.style.display = "none";
	c.style.display = "none";
	d.style.display = "none";
	e.style.display = "none";
	f.style.display = "none";
	a.style.display = "block";
	handler.style.display = "none";
}

function util_ButtonShow(a, b){
	var a = document.getElementById(a);
	var b = b.split(',');
	
	a.style.display = "block";
	for(var i = 0; i < b.length; i++){
		document.getElementById(b[i]).style.display = "none";
	}
}

function textarea_bbcode(tag){
	  $('#textedit')
		.selection('insert', {text: '['+tag+']', mode: 'before'})
		.selection('insert', {text: '[/'+tag+']', mode: 'after'});
}

var isIE = document.all?true:false;
if (!isIE) document.captureEvents(Event.CLICK);
document.onclick = getMousePosition;
function getMousePosition(e) {
	  var _x;
	  var _y;
	  if (!isIE) {
		_x = e.pageX;
		_y = e.pageY;
	  }
	  if (isIE) {
		_x = event.clientX + document.body.scrollLeft;
		_y = event.clientY + document.body.scrollTop;
	  }

	posX=_x;
	posY=_y;
	  return true;
}

function PopUserHandler(a,b,c){
	var handler = document.getElementById('u-handler');
	var link1 = document.getElementById('u-link1');
	var link2 = document.getElementById('u-link2');
	var link3 = document.getElementById('u-link3');
	var link4 = document.getElementById('u-link4');
	
	link1.setAttribute('href', "../../Modules/calendar/removePlayer.php?uid="+a+"&eventid="+b+"&date="+c)
	link2.setAttribute('href', "../../Modules/calendar/movePlayerToNIT.php?uid="+a+"&eventid="+b+"&date="+c)
	link3.setAttribute('href', "../../Modules/calendar/movePlayerToSO.php?uid="+a+"&eventid="+b+"&date="+c)
	link4.setAttribute('href', "../../Modules/calendar/movePlayerToSI.php?uid="+a+"&eventid="+b+"&date="+c)
	
	handler.style.top = posY-40+"px";
	handler.style.left = posX+30+"px";
	handler.style.display = "block";
}