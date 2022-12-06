/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	var current_url = window.location.host;

   config.filebrowserBrowseUrl = 		'https://'+current_url+'/cockpit/kcfinder/browse.php?lang=nl&opener=ckeditor&type=files';
   config.filebrowserImageBrowseUrl = 	'https://'+current_url+'/cockpit/kcfinder/browse.php?lang=nl&opener=ckeditor&type=images';
   config.filebrowserFlashBrowseUrl = 	'https://'+current_url+'/cockpit/kcfinder/browse.php?lang=nl&opener=ckeditor&type=flash';
   config.filebrowserUploadUrl = 		'https://'+current_url+'/cockpit/kcfinder/upload.php?lang=nl&opener=ckeditor&type=files';
   config.filebrowserImageUploadUrl = 	'https://'+current_url+'/cockpit/kcfinder/upload.php?lang=nl&opener=ckeditor&type=images';
   config.filebrowserFlashUploadUrl = 	'https://'+current_url+'/cockpit/kcfinder/upload.php?lang=nl&opener=ckeditor&type=flash';

/*
   config.filebrowserBrowseUrl = 'http://'+current_url+'/cockpit/ckfinder/ckfinder.html';
   config.filebrowserImageBrowseUrl = 'http://'+current_url+'/cockpit/ckfinder/ckfinder.html?type=Images';
   config.filebrowserFlashBrowseUrl = 'http://'+current_url+'/cockpit/ckfinder/ckfinder.html?type=Flash';
   config.filebrowserUploadUrl = 'http://'+current_url+'/cockpit/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
   config.filebrowserImageUploadUrl = 'http://'+current_url+'/cockpit/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
   config.filebrowserFlashUploadUrl = 'http://'+current_url+'/cockpit/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
*/

   //config.uiColor = '#e31c1d';
   config.enterMode = CKEDITOR.ENTER_BR;
   config.forcePasteAsPlainText = true;
   config.extraPlugins = 'pastetext';
//    config.stylesSet = 'mystyles:http://develop.nannedijkstra.nl/cockpit/ckeditor/my_styles/styles.js';


	config.extraPlugins = 'autogrow';
	config.autoGrow_minHeight = 450;
	config.autoGrow_maxHeight = 600;

   //config.emailProtection = 'encode';
   config.scayt_autoStartup = true;
   config.scayt_sLang = 'nl_NL';

	config.allowedContent = true;

    config.contentsCss = [
        'https://'+current_url+'/cockpit/vendor/bootstrap/bootstrap.min.css',
        'https://'+current_url+'/cockpit/ckeditor/contents.css'
    ];



    //CKEDITOR.stylesSet.add( 'my_style', [
    // Inline styles
    //	{ name: 'CSS Style', element: 'span', attributes: { 'class': 'my_style' } },
	//	{ name: 'Marker: Yellow', element: 'span', styles: { 'background-color': 'Yellow' } },

	//	{ name: 'Standaard Tabel', element: 'table', attributes: { 'class': 'table'} }
    //]);


	// Toolbar configuration generated automatically by the editor based on config.toolbarGroups.
	config.toolbar = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Preview', 'Print', '-', 'Templates' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ] },
		{ name: 'editing', groups: [ 'spellchecker' ], items: [ 'Scayt' ] },
// 		{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },

		{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
		{ name: 'insert', items: [ 'Image' ] },
		{ name: 'insert', items: [ 'FontAwesome' , 'Youtube'] },

		{ name: 'styles', items: [ 'Styles', 'Format', 'FontSize' ] },
		{ name: 'insert', items: [ 'Table', 'btbutton', 'HorizontalRule', 'InsertPre' ] },
	/* 	{ name: 'colors', items: [ 'TextColor', 'BGColor' ] }, */
		{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
		{ name: 'others', items: [ '-' ] },
	/* 	{ name: 'about', items: [ 'About' ] } */
		{ name: 'others', items: [ '-','-' ] }

	];


	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Se the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Make dialogs simpler.
	config.removeDialogTabs = 'image:advanced;link:advanced';


};
