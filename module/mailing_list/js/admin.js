function cms_news_add(f) {
	id = f.name+'_'+f.value;
	l1 = new Element('li', {
		'id':id
	});
	l2 = new Element('a', {
		'href':"javascript:cms_news_del('"+id+"')",
		'class':'button rlink'
	});
	l2.appendText('X');
	l2.injectInside(l1);
	l3 = new Element('input', {
		'type':'hidden',
		'name':f.name+'[]',
		'value':f.value
	});
	l3.injectInside(l1);
	l1.appendText(f.options[f.selectedIndex].text);
	l1.injectInside($('news_items'));
	f.selectedIndex=0;
	new Sortables($('news_items'));
}

function cms_news_del(id,lst) {
	$(id).destroy();
	if (lst) {
		document.forms[0].elements['items2delete'].value += id+',';
	}
}

function cms_set_quicksearch(id) {
	$('main').addEvent('submit', function(e) {
		e.stop();
		this.set('send', {
			'url': '../ajax.php?module=mailing_list&action='+id,
			onComplete: function(response) { 
				$(id+'-list').set('html', response);
			}
		});
		this.send();
	});
}

function cms_rem_quicksearch() {
	$('main').removeEvents('submit');
}

function cms_res_quicksearch(id) {
	$(id+'-keyword').set('value','');
	$('main').set('send', {
		'url': '../ajax.php?module=mailing_list&action='+id,
		onComplete: function(response) { 
			$(id+'-list').set('html', response);
		}
	}).send();
}

function cms_init_quicksearch(id) {
	if (!$(id+'-keyword')) {
		return false;
	}
	$(id+'-keyword').addEvents({
		'focus':function() {cms_set_quicksearch(id)},
		'blur':function() {cms_rem_quicksearch()}
	});
	$(id+'-submit').addEvent('click',function(){cms_set_quicksearch(id)});
	$(id+'-reset').addEvent('click',function(){cms_res_quicksearch(id)});
	return true;
}

window.addEvent('domready',function() {
	cms_init_quicksearch('letters');
	cms_init_quicksearch('subscribers');
	if ($('news_items')) {
		new Sortables($('news_items'));
	}
});