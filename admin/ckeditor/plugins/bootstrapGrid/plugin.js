CKEDITOR.plugins.add('bootstrapGrid', {
    requires: 'dialog',
    icons: 'bootstrapGrid',
    init: function (editor) {
        // Add dialog CSS
        CKEDITOR.document.appendStyleSheet(this.path + 'styles/dialog.css');
        
        // Register the dialog
        CKEDITOR.dialog.add('bootstrapGridDialog', this.path + 'dialogs/bootstrapGrid.js');
        
        editor.addCommand('insertBootstrapGrid', new CKEDITOR.dialogCommand('bootstrapGridDialog'));
        
        editor.ui.addButton('BootstrapGrid', {
            label: 'Insert Bootstrap Grid',
            command: 'insertBootstrapGrid',
            toolbar: 'insert'
        });
    }
});
