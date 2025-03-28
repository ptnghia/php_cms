CKEDITOR.dialog.add('bootstrapGridDialog', function(editor) {
    return {
        title: 'Chèn Bootstrap Grid',
        minWidth: 600,
        minHeight: 400,
        contents: [
            {
                id: 'tab-basic',
                label: 'Bố cục lưới',
                elements: [
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
                            // Cập nhật bản xem trước khi bố cục thay đổi
                            updatePreview(this.getValue(), editor.id);
                        },
                        setup: function(widget) {
                            this.setValue('12');
                        }
                    },
                    {
                        type: 'checkbox',
                        id: 'container',
                        label: 'Thêm lớp container',
                        'default': true
                    },
                    {
                        type: 'html',
                        id: 'previewContainer', // Thêm ID cho container
                        html: '<div id="bootstrapGridPreview_' + editor.id + '" class="grid-preview"></div>'
                    },
                    {
                        type: 'text',
                        id: 'customLayout',
                        label: 'Bố cục tùy chỉnh (các số cột Bootstrap cách nhau bằng dấu phẩy, ví dụ: "3,6,3")',
                        validate: function() {
                            var value = this.getValue();
                            if (value) {
                                var cols = value.split(',').map(function(col) { 
                                    return parseInt(col.trim()); 
                                });
                                
                                var sum = cols.reduce(function(a, b) { return a + b; }, 0);
                                if (sum !== 12) {
                                    alert('Chiều rộng các cột phải tổng cộng là 12.');
                                    return false;
                                }
                                
                                for (var i = 0; i < cols.length; i++) {
                                    if (isNaN(cols[i]) || cols[i] < 1 || cols[i] > 12) {
                                        alert('Mỗi cột phải là một số từ 1 đến 12.');
                                        return false;
                                    }
                                }
                            }
                            return true;
                        }
                    }
                ]
            }
        ],
        onShow: function() {
            // Khởi tạo bản xem trước khi mở hộp thoại
            updatePreview('12', editor.id);
        },
        onOk: function() {
            var dialog = this;
            var useContainer = dialog.getValueOf('tab-basic', 'container');
            var layout = dialog.getValueOf('tab-basic', 'layout');
            var customLayout = dialog.getValueOf('tab-basic', 'customLayout');
            
            var columns = customLayout ? customLayout.split(',').map(function(col) { 
                return parseInt(col.trim()); 
            }) : layout.split(',').map(function(col) { 
                return parseInt(col); 
            });
            
            var html = useContainer ? '<div class="container">\n' : '';
            html += '    <div class="row">\n';
            
            columns.forEach(function(col, index) {
                html += '        <div class="col-md-' + col + '">\n';
                html += '            <p>Cột ' + (index + 1) + '</p>\n';
                html += '        </div>\n';
            });
            
            html += '    </div>\n';
            html += useContainer ? '</div>' : '';
            
            editor.insertHtml(html);
        }
    };
});

// Function to update the preview
function updatePreview(layout, editorId) {
    var previewDiv = document.getElementById('bootstrapGridPreview_' + editorId);
    if (!previewDiv) return;
    
    var columns = layout.split(',').map(function(col) { 
        return parseInt(col); 
    });
    
    var html = '<div class="preview-container">';
    html += '    <div class="preview-row">';
    
    columns.forEach(function(col) {
        var width = (col / 12 * 100) + '%';
        html += '<div class="preview-col" style="width: ' + width + '">';
        html += '    <div class="preview-col-inner">' + col + '</div>';
        html += '</div>';
    });
    
    html += '    </div>';
    html += '</div>';
    
    previewDiv.innerHTML = html;
}
