function pic_scroll(dir) {
	var scroll = new Fx.Scroll(window, {
		wait: false,
		duration: 500,
		offset: {'x': -50, 'y': -300}
	});
	switch(dir) {
		case 0:
			scroll.toLeft();
			$('ref'+cur).removeClass('hgh');
			cur=1;
			$('ref'+cur).addClass('hgh');
			break;
		case 1:
			if (cur < maxi) {
				$('ref'+cur).removeClass('hgh');
				scroll.toElement('ref'+(++cur));
				$('ref'+cur).addClass('hgh');
			}
			break;
		case -1:
			if (cur > 1) {
				$('ref'+cur).removeClass('hgh');
				if (cur > 2) {
					scroll.toElement('ref'+(--cur));
				} else {
					cur = 1;
					scroll.toLeft();
				}
				$('ref'+cur).addClass('hgh');
			}
			break;
	}
}

window.addEvent('domready', function() {
	new Element('div',{id:'pic_nav'}).adopt(
		new Element('a',{'id':'pic_rst','href':'javascript:{}','events':{'click':function() {pic_scroll(0)}}}).appendText('back'),
		new Element('a',{'id':'pic_prv','href':'javascript:{}','events':{'click':function() {pic_scroll(-1)}}}).appendText('previous'),
		new Element('a',{'id':'pic_nxt','href':'javascript:{}','events':{'click':function() {pic_scroll(1)}}}).appendText('next')
	).inject('pic_table','before');
	$('ref1').addClass('hgh');
});