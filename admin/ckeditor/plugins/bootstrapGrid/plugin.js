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
            label: 'Chèn Bootstrap Grid',
            command: 'insertBootstrapGrid',
            toolbar: 'insert'
        });
    }
});

// Namespace for Bootstrap Grid utilities
CKEDITOR.plugins.bootstrapGrid = {
    // Bootstrap breakpoints
    breakpoints: {
        xs: { min: 0, max: 575, label: 'XS (≥0px)' },
        sm: { min: 576, max: 767, label: 'SM (≥576px)' },
        md: { min: 768, max: 991, label: 'MD (≥768px)' },
        lg: { min: 992, max: 1199, label: 'LG (≥992px)' },
        xl: { min: 1200, max: 9999, label: 'XL (≥1200px)' }
    },
    
    // Grid templates
    templates: {
        'hero': {
            name: 'Hero Section',
            layout: { md: [12] },
            content: '<div class="jumbotron text-center"><h1 class="display-4">Tiêu đề chính</h1><p class="lead">Mô tả ngắn gọn về sản phẩm hoặc dịch vụ.</p><a class="btn btn-primary btn-lg" href="#" role="button">Tìm hiểu thêm</a></div>'
        },
        'three-cards': {
            name: 'Ba cột Cards',
            layout: { md: [4, 4, 4] },
            content: '<div class="card"><div class="card-body"><h5 class="card-title">Tiêu đề Card</h5><p class="card-text">Nội dung mô tả ngắn gọn.</p><a href="#" class="btn btn-primary">Xem chi tiết</a></div></div>'
        },
        'two-columns': {
            name: 'Hai cột nội dung',
            layout: { md: [6, 6] },
            content: '<h3>Tiêu đề</h3><p>Nội dung đoạn văn mẫu. Thay thế bằng nội dung thực tế của bạn.</p>'
        },
        'sidebar': {
            name: 'Nội dung + Sidebar',
            layout: { md: [8, 4] },
            content: ['<article><h2>Bài viết chính</h2><p>Nội dung bài viết chi tiết...</p></article>', '<aside><h4>Sidebar</h4><ul><li>Liên kết 1</li><li>Liên kết 2</li><li>Liên kết 3</li></ul></aside>']
        }
    },
    
    // Utility functions
    updatePreview: function(config, editorId) {
        var previewDiv = document.getElementById('bootstrapGridPreview_' + editorId);
        if (!previewDiv) return;
        
        var currentBreakpoint = config.currentBreakpoint || 'md';
        var columns = config.layout[currentBreakpoint] || [12];
        
        var html = '<div class="preview-container">';
        html += '    <div class="preview-row">';
        
        columns.forEach(function(col, index) {
            var width = (col / 12 * 100) + '%';
            var offset = config.offsets && config.offsets[currentBreakpoint] ? config.offsets[currentBreakpoint][index] || 0 : 0;
            var marginLeft = (offset / 12 * 100) + '%';
            
            html += '<div class="preview-col" style="width: ' + width + '; margin-left: ' + marginLeft + '">';
            html += '    <div class="preview-col-inner">';
            html += '        <span class="col-info">col-' + currentBreakpoint + '-' + col;
            if (offset > 0) html += '<br>offset-' + currentBreakpoint + '-' + offset;
            html += '</span>';
            html += '    </div>';
            html += '</div>';
        });
        
        html += '    </div>';
        html += '</div>';
        
        // Add breakpoint indicator
        html += '<div class="preview-breakpoint">Xem trước: ' + this.breakpoints[currentBreakpoint].label + '</div>';
        
        previewDiv.innerHTML = html;
    },
    
    validateLayout: function(layout, breakpoint) {
        if (!layout || layout.length === 0) return false;
        
        var sum = layout.reduce(function(a, b) { return a + b; }, 0);
        if (sum > 12) {
            alert('Tổng chiều rộng các cột không được vượt quá 12 cho breakpoint ' + breakpoint.toUpperCase());
            return false;
        }
        
        for (var i = 0; i < layout.length; i++) {
            if (isNaN(layout[i]) || layout[i] < 1 || layout[i] > 12) {
                alert('Mỗi cột phải là một số từ 1 đến 12');
                return false;
            }
        }
        
        return true;
    },
    
    generateGridHTML: function(config) {
        var useContainer = config.useContainer;
        var template = config.template;
        var layout = config.layout;
        var offsets = config.offsets || {};
        var alignment = config.alignment || {};
        var useGutters = config.useGutters !== false;
        
        var html = useContainer ? '<div class="container">\n' : '';
        
        // Row classes
        var rowClasses = ['row'];
        if (!useGutters) rowClasses.push('no-gutters');
        if (alignment.horizontal) rowClasses.push('justify-content-' + alignment.horizontal);
        if (alignment.vertical) rowClasses.push('align-items-' + alignment.vertical);
        
        html += '    <div class="' + rowClasses.join(' ') + '">\n';
        
        // Generate columns for each breakpoint
        Object.keys(layout).forEach(function(breakpoint) {
            var cols = layout[breakpoint];
            if (!cols) return;
            
            cols.forEach(function(colSize, index) {
                if (index === 0 || breakpoint === 'md') { // Only generate column divs once
                    var colClasses = [];
                    
                    // Add column classes for all breakpoints
                    Object.keys(layout).forEach(function(bp) {
                        if (layout[bp] && layout[bp][index]) {
                            colClasses.push('col-' + bp + '-' + layout[bp][index]);
                            
                            // Add offset if exists
                            if (offsets[bp] && offsets[bp][index]) {
                                colClasses.push('offset-' + bp + '-' + offsets[bp][index]);
                            }
                        }
                    });
                    
                    html += '        <div class="' + colClasses.join(' ') + '">\n';
                    
                    // Insert content based on template
                    if (template && this.templates[template]) {
                        var templateContent = this.templates[template].content;
                        if (Array.isArray(templateContent)) {
                            html += '            ' + (templateContent[index] || templateContent[0]) + '\n';
                        } else {
                            html += '            ' + templateContent + '\n';
                        }
                    } else {
                        html += '            <p>Cột ' + (index + 1) + '</p>\n';
                    }
                    
                    html += '        </div>\n';
                }
            }.bind(this));
        }.bind(this));
        
        html += '    </div>\n';
        html += useContainer ? '</div>' : '';
        
        return html;
    }
};
