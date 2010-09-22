/* some useful functions */

function cms_shortcut(str) {
	str=str.replace(/[ח]/g,'c');
	str=str.replace(/[טיכך]/g,'e');
	str=str.replace(/[אבהג]/g,'a');
	str=str.replace(/[לםןמ]/g,"i");
	str=str.replace(/[שתüû]/g,"u");
	str=str.replace(/[עףצפ]/g,"o");
	str=str.replace(/[^A-Za-z0-9]/g,'-');
	return str.toLowerCase();
}

function cms_switch_panel(il, it) {
	$$('#'+il+' ul.tzntabs li a').each(function(el) {
		ip = el.id.replace(/(lnk)/,'pan');
		//console.log('is this '+ip);
		if (el.id == il+'_lnk_'+it) {
			el.addClass('current');
			$(ip).removeClass('hide');
		} else {
			el.removeClass('current');
			$(ip).addClass('hide');
		}
	});
}

window.addEvent('domready',function() {
	cms_init_tabs();
	cms_init_accordion();
	cms_init_clickable();
});

function cms_init_tabs() {
	et = $$('.tabs');
	if (!et) {
		return false;
	}
	et.each(function(it,ix) {
		new SimpleTabs(it, {
			selector: 'h3'
		});
	});
}

function cms_init_accordion() {
	if (!$('accordion') || $('accordion').hasClass('showall')) {
		return false;
	}
	new Accordion($('accordion'), 'h3.acctog', 'div.accinf', {
		display: false,
		show: 0,
		opacity: true,
		alwaysHide: false,
		onActive: function(toggler, element){
			toggler.addClass('active');
			element.setStyle('margin-bottom','8px');
		},
		onBackground: function(toggler, element){
			toggler.removeClass('active');
			element.setStyle('margin-bottom','0'); 
		}
	});
}

function cms_init_clickable() {
	if (!$$('.clickable')) {
		return false;
	}
	$$('.clickable .row').each(function(it,ix) {
		if (it.getElement('a')) {
			it.setStyle('cursor','pointer');
			it.addEvent('click',function(e) {
				if (!$defined(e.target.href) && !e.target.tagName.match(/input|select|textarea|radio|button/i)) {
					var el = this.getElement('a[rel=clickme]');
					if (!el) {
						el = this.getElement('a');
					}
					if (el.get('rel') == 'ajaxed') {
						el.fireEvent('click');
					} else {
						window.location.href = el.get('href');
					}
				}
			});
		}
	});
}