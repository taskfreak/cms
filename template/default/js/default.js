/* interface animations */

function togglelogin() {
	e = $('user');
	if (e.hasClass('pnl')) {
		$('auth_pnl').slide('toggle');
		e.removeClass('pnl');
	} else {
		e.addClass('pnl');
		$('auth_pnl').slide('toggle');
		$('username').focus();
	}
}
function hidelogin() {
	$('login').tween('opacity',1,0);
	$('auth').tween('opacity',0,1);
}
function tooglepanel(id) {
	el = $(id+'_panel');
	try {
		el.getParent('fieldset').toggleClass('panel');
		el.slide('toggle');
		el.getElement('input').focus();
	} catch(e) {
		el.slide('toggle');
	}
}

/* ajax stuff */

var is_loading = false;

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
				el.set('html','<p class="empty">Une erreur est survenue pendant l\'operation. Reessayez.</p>');
			} else {
				alert('Une erreur est survenue pendant l\'operation. Reessayez.');
			}
			ajaxify_unload(trg);
		}
	}).send();
}

function ajaxify_form(el, trg, sts) {
	if (!sts) {
		sts = trg;
	}
	el.set('send',{
		encoding: 'utf-8',
		evalScripts: true,
		onRequest:function() {
			ajaxify_load(sts);
		},
		onComplete:function() {
			ajaxify_unload(sts);
		},
		onSuccess:function(res) {
			if (ed = $(trg)) {
				ed.set('html',res);
			}
		},
		onFailure:function() {
			if (ed = $(trg)) {
				ed.set('html','<p class="empty">Une erreur est survenue pendant l\'operation. Reessayez.</p>');
			} else {
				alert('Une erreur est survenue pendant l\'operation. Reessayez.');
			}
			ajaxify_unload(trg);
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

/* easy click */

function clickable_init() {
	if (!$$('.easyclick')) {
		return false;
	}
	$$('.easyclick').each(function(it,ix) {
		if (it.getElement('a')) {
			it.setStyle('cursor','pointer');
			it.addEvent('click',function(e) {
				if (!$defined(e.target.href) && !e.target.tagName.match(/input|select|textarea|radio|button/i)) {
					var el = this.getElement('a[rel=clickme]');
					if (!el) {
						el = this.getElement('a');
					}
					rl = el.get('rel');
					if (rl && rl.match(/^ajax/)) {
						el.fireEvent('click');
					} else {
						window.location.href = el.get('href');
					}
				}
			});
		}
	});
}

/* init */

window.addEvent('domready',function() {
	ajaxify_links();
	clickable_init();
	$$('.panel .fields').each(function(it) {
		it.slide('hide');
		it.setStyle('display','block');
	});
});
