/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

 CKEDITOR.editorConfig = function( config ) {
 	config.extraPlugins = 'widgetselection,lineutils,imageuploader,dialog,btgrid,bootstrapTabs,accordionList,collapsibleItem,lightbox';
 	config.language = 'es';	
 	config.extraAllowedContent = 'a[data-lightbox,data-title,data-lightbox-saved]';
 	config.protectedSource.push(/<\?[\s\S]*?\?>/g); 
 	config.entities = false;
 };
 