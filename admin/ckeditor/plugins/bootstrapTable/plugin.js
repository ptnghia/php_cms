/**
 * Bootstrap Table Plugin for CKEditor
 * Adds Bootstrap table classes and utilities to CKEditor tables
 */

CKEDITOR.plugins.add('bootstrapTable', {
    requires: 'table,tabletools,contextmenu',
    icons: 'bootstraptable',
    
    init: function(editor) {
        var pluginName = 'bootstrapTable';
        
        // Define Bootstrap table classes
        var bootstrapTableClasses = {
            'table': 'Bảng cơ bản',
            'table-striped': 'Hàng xen kẽ',
            'table-bordered': 'Viền bảng',
            'table-hover': 'Hover hiệu ứng',
            'table-sm': 'Bảng nhỏ gọn',
            'table-responsive': 'Responsive',
            'table-dark': 'Bảng tối',
            'table-light': 'Bảng sáng'
        };
        
        var contextualClasses = {
            'table-primary': 'Primary',
            'table-secondary': 'Secondary', 
            'table-success': 'Success',
            'table-danger': 'Danger',
            'table-warning': 'Warning',
            'table-info': 'Info',
            'table-light': 'Light',
            'table-dark': 'Dark'
        };
        
        // Add command for Bootstrap table dialog
        editor.addCommand('bootstrapTableDialog', new CKEDITOR.dialogCommand('bootstrapTable'));
        
        // Add toolbar button
        editor.ui.addButton('BootstrapTable', {
            label: 'Bootstrap Table Classes',
            command: 'bootstrapTableDialog',
            toolbar: 'table,1',
            icon: this.path + 'icons/bootstraptable.png'
        });
        
        // Add context menu
        if (editor.contextMenu) {
            editor.addMenuGroup('bootstrapTableGroup');
            editor.addMenuItem('bootstrapTable', {
                label: 'Bootstrap Table Classes',
                icon: this.path + 'icons/bootstraptable.png',
                command: 'bootstrapTableDialog',
                group: 'bootstrapTableGroup'
            });
            
            editor.contextMenu.addListener(function(element) {
                if (element.getAscendant('table', true)) {
                    return { bootstrapTable: CKEDITOR.TRISTATE_OFF };
                }
            });
        }
        
        // Register dialog
        CKEDITOR.dialog.add('bootstrapTable', this.path + 'dialogs/bootstrapTable.js');
        
        // Utility functions for table class management
        editor.bootstrapTable = {
            // Add class to table
            addClass: function(table, className) {
                if (!table || !className) return;
                
                var currentClass = table.getAttribute('class') || '';
                var classes = currentClass.split(' ').filter(function(c) { return c.trim(); });
                
                if (classes.indexOf(className) === -1) {
                    classes.push(className);
                    table.setAttribute('class', classes.join(' ').trim());
                }
            },
            
            // Remove class from table
            removeClass: function(table, className) {
                if (!table || !className) return;
                
                var currentClass = table.getAttribute('class') || '';
                var classes = currentClass.split(' ').filter(function(c) { 
                    return c.trim() && c !== className; 
                });
                
                if (classes.length > 0) {
                    table.setAttribute('class', classes.join(' '));
                } else {
                    table.removeAttribute('class');
                }
            },
            
            // Check if table has class
            hasClass: function(table, className) {
                if (!table || !className) return false;
                
                var currentClass = table.getAttribute('class') || '';
                var classes = currentClass.split(' ');
                return classes.indexOf(className) !== -1;
            },
            
            // Get current table from selection
            getCurrentTable: function() {
                var selection = editor.getSelection();
                var ranges = selection.getRanges();
                
                if (ranges.length > 0) {
                    var startElement = ranges[0].getCommonAncestor();
                    return startElement.getAscendant('table', true);
                }
                return null;
            },
            
            // Apply Bootstrap responsive wrapper
            makeResponsive: function(table) {
                if (!table) return;
                
                var wrapper = table.getParent();
                if (!wrapper.hasClass('table-responsive')) {
                    var responsiveDiv = new CKEDITOR.dom.element('div');
                    responsiveDiv.addClass('table-responsive');
                    
                    // Wrap table with responsive div
                    table.insertBefore(responsiveDiv);
                    table.remove();
                    responsiveDiv.append(table);
                }
            },
            
            // Remove responsive wrapper
            removeResponsive: function(table) {
                if (!table) return;
                
                var wrapper = table.getParent();
                if (wrapper.hasClass('table-responsive')) {
                    var parent = wrapper.getParent();
                    table.remove();
                    wrapper.insertBefore(table);
                    wrapper.remove();
                }
            },
            
            // Apply contextual class to row
            applyRowClass: function(row, className) {
                if (!row) return;
                
                // Remove existing contextual classes
                Object.keys(contextualClasses).forEach(function(cls) {
                    row.removeClass(cls);
                });
                
                // Add new class if specified
                if (className && contextualClasses[className]) {
                    row.addClass(className);
                }
            },
            
            // Apply contextual class to cell
            applyCellClass: function(cell, className) {
                if (!cell) return;
                
                // Remove existing contextual classes
                Object.keys(contextualClasses).forEach(function(cls) {
                    cell.removeClass(cls);
                });
                
                // Add new class if specified
                if (className && contextualClasses[className]) {
                    cell.addClass(className);
                }
            }
        };
        
        // Auto-add basic table class when creating new tables
        editor.on('afterInsertHtml', function(evt) {
            var selection = editor.getSelection();
            var table = editor.bootstrapTable.getCurrentTable();
            
            if (table && !table.getAttribute('class')) {
                editor.bootstrapTable.addClass(table, 'table');
            }
        });
        
        // Enhanced table creation command
        var originalTableCommand = editor.getCommand('table');
        if (originalTableCommand) {
            editor.addCommand('bootstrapTableCreate', {
                exec: function(editor) {
                    originalTableCommand.exec(editor);
                    
                    // Add basic Bootstrap class after table creation
                    setTimeout(function() {
                        var table = editor.bootstrapTable.getCurrentTable();
                        if (table) {
                            editor.bootstrapTable.addClass(table, 'table');
                        }
                    }, 100);
                }
            });
        }
    }
});

// Define available Bootstrap classes for export
CKEDITOR.plugins.bootstrapTable = {
    tableClasses: {
        'table': 'Bảng cơ bản',
        'table-striped': 'Hàng xen kẽ', 
        'table-bordered': 'Viền bảng',
        'table-hover': 'Hover hiệu ứng',
        'table-sm': 'Bảng nhỏ gọn',
        'table-responsive': 'Responsive',
        'table-dark': 'Bảng tối',
        'table-light': 'Bảng sáng'
    },
    
    contextualClasses: {
        'table-primary': 'Primary',
        'table-secondary': 'Secondary',
        'table-success': 'Success', 
        'table-danger': 'Danger',
        'table-warning': 'Warning',
        'table-info': 'Info',
        'table-light': 'Light',
        'table-dark': 'Dark'
    }
};
