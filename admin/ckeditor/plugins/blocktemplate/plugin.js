CKEDITOR.plugins.add('blocktemplate', {
    requires: 'dialog',
    icons: 'blocktemplate',
    init: function(editor) {
        var pluginDirectory = this.path;
        
        // Load blocksData from blocks.json
        var blocksData = {};
        var xhr = new XMLHttpRequest();
        xhr.open('GET', pluginDirectory + 'blocks.json', false);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                blocksData = JSON.parse(xhr.responseText);
            }
        };
        xhr.send();
        
        // Thêm nút vào toolbar
        editor.ui.addButton('BlockTemplate', {
            label: 'Chèn block mẫu',
            command: 'openBlockTemplateDialog',
            toolbar: 'insert'
        });
        
        // Đăng ký command để mở dialog
        editor.addCommand('openBlockTemplateDialog', {
            exec: function(editor) {
                editor.openDialog('blockTemplateDialog');
            }
        });
        
        // Hàm mở dialog
        function openDialog(editor) {
            // Tạo nội dung HTML của dialog
            var dialogDefinition = {
                title: 'Chọn Block Mẫu',
                minWidth: 600,
                minHeight: 400,
                contents: [
                    {
                        id: 'tab1',
                        label: 'Chọn Block',
                        elements: [
                            {
                                type: 'html',
                                html: buildDialogHtml()
                            }
                        ]
                    }
                ],
                buttons: [CKEDITOR.dialog.cancelButton],
                onShow: function() {
                    var dialog = this;
                    
                    // Xử lý sự kiện khi click vào block
                    var blockItems = dialog.getElement().find('.block-item');
                    for (var i = 0; i < blockItems.count(); i++) {
                        blockItems.getItem(i).on('click', function() {
                            var blockId = this.getAttribute('data-block-id');
                            var block = findBlockById(blockId);
                            if (block) {
                                insertBlockContent(editor, block.code_html);
                                dialog.hide();
                            }
                        });
                    }
                    
                    // Xử lý sự kiện lọc theo danh mục
                    var categoryFilters = dialog.getElement().find('.category-filter');
                    for (var i = 0; i < categoryFilters.count(); i++) {
                        categoryFilters.getItem(i).on('click', function() {
                            var categoryId = this.getAttribute('data-category');
                            filterBlocksByCategory(dialog, categoryId);
                        });
                    }
                    
                    // Xử lý sự kiện tìm kiếm
                    var searchInput = dialog.getElement().findOne('#block-search-input');
                    if (searchInput) {
                        searchInput.on('keyup', function() {
                            var searchTerm = this.getValue().toLowerCase();
                            searchBlocks(dialog, searchTerm);
                        });
                    }
                }
            };
            
            return dialogDefinition;
        }
        
        // Hàm tạo HTML cho dialog
        function buildDialogHtml() {
            var html = '<div class="block-template-dialog">';
            
            // Tạo thanh tìm kiếm
            html += '<div class="block-search">';
            html += '<input type="text" placeholder="Tìm kiếm block..." id="block-search-input">';
            html += '</div>';
            
            // Tạo bộ lọc danh mục
            html += '<div class="category-filters">';
            html += '<span class="category-filter active" data-category="all">Tất cả</span>';
            
            blocksData.categories.forEach(function(category) {
                html += '<span class="category-filter" data-category="' + category.id + '">' + category.name + '</span>';
            });
            
            html += '</div>';
            
            // Tạo danh sách blocks
            html += '<div class="block-list">';
            
            blocksData.blocks.forEach(function(block) {
                html += '<div class="block-item" data-block-id="' + block.id + '" data-category="' + block.category + '">';
                html += '<div class="block-thumbnail"><img src="' + pluginDirectory + block.thumbnail + '" alt="' + block.name + '"></div>';
                html += '<div class="block-info">';
                html += '<div class="block-name">' + block.name + '</div>';
                html += '<div class="block-description">' + block.description + '</div>';
                html += '</div>';
                html += '</div>';
            });
            
            html += '</div>';
            
            // Thêm link điều hướng đến admin.php
            html += '<div class="admin-link">';
            html += '<a href="' + pluginDirectory + 'admin.php" target="_blank">Quản lý Block Template</a>';
            html += '</div>';
            
            html += '</div>';
            
            return html;
        }
        
        // Hàm tìm block theo ID
        function findBlockById(id) {
            for (var i = 0; i < blocksData.blocks.length; i++) {
                if (blocksData.blocks[i].id === id) {
                    return blocksData.blocks[i];
                }
            }
            return null;
        }
        
        // Hàm lọc blocks theo danh mục
        function filterBlocksByCategory(dialog, categoryId) {
            var blockItems = dialog.getElement().find('.block-item');
            var filters = dialog.getElement().find('.category-filter');
            
            // Cập nhật trạng thái active của bộ lọc
            for (var i = 0; i < filters.count(); i++) {
                if (filters.getItem(i).getAttribute('data-category') === categoryId) {
                    filters.getItem(i).addClass('active');
                } else {
                    filters.getItem(i).removeClass('active');
                }
            }
            
            // Lọc các block
            for (var j = 0; j < blockItems.count(); j++) {
                var item = blockItems.getItem(j);
                if (categoryId === 'all' || item.getAttribute('data-category') === categoryId) {
                    item.setStyle('display', 'block');
                } else {
                    item.setStyle('display', 'none');
                }
            }
        }
        
        // Hàm tìm kiếm blocks
        function searchBlocks(dialog, searchTerm) {
            if (!searchTerm) {
                // Nếu không có từ khóa tìm kiếm, hiển thị tất cả
                dialog.getElement().find('.block-item').forEach(function(item) {
                    item.setStyle('display', 'block');
                });
                return;
            }
            
            var blockItems = dialog.getElement().find('.block-item');
            for (var i = 0; i < blockItems.count(); i++) {
                var item = blockItems.getItem(i);
                var blockId = item.getAttribute('data-block-id');
                var block = findBlockById(blockId);
                
                if (block && (
                    block.name.toLowerCase().indexOf(searchTerm) !== -1 ||
                    block.description.toLowerCase().indexOf(searchTerm) !== -1
                )) {
                    item.setStyle('display', 'block');
                } else {
                    item.setStyle('display', 'none');
                }
            }
        }
        
        // Hàm chèn nội dung block vào editor
        function insertBlockContent(editor, html) {
            editor.insertHtml(html);
        }
        
        // Đăng ký dialog
        CKEDITOR.dialog.add('blockTemplateDialog', function(editor) {
            return openDialog(editor);
        });
        
        // Thêm CSS cho dialog
        CKEDITOR.document.appendStyleSheet(pluginDirectory + 'styles.css');
    }
});