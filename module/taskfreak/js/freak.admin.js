function tf_team_edit(id,value,own) {
	e = $('pos-'+id);
	pos = e.get('html');
	
	e1 = new Element('select', {'name':'position-'+id});
		
	len = arrPositions.length-1;
	
	for (i=0; i<=len ; i++) {
		if (own >= i+1) {
			e1.grab(new Element('option',{'value':i+1, 'text':arrPositions[i]}));
		}
	}
	
	e1.set('value',value);
	
	e.empty();
	e.grab(e1);
	
	/*
	e = $('but-'+id);
	
	e1 = new Element('button', {'type':'submit', 'text','Change'});
	e1.type='submit';
	e1.value='Change';
	
	for(i=0;i<4;i++) {
		e.removeChild(e.lastChild);
	}
	
	e.appendChild(e1);
	*/
}
