function ck_upload(html) {
	$('flist').set('html',html);
	$('i_myfile').set('value','');
	$('footer').removeClass('loading')
}
function ck_error(mess) {
	new Element('div',{'id':'error'}).set('html',mess).inject('global','before');
	$('footer').removeClass('loading');
	window.setTimeout(function() {$('error').destroy()}, 2000);
}