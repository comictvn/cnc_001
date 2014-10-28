/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.filebrowserBrowseUrl = window.location.host + '/template/admin/ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = window.location.host + '/template/admin/ckfinder/ckfinder.html?type=Images';
	config.filebrowserFlashBrowseUrl = window.location.host +'/template/admin/ckfinder/ckfinder.html?type=Flash';
	config.filebrowserUploadUrl = window.location.host +'/template/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = window.location.host +'/template/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl = window.location.host +'/template/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
	
};
