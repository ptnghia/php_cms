CKEDITOR.dialog.add('bootstrapGridDialog', function(editor) {
    var gridConfig = {
        layout: { md: [12] },
        offsets: {},
        alignment: {},
        currentBreakpoint: 'md',
        useContainer: true,
        useGutters: true,
        template: null
    };

    return {
        title: 'Chèn Bootstrap Grid',
        minWidth: 800,
        minHeight: 600,
        contents: [
            {
                id: 'tab-basic',
                label: 'Bố cục cơ bản',
                elements: [
                    {
                        type: 'select',
                        id: 'template',
                        label: 'Template có sẵn',
                        items: [
                            ['Không sử dụng template', ''],
                            ['Hero Section', 'hero'],
                            ['Ba cột Cards', 'three-cards'],
                            ['Hai cột nội dung', 'two-columns'],
                            ['Nội dung + Sidebar', 'sidebar']
                        ],
                        'default': '',
                        onChange: function() {
                            var templateId = this.getValue();
                            var dialog = this.getDialog();
                            
                            if (templateId && CKEDITOR.plugins.bootstrapGrid.templates[templateId]) {
                                var template = CKEDITOR.plugins.bootstrapGrid.templates[templateId];
                                gridConfig.template = templateId;
                                gridConfig.layout = template.layout;
                                
                                // Update layout display
                                var layoutSelect = dialog.getContentElement('tab-basic', 'layout');
                                var layoutStr = template.layout.md.join(',');
                                layoutSelect.setValue(layoutStr);
                                
                                // Update preview
                                CKEDITOR.plugins.bootstrapGrid.updatePreview(gridConfig, editor.id);
                            } else {
                                gridConfig.template = null;
                            }
                        }
                    },
                    {
                        type: 'select',
                        id: 'layout',
                        label: 'Chọn bố cục',
                        items: [
                            ['1 Cột', '12'],
                            ['2 Cột (50% - 50%)', '6,6'],
                            ['3 Cột (33% mỗi cột)', '4,4,4'],
                            ['4 Cột (25% mỗi cột)', '3,3,3,3'],
                            ['6 Cột (16.6% mỗi cột)', '2,2,2,2,2,2'],
                            ['2 Cột (30% - 70%)', '3,9'],
                            ['2 Cột (40% - 60%)', '4,8'],
                            ['2 Cột (42% - 58%)', '5,7']
                        ],
                        'default': '12',
                        onChange: function() {
                            var layoutStr = this.getValue();
                            var layout = layoutStr.split(',').map(function(col) { 
                                return parseInt(col.trim()); 
                            });
                            
                            gridConfig.layout[gridConfig.currentBreakpoint] = layout;
                            gridConfig.template = null; // Clear template when manual layout is selected
                            
                            var dialog = this.getDialog();
                            dialog.getContentElement('tab-basic', 'template').setValue('');
                            
                            CKEDITOR.plugins.bootstrapGrid.updatePreview(gridConfig, editor.id);
                        }
                    },
                    {
                        type: 'text',
                        id: 'customLayout',
                        label: 'Bố cục tùy chỉnh (các số cột Bootstrap cách nhau bằng dấu phẩy)',
                        validate: function() {
                            var value = this.getValue();
                            if (value) {
                                var cols = value.split(',').map(function(col) { 
                                    return parseInt(col.trim()); 
                                });
                                
                                return CKEDITOR.plugins.bootstrapGrid.validateLayout(cols, gridConfig.currentBreakpoint);
                            }
                            return true;
                        },
                        onChange: function() {
                            var value = this.getValue();
                            if (value) {
                                var layout = value.split(',').map(function(col) { 
                                    return parseInt(col.trim()); 
                                });
                                
                                if (CKEDITOR.plugins.bootstrapGrid.validateLayout(layout, gridConfig.currentBreakpoint)) {
                                    gridConfig.layout[gridConfig.currentBreakpoint] = layout;
                                    gridConfig.template = null;
                                    
                                    var dialog = this.getDialog();
                                    dialog.getContentElement('tab-basic', 'template').setValue('');
                                    
                                    CKEDITOR.plugins.bootstrapGrid.updatePreview(gridConfig, editor.id);
                                }
                            }
                        }
                    },
                    {
                        type: 'html',
                        html: '<div id="bootstrapGridPreview_' + editor.id + '" class="grid-preview"></div>'
                    }
                ]
            },
            {
                id: 'tab-responsive',
                label: 'Responsive',
                elements: [
                    {
                        type: 'hbox',
                        children: [
                            {
                                type: 'button',
                                id: 'btn-xs',
                                label: 'XS',
                                title: 'Extra Small (≥0px)',
                                onClick: function() {
                                    gridConfig.currentBreakpoint = 'xs';
                                    updateBreakpointUI(this.getDialog(), 'xs');
                                }
                            },
                            {
                                type: 'button',
                                id: 'btn-sm',
                                label: 'SM',
                                title: 'Small (≥576px)',
                                onClick: function() {
                                    gridConfig.currentBreakpoint = 'sm';
                                    updateBreakpointUI(this.getDialog(), 'sm');
                                }
                            },
                            {
                                type: 'button',
                                id: 'btn-md',
                                label: 'MD',
                                title: 'Medium (≥768px)',
                                onClick: function() {
                                    gridConfig.currentBreakpoint = 'md';
                                    updateBreakpointUI(this.getDialog(), 'md');
                                }
                            },
                            {
                                type: 'button',
                                id: 'btn-lg',
                                label: 'LG',
                                title: 'Large (≥992px)',
                                onClick: function() {
                                    gridConfig.currentBreakpoint = 'lg';
                                    updateBreakpointUI(this.getDialog(), 'lg');
                                }
                            },
                            {
                                type: 'button',
                                id: 'btn-xl',
                                label: 'XL',
                                title: 'Extra Large (≥1200px)',
                                onClick: function() {
                                    gridConfig.currentBreakpoint = 'xl';
                                    updateBreakpointUI(this.getDialog(), 'xl');
                                }
                            }
                        ]
                    },
                    {
                        type: 'text',
                        id: 'responsiveLayout',
                        label: 'Layout cho breakpoint hiện tại',
                        onChange: function() {
                            var value = this.getValue();
                            if (value) {
                                var layout = value.split(',').map(function(col) { 
                                    return parseInt(col.trim()); 
                                });
                                
                                if (CKEDITOR.plugins.bootstrapGrid.validateLayout(layout, gridConfig.currentBreakpoint)) {
                                    gridConfig.layout[gridConfig.currentBreakpoint] = layout;
                                    CKEDITOR.plugins.bootstrapGrid.updatePreview(gridConfig, editor.id);
                                }
                            }
                        }
                    },
                    {
                        type: 'text',
                        id: 'offsetLayout',
                        label: 'Offset cho từng cột (cách nhau bằng dấu phẩy, 0 = không offset)',
                        onChange: function() {
                            var value = this.getValue();
                            if (value) {
                                var offsets = value.split(',').map(function(offset) { 
                                    return parseInt(offset.trim()) || 0; 
                                });
                                
                                if (!gridConfig.offsets[gridConfig.currentBreakpoint]) {
                                    gridConfig.offsets[gridConfig.currentBreakpoint] = {};
                                }
                                
                                offsets.forEach(function(offset, index) {
                                    gridConfig.offsets[gridConfig.currentBreakpoint][index] = offset;
                                });
                                
                                CKEDITOR.plugins.bootstrapGrid.updatePreview(gridConfig, editor.id);
                            }
                        }
                    }
                ]
            },
            {
                id: 'tab-advanced',
                label: 'Nâng cao',
                elements: [
                    {
                        type: 'checkbox',
                        id: 'container',
                        label: 'Thêm lớp container',
                        'default': true,
                        onChange: function() {
                            gridConfig.useContainer = this.getValue();
                        }
                    },
                    {
                        type: 'checkbox',
                        id: 'gutters',
                        label: 'Sử dụng gutters (khoảng cách giữa các cột)',
                        'default': true,
                        onChange: function() {
                            gridConfig.useGutters = this.getValue();
                        }
                    },
                    {
                        type: 'select',
                        id: 'horizontalAlign',
                        label: 'Căn chỉnh ngang',
                        items: [
                            ['Mặc định', ''],
                            ['Bắt đầu', 'start'],
                            ['Giữa', 'center'],
                            ['Kết thúc', 'end'],
                            ['Xung quanh', 'around'],
                            ['Giữa các phần tử', 'between']
                        ],
                        'default': '',
                        onChange: function() {
                            gridConfig.alignment.horizontal = this.getValue();
                        }
                    },
                    {
                        type: 'select',
                        id: 'verticalAlign',
                        label: 'Căn chỉnh dọc',
                        items: [
                            ['Mặc định', ''],
                            ['Trên cùng', 'start'],
                            ['Giữa', 'center'],
                            ['Dưới cùng', 'end'],
                            ['Căn đều', 'stretch']
                        ],
                        'default': '',
                        onChange: function() {
                            gridConfig.alignment.vertical = this.getValue();
                        }
                    }
                ]
            }
        ],
        onShow: function() {
            // Initialize preview
            CKEDITOR.plugins.bootstrapGrid.updatePreview(gridConfig, editor.id);
            
            // Set active breakpoint button
            updateBreakpointUI(this, gridConfig.currentBreakpoint);
        },
        onOk: function() {
            var html = CKEDITOR.plugins.bootstrapGrid.generateGridHTML(gridConfig);
            editor.insertHtml(html);
        }
    };
    
    function updateBreakpointUI(dialog, breakpoint) {
        // Update button styles (simulate active state)
        var breakpoints = ['xs', 'sm', 'md', 'lg', 'xl'];
        breakpoints.forEach(function(bp) {
            var btn = dialog.getContentElement('tab-responsive', 'btn-' + bp);
            if (btn && btn.getElement()) {
                var element = btn.getElement();
                if (bp === breakpoint) {
                    element.addClass('active');
                } else {
                    element.removeClass('active');
                }
            }
        });
        
        // Update layout input for current breakpoint
        var layoutInput = dialog.getContentElement('tab-responsive', 'responsiveLayout');
        if (layoutInput && gridConfig.layout[breakpoint]) {
            layoutInput.setValue(gridConfig.layout[breakpoint].join(','));
        } else {
            layoutInput.setValue('');
        }
        
        // Update offset input for current breakpoint
        var offsetInput = dialog.getContentElement('tab-responsive', 'offsetLayout');
        if (offsetInput && gridConfig.offsets[breakpoint]) {
            var offsets = [];
            var maxIndex = Math.max.apply(Math, Object.keys(gridConfig.offsets[breakpoint]).map(Number));
            for (var i = 0; i <= maxIndex; i++) {
                offsets.push(gridConfig.offsets[breakpoint][i] || 0);
            }
            offsetInput.setValue(offsets.join(','));
        } else {
            offsetInput.setValue('');
        }
        
        // Update preview
        CKEDITOR.plugins.bootstrapGrid.updatePreview(gridConfig, editor.id);
    }
});
