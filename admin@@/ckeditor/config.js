/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	
	config.enterMode = CKEDITOR.ENTER_BR;
	config.shiftEnterMode = CKEDITOR.ENTER_P;
	config.entities_latin = false;
	config.height=250;
	config.extraPlugins = 'lineheight';
	//cau hinh thanh cong cu
	/*config.filebrowserImageBrowseUrl = 'ckfinder/ckfinder.html?type=Images';
    config.filebrowserFlashBrowseUrl = 'ckfinder/ckfinder.html?type=Flash';
    config.filebrowserUploadUrl = 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserImageUploadUrl = 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
    config.filebrowserFlashUploadUrl ='ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';*/
    config.filebrowserBrowseUrl = 'ckfinder/ckfinder.html';

    config.filebrowserImageBrowseUrl = 'ckfinder/ckfinder.html?type=Images';

    config.filebrowserFlashBrowseUrl = 'ckfinder/ckfinder.html?type=Flash';

    config.filebrowserUploadUrl = 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';

    config.filebrowserImageUploadUrl = 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';

    config.filebrowserFlashUploadUrl = 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
    
	config.toolbar = [
		[ 'Source', '-', 'NewPage', 'Preview', '-', 'Templates' ],
		[ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ],['Find','Replace'],
		['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		'/',
		[ 'Bold', 'Italic','Underline','Strike','Subscript','Superscript','RemoveFormat' ],
		['NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl'],
		'/',		
		['Styles'],['Format','Font','FontSize','lineheight'],['TextColor','BGColor','UIColor'],['Link','Unlink','Anchor']
	];
	
};
