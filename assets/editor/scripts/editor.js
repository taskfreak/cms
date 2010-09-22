function getEditorForm() {
	ff = $('main');
	if (ff.get('tag') == 'form') {
		return ff;
	} else {
		return ff.getFirst('form');
	}
}
function turnUplOn(t) {
	ff = getEditorForm();
	ff.set('target','upload_iframe');
	$(t+'add').setProperty('type','hidden');
	$('upload_mode').set('value',t);
	$(t+'form').addClass('loading');
	ff.submit();
}
function turnUplOff(t) {
	ff = getEditorForm();
	ff.target='';
	$('upload_mode').set('value','');
	$(t+'add').setProperty('type','button');
	$(t+'form').removeClass('loading');
}
function rstFile(field) {
	ff = getEditorForm();
	ff.set('target','');
	$('upload_mode').set('value','');
	ff.elements[field+'new'].value='';
	if (ff.elements[field+'new'].value!='') {
		$(field+'field').setHTML('<input type="file" name="'+field+'new" value="" />');
	}
	turnUplOff(field);
}
function addFile(field,ftemp,freal,ftype,fsize) {
	// console.log('adding file '+freal+' to list');
	dd = new Element('div', {
		'id':field+'_'+ftemp,
		'class':'row fnew'
	}).adopt(
		new Element('input',{
			'type':'hidden',
			'name':field+'nw[]',
			'value':ftemp+';'+freal+';'+ftype+';'+fsize
		})
	).adopt(
		new Element('a', {
			'class':'del',
			'href':'javascript:delFile(\''+field+'\',\''+ftemp+'\',false)'
		}).appendText('X')
	).appendText(freal);
	// console.log('adding to list: '+field+'list');
	dd.inject(field+'list','bottom');
	$('imgconfirm').setStyle('display','block');
	rstFile(field);
}
function delFile(field,fname,req) {
	if (req) {
		ff = getEditorForm();
		ff.elements[field+'del'].value += fname+';';
	}
	$(field+'_'+fname).dispose();
	if (!$$('div.fnew').length) $('imgconfirm').setStyle('display','none');
}