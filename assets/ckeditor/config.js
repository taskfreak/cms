/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	config.toolbar = 'Default';
	
	config.toolbar_Full =
    [
		['Save','FitWindow','ShowBlocks','-','Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['Cut','Copy','Paste','PasteText','PasteFromWord','-','SpellCheck','About'],
		'/',
		['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
		['BulletedList','NumberedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor'],
		'/',
		['Format','Font','FontSize'],
		['TextColor','BGColor','-','Source']
	];

    config.toolbar_FullUpl =
    [
		['Save','FitWindow','ShowBlocks','-','Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['Cut','Copy','Paste','PasteText','PasteFromWord','-','SpellCheck','About'],
		'/',
		['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
		['BulletedList','NumberedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor'],
		['Image','Flash','Table','Rule','SpecialChar'],
		'/',
		['Format','Font','FontSize'],
		['TextColor','BGColor','-','Source']
	];

	config.toolbar_Default =
	[
		['Format','Font','FontSize'],['PasteText','Source'],
		,'/',
		['Bold','Italic','Underline','StrikeThrough','TextColor','BGColor'],
		['BulletedList','NumberedList','-','Outdent','Indent','Blockquote'],
		['Link','Unlink'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
	];
	
	config.toolbar_DefaultUpl =
	[
		['Format','Font','FontSize'],['PasteText','Source'],
		,'/',
		['Bold','Italic','Underline','StrikeThrough','TextColor','BGColor'],
		['BulletedList','NumberedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink'],
		['Image','Flash','Table','SpecialChar']
	];

	config.toolbar_Mini =
	[
		['FontSize','Bold','Italic','Underline','StrikeThrough','TextColor','BGColor'],
		['JustifyLeft','JustifyCenter','JustifyRight'],['Link','Unlink'],['PasteText','Source']
	];
	
	config.toolbar_MiniUpl =
	[
		['FontSize','Bold','Italic','Underline','StrikeThrough','TextColor','BGColor'],
		['JustifyLeft','JustifyCenter','JustifyRight'],['Link','Unlink'],['Image','Flash','Table'],['PasteText','Source']
	];

	config.toolbar_Micro =
	[
		['Bold','Italic','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['-','BulletedList','NumberedList','-','Link','Unlink','-','PasteText','Source']
	];
	
	config.toolbar_MicroUpl =
	[
		['Bold','Italic','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['-','BulletedList','NumberedList','-','Link','Unlink','-','Image','Flash','-','PasteText','Source']
	];

	config.toolbar_Nano =
	[
		['Bold','Italic','-','-','Link','Unlink','-','PasteText','Source']
	];
	
	config.toolbar_NanoUpl =
	[
		['Bold','Italic','-','Link','Unlink','-','Image','Flash','-','PasteText','Source']
	];

};
