/**
 * Bootstrap Table Dialog for CKEditor
 * Enhanced table styling with Bootstrap classes
 */

CKEDITOR.dialog.add('bootstrapTable', function(editor) {
    var tableClasses = CKEDITOR.plugins.bootstrapTable.tableClasses;
    var contextualClasses = CKEDITOR.plugins.bootstrapTable.contextualClasses;
    
    return {
        title: 'Bootstrap Table Classes',
        minWidth: 600,
        minHeight: 500,
        
        contents: [
            {
                id: 'tableClasses',
                label: 'Table Classes',
                elements: [
                    {
                        type: 'html',
                        html: '<p><strong>Chọn các thuộc tính Bootstrap cho bảng:</strong></p>'
                    },
                    {
                        type: 'vbox',
                        children: [
                            {
                                type: 'fieldset',
                                label: 'Kiểu bảng cơ bản',
                                children: [
                                    {
                                        type: 'checkbox',
                                        id: 'table',
                                        label: 'table - Kiểu bảng cơ bản Bootstrap',
                                        'default': true
                                    },
                                    {
                                        type: 'checkbox',
                                        id: 'table-striped',
                                        label: 'table-striped - Hàng xen kẽ màu'
                                    },
                                    {
                                        type: 'checkbox',
                                        id: 'table-bordered',
                                        label: 'table-bordered - Viền bảng'
                                    },
                                    {
                                        type: 'checkbox',
                                        id: 'table-hover',
                                        label: 'table-hover - Hiệu ứng hover'
                                    },
                                    {
                                        type: 'checkbox',
                                        id: 'table-sm',
                                        label: 'table-sm - Bảng nhỏ gọn'
                                    }
                                ]
                            },
                            {
                                type: 'fieldset',
                                label: 'Màu sắc và responsive',
                                children: [
                                    {
                                        type: 'radio',
                                        id: 'tableVariant',
                                        label: 'Biến thể màu sắc:',
                                        items: [
                                            ['Mặc định', ''],
                                            ['Dark - Bảng tối', 'table-dark'],
                                            ['Light - Bảng sáng', 'table-light']
                                        ],
                                        'default': ''
                                    },
                                    {
                                        type: 'checkbox',
                                        id: 'table-responsive',
                                        label: 'table-responsive - Responsive (cuộn ngang trên mobile)'
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        type: 'html',
                        html: '<hr style="margin: 15px 0;"><p><strong>Preview:</strong></p>' +
                              '<div id="table-preview" style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9; max-height: 200px; overflow: auto;">' +
                              '<table class="table" style="margin: 0;">' +
                              '<thead><tr><th>Cột 1</th><th>Cột 2</th><th>Cột 3</th></tr></thead>' +
                              '<tbody>' +
                              '<tr><td>Dữ liệu 1</td><td>Dữ liệu 2</td><td>Dữ liệu 3</td></tr>' +
                              '<tr><td>Dữ liệu 4</td><td>Dữ liệu 5</td><td>Dữ liệu 6</td></tr>' +
                              '</tbody></table>' +
                              '</div>'
                    }
                ]
            },
            {
                id: 'rowClasses',
                label: 'Row/Cell Classes',
                elements: [
                    {
                        type: 'html',
                        html: '<p><strong>Áp dụng màu contextual cho hàng/ô hiện tại:</strong></p>'
                    },
                    {
                        type: 'radio',
                        id: 'contextualClass',
                        label: 'Chọn màu contextual:',
                        items: [
                            ['Không áp dụng', ''],
                            ['Primary - Xanh chính', 'table-primary'],
                            ['Secondary - Xám', 'table-secondary'],
                            ['Success - Xanh lá', 'table-success'],
                            ['Danger - Đỏ', 'table-danger'],
                            ['Warning - Vàng', 'table-warning'],
                            ['Info - Xanh nhạt', 'table-info'],
                            ['Light - Sáng', 'table-light'],
                            ['Dark - Tối', 'table-dark']
                        ],
                        'default': ''
                    },
                    {
                        type: 'radio',
                        id: 'applyTo',
                        label: 'Áp dụng cho:',
                        items: [
                            ['Hàng hiện tại', 'row'],
                            ['Ô hiện tại', 'cell']
                        ],
                        'default': 'row'
                    },
                    {
                        type: 'html',
                        html: '<div style="margin-top: 15px; padding: 10px; background: #e3f2fd; border-radius: 4px;">' +
                              '<strong>Lưu ý:</strong> Màu contextual sẽ được áp dụng cho hàng hoặc ô mà bạn đang chọn.' +
                              '</div>'
                    }
                ]
            },
            {
                id: 'advanced',
                label: 'Advanced',
                elements: [
                    {
                        type: 'html',
                        html: '<p><strong>Các tùy chọn nâng cao:</strong></p>'
                    },
                    {
                        type: 'textarea',
                        id: 'customClasses',
                        label: 'Custom CSS Classes (phân cách bằng dấu cách):',
                        rows: 3,
                        cols: 50
                    },
                    {
                        type: 'checkbox',
                        id: 'removeAllClasses',
                        label: 'Xóa tất cả classes hiện tại trước khi áp dụng'
                    },
                    {
                        type: 'html',
                        html: '<div style="margin-top: 15px; padding: 10px; background: #fff3cd; border-radius: 4px;">' +
                              '<strong>Hướng dẫn sử dụng:</strong><br>' +
                              '• Chọn bảng trước khi mở dialog này<br>' +
                              '• Có thể kết hợp nhiều classes Bootstrap<br>' +
                              '• Classes sẽ được áp dụng ngay lập tức<br>' +
                              '• Responsive wrapper sẽ tự động thêm div bao quanh' +
                              '</div>'
                    }
                ]
            }
        ],
        
        onShow: function() {
            var dialog = this;
            var editor = dialog.getParentEditor();
            var table = editor.bootstrapTable.getCurrentTable();
            
            if (!table) {
                alert('Vui lòng chọn một bảng trước khi mở dialog này.');
                dialog.hide();
                return;
            }
            
            // Store current table reference
            dialog.table = table;
            
            // Load current table classes
            var currentClasses = (table.getAttribute('class') || '').split(' ');
            
            // Set checkbox states based on current classes
            Object.keys(tableClasses).forEach(function(className) {
                var checkbox = dialog.getContentElement('tableClasses', className);
                if (checkbox) {
                    checkbox.setValue(currentClasses.indexOf(className) !== -1);
                }
            });
            
            // Set table variant
            var variant = '';
            if (currentClasses.indexOf('table-dark') !== -1) {
                variant = 'table-dark';
            } else if (currentClasses.indexOf('table-light') !== -1) {
                variant = 'table-light';
            }
            dialog.getContentElement('tableClasses', 'tableVariant').setValue(variant);
            
            // Update preview
            updatePreview.call(dialog);
            
            // Add event listeners for real-time preview
            setupPreviewListeners.call(dialog);
        },
        
        onOk: function() {
            var dialog = this;
            var editor = dialog.getParentEditor();
            var table = dialog.table;
            
            if (!table) return;
            
            // Remove all classes if requested
            if (dialog.getValueOf('advanced', 'removeAllClasses')) {
                table.removeAttribute('class');
            }
            
            // Apply table classes
            var currentClasses = (table.getAttribute('class') || '').split(' ').filter(function(c) {
                return c.trim() && !Object.keys(tableClasses).includes(c.trim());
            });
            
            // Add selected Bootstrap classes
            Object.keys(tableClasses).forEach(function(className) {
                var checkbox = dialog.getContentElement('tableClasses', className);
                if (checkbox && checkbox.getValue()) {
                    currentClasses.push(className);
                }
            });
            
            // Add table variant
            var variant = dialog.getValueOf('tableClasses', 'tableVariant');
            if (variant) {
                currentClasses.push(variant);
            }
            
            // Add custom classes
            var customClasses = dialog.getValueOf('advanced', 'customClasses');
            if (customClasses) {
                var custom = customClasses.split(' ').filter(function(c) { return c.trim(); });
                currentClasses = currentClasses.concat(custom);
            }
            
            // Update table class attribute
            if (currentClasses.length > 0) {
                table.setAttribute('class', currentClasses.join(' '));
            }
            
            // Handle responsive wrapper
            var isResponsive = dialog.getValueOf('tableClasses', 'table-responsive');
            if (isResponsive) {
                editor.bootstrapTable.makeResponsive(table);
                // Remove table-responsive from table itself
                editor.bootstrapTable.removeClass(table, 'table-responsive');
            } else {
                editor.bootstrapTable.removeResponsive(table);
            }
            
            // Apply contextual classes to rows/cells
            var contextualClass = dialog.getValueOf('rowClasses', 'contextualClass');
            var applyTo = dialog.getValueOf('rowClasses', 'applyTo');
            
            if (contextualClass) {
                var selection = editor.getSelection();
                var ranges = selection.getRanges();
                
                if (ranges.length > 0) {
                    var element = ranges[0].getCommonAncestor();
                    
                    if (applyTo === 'row') {
                        var row = element.getAscendant('tr', true);
                        if (row) {
                            editor.bootstrapTable.applyRowClass(row, contextualClass);
                        }
                    } else {
                        var cell = element.getAscendant(['td', 'th'], true);
                        if (cell) {
                            editor.bootstrapTable.applyCellClass(cell, contextualClass);
                        }
                    }
                }
            }
        }
    };
    
    // Helper function to update preview
    function updatePreview() {
        var dialog = this;
        var previewTable = CKEDITOR.document.getById('table-preview').findOne('table');
        
        if (!previewTable) return;
        
        // Reset classes
        previewTable.setAttribute('class', 'table');
        
        // Apply selected classes
        Object.keys(tableClasses).forEach(function(className) {
            var checkbox = dialog.getContentElement('tableClasses', className);
            if (checkbox && checkbox.getValue()) {
                previewTable.addClass(className);
            }
        });
        
        // Apply variant
        var variant = dialog.getValueOf('tableClasses', 'tableVariant');
        if (variant) {
            previewTable.addClass(variant);
        }
    }
    
    // Helper function to setup preview listeners
    function setupPreviewListeners() {
        var dialog = this;
        
        // Add listeners to all checkboxes and radio buttons
        Object.keys(tableClasses).forEach(function(className) {
            var element = dialog.getContentElement('tableClasses', className);
            if (element) {
                element.getInputElement().on('change', function() {
                    updatePreview.call(dialog);
                });
            }
        });
        
        var variantElement = dialog.getContentElement('tableClasses', 'tableVariant');
        if (variantElement) {
            variantElement.getInputElement().on('change', function() {
                updatePreview.call(dialog);
            });
        }
    }
});
