function cms_toggle(id) {
	e = $(id);
	sh = (e.getStyle('display') == 'none')?true:false;
	if (arguments.length > 1) {
		sh = arguments[1];
	}
	if (sh) {
		e.setStyle('display','');
		/*
		if (e.getTag() == 'table') {
			e.setStyle('display','table');
		} else if (e.getTag() == 'tr') {
			e.setStyle('display','');
		} else {
			e.setStyle('display','block');
		}
		*/
	} else {
		e.setStyle('display','none');
	}
}

function cms_message() {
	if (el = $('cms_message')) {
		// -TODO- fade
		el.destroy();
	}
}

var is_loading = false;

/* ajax stuff */

function ajaxify_request(uri, rel) {
	re = /(ajax| )/g;
	trg = '';
	if (rel) {
		trg = rel.replace(re, '');
	}
	new Request({
		method: 'get', 
		url: uri,
		encoding: 'utf-8',
		evalScripts: true,
		onRequest:function() {
			ajaxify_load(trg);
		},
		onComplete:function() {
			ajaxify_unload(trg);
		},
		onSuccess:function(res) {
			if (el = $(trg)) {
				el.set('html',res);
			}
		},
		onFailure:function() {
			if (el = $(trg)) {
				el.set('html','<p class="empty">Uh oh something went wrong, please reload the page</p>');
			} else {
				alert('Uh oh something went wrong, please reload the page');
			}
		}
	}).send();
}

function ajaxify_form(el, trg) {
	el.set('send',{
		encoding: 'utf-8',
		evalScripts: true,
		onRequest:function() {
			ajaxify_load(trg);
		},
		onComplete:function() {
			ajaxify_unload(trg);
		},
		onSuccess:function(res) {
			if (ed = $(trg)) {
				ed.set('html',res);
			}
		}
	});
	el.send();
	return false;
}

function ajaxify_load(t) {
	is_loading = window.setTimeout('ajaxify_load_div("'+t+'")',300);
}
function ajaxify_load_div(t) {
	if (t && $(t)) {
		$(t).addClass('loading');
	} else {
		new Element('div',{'id':'ajax-load'}).inject('global','before');
	}
}
function ajaxify_unload(t) {
	window.clearTimeout(is_loading);
	if (t && $(t)) {
		$(t).removeClass('loading');
	} else if (el = $('ajax-load')) {
		el.destroy();
	}
}

function ajaxify_links() {
	$$('a[rel*=ajax]').each(function(el){
		el.addEvent('click', function(e){
			try {
				e.stop();
				ajaxify_request(this.get('href'), this.get('rel'));
			} catch(err) {}
		});
	});
}

window.addEvent('domready',function() {
	setTimeout('cms_message()',2500);
	ajaxify_links();
});