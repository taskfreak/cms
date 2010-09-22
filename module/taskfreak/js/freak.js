function tf_status(tid, sts) {
	ajaxify_request('/ajax.php?module=taskfreak&action=status&item='+tid+'&status='+sts);
}
function tf_new_status(d,u,s) {
	el = new Element('tr').adopt(new Element('td').set('html',d)).adopt(new Element('td').set('html',u)).adopt(new Element('td').set('html',s));
	$('history_panel').getElement('tbody').grab(el, 'bottom');
}
function tf_project_users(sel) {
	$('i_user').setProperty('disabled',true);
	if (sel) {
		sel = sel.options[sel.selectedIndex].value;
	}
	ajaxify_request('/ajax.php?module=taskfreak&action=userlist&item='+sel);
}

/* file upload */
var ff_act = '';

function tf_form() {
	ff = $('tf_form');
	if (!ff) {
		ff = $('comment_form');
	}
	return ff;
}

function tf_upload() {
	ff = tf_form();
	ff_act = ff.getProperty('action');
	ff.setProperties({
		'action':'/module/taskfreak/upload.php',
		'target':'upload_iframe'
	});
	$('fileadd').addClass('loading');
	ff.submit();
}
function tf_uploaded(ftemp,freal,ftype,fsize) {
	html = '<input type="hidden" name="uplfile[]" value="'+ftemp+';'+freal+';'+ftype+';'+fsize+'" />';
	html += '<a href="javascript:tf_del_file(\''+ftemp+'\')">X</a>';
	html += freal;
	e = new Element('div', {'id':'file_'+ftemp,	'class':'filerow'}).set('html',html);
	$('tf_attachs').adopt(e);
	tf_upload_clean();
}
function tf_upload_clean() {
	ff = tf_form();
	ff.setProperties({
		'action':ff_act,
		'target':''
	});
	$('i_newfile').set('value','');
	$('fileadd').removeClass('loading');
}
function tf_del_file(tmp) {
	tid = parseInt(tmp);
	if (tid == tmp) {
		fd = $('i_files2del');
		fd.set('value',fd.get('value') + tid + ';');
	}
	$('file_'+tmp).destroy();
}
/* tootips */

var qTipTag = "a,span,img,td,button"; //Which tag do you want to qTip-ize? Keep it lowercase!//
var qTipX = -100; //This is qTip's X offset//
var qTipY = 15; //This is qTip's Y offset//

//There's No need to edit anything below this line//
tooltip = {
  name : "qTip",
  offsetX : qTipX,
  offsetY : qTipY,
  tip : null
}

tooltip.init = function () {
	var tipNameSpaceURI = "http://www.w3.org/1999/xhtml";
	if(!tipContainerID){ var tipContainerID = "qTip";}
	var tipContainer = document.getElementById(tipContainerID);

	if(!tipContainer) {
	  tipContainer = document.createElementNS ? document.createElementNS(tipNameSpaceURI, "div") : document.createElement("div");
		tipContainer.setAttribute("id", tipContainerID);
	  document.getElementsByTagName("body").item(0).appendChild(tipContainer);
	}

	if (!document.getElementById) return;
	this.tip = document.getElementById (this.name);
	if (this.tip) document.onmousemove = function (evt) {tooltip.move (evt)};

	var a, sTitle, elements;
	
	var elementList = qTipTag.split(",");
	for(var j = 0; j < elementList.length; j++)
	{	
		elements = document.getElementsByTagName(elementList[j]);
		if(elements)
		{
			for (var i = 0; i < elements.length; i ++)
			{
				a = elements[i];
				sTitle = a.getAttribute("title");				
				if(sTitle)
				{
					a.setAttribute("tiptitle", sTitle);
					a.removeAttribute("title");
					a.removeAttribute("alt");
					a.onmouseover = function() {tooltip.show(this.getAttribute('tiptitle'))};
					a.onmouseout = function() {tooltip.hide()};
				}
			}
		}
	}
}

tooltip.move = function (evt) {
	var x=0, y=0;
	if (document.all) {//IE
		x = (document.documentElement && document.documentElement.scrollLeft) ? document.documentElement.scrollLeft : document.body.scrollLeft;
		y = (document.documentElement && document.documentElement.scrollTop) ? document.documentElement.scrollTop : document.body.scrollTop;
		x += window.event.clientX;
		y += window.event.clientY;
		
	} else {//Good Browsers
		x = evt.pageX;
		y = evt.pageY;
	}
	this.tip.style.left = (x + this.offsetX) + "px";
	this.tip.style.top = (y + this.offsetY) + "px";
}

tooltip.show = function (text) {
	if (!this.tip) return;
	this.tip.innerHTML = text;
	this.tip.style.display = "block";
}

tooltip.hide = function () {
	if (!this.tip) return;
	this.tip.innerHTML = "";
	this.tip.style.display = "none";
}

/* init */
window.addEvent('domready',function() {
	tooltip.init();
});