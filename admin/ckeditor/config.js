/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	//config.extraPlugins = 'codesnippet';
	//config.extraPlugins = 'gallery';
	//config.extraPlugins = 'powrmediagallery';
	config.extraPlugins = 'youtube,blocktemplate,bootstrapGrid,bootstrap5components,bootstrapTable';
        config.contentsCss = [
            '../templates/css/style.css',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' // Add Bootstrap CSS
        ];
        config.font_names = 'Roboto/Roboto,Helvetica,Arial,sans-serif;'+
                        'Merienda/Merienda,Helvetica,Arial,sans-serif;'+
                        'Roboto Condensed/Roboto Condensed,Helvetica,Arial,sans-serif;' ;
        config.removePlugins = 'forms';
};
