/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	// Other configs
   config.filebrowserBrowseUrl = '/assets/admin/js/plugin/kcfinder/browse.php?type=files';
   config.filebrowserImageBrowseUrl = '/assets/admin/js/plugin/kcfinder/browse.php?type=images';
   config.filebrowserFlashBrowseUrl = '/assets/admin/js/plugin/kcfinder/browse.php?type=flash';
   config.filebrowserUploadUrl = '/assets/admin/js/plugin/kcfinder/upload.php?type=files';
   config.filebrowserImageUploadUrl = '/assets/admin/js/plugin/kcfinder/upload.php?type=images';
   config.filebrowserFlashUploadUrl = '/assets/admin/js/plugin/kcfinder/upload.php?type=flash';
   config.width = '98%';
   config.height = '200px';
   config.enterMode = CKEDITOR.ENTER_BR;
   config.allowedContent = true;
   //config.contentsCss = ['../../../css/frontend/bootstrap.css','../../../css/frontend/custom.css','../../../css/frontend/custom2.css','../../../css/frontend/fonts.css','../../../css/frontend/webfonts/webfonts.css'];
};
