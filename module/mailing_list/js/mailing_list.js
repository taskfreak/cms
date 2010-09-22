var gblfnc = null;
function cms_submit_button(fnc) {
    if (fnc) {
        if (!gblfnc) {
            gblfnc = fnc;
        }
        document.forms[0].onsubmit=function(){
            cms_custom_call('mailing_list',gblfnc,document.forms[0].elements[gblfnc].value);
            return false;
        };
    } else {
        window.setTimeout('cms_submit_clear()',500);
    }
}
function cms_submit_reset(fnc) {
    document.forms[0].elements[fnc].value='';
    document.forms[0].elements[fnc+'Submit'].click();
}
function cms_submit_focus(fnc) {
    if (fnc) {
        // window.setTimeout('cms_submit_set("'+fnc+'")',700);
        gblfnc = fnc;
        document.forms[0].onsubmit=function(){
            cms_custom_call('mailing_list',gblfnc,document.forms[0].elements[gblfnc].value);
            return false;
        };
    } else {
        window.setTimeout('cms_submit_clear()',500);
    }
}
function cms_submit_set(fnc) {
    gblfnc = fnc;
}
function cms_submit_clear() {
    gblfnc = null;
    document.forms[0].onsubmit=function() {
        return true;
    }
}
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
	$(id).remove();
	if (lst) {
		document.forms['cms_admin_form'].elements['items2delete'].value += id+',';
	}
}