CKEDITOR.plugins.add('blocktemplate', {
    requires: 'dialog',
    icons: 'blocktemplate',
    init: function(editor) {
        var pluginDirectory = this.path;
        
        // Namespace for plugin utilities
        CKEDITOR.plugins.blockTemplate = {
            blocksData: { blocks: [], categories: [] },
            favorites: JSON.parse(localStorage.getItem('blocktemplate_favorites') || '[]'),
            recentBlocks: JSON.parse(localStorage.getItem('blocktemplate_recent') || '[]'),
            
            // Load blocks data asynchronously
            loadBlocksData: function() {
                return new Promise(function(resolve, reject) {
                    try {
                        var xhr = new XMLHttpRequest();
                        xhr.open('GET', pluginDirectory + 'blocks.json?v=' + Date.now(), true);
                        xhr.timeout = 10000; // 10 second timeout
                        
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                try {
                                    var data = JSON.parse(xhr.responseText);
                                    CKEDITOR.plugins.blockTemplate.blocksData = data;
                                    resolve(data);
                                } catch (parseError) {
                                    console.error('Block Template: Invalid JSON response', parseError);
                                    reject(new Error('Invalid JSON response'));
                                }
                            } else {
                                reject(new Error('HTTP ' + xhr.status + ': ' + xhr.statusText));
                            }
                        };
                        
                        xhr.onerror = function() {
                            reject(new Error('Network error loading blocks data'));
                        };
                        
                        xhr.ontimeout = function() {
                            reject(new Error('Timeout loading blocks data'));
                        };
                        
                        xhr.send();
                    } catch (error) {
                        reject(error);
                    }
                });
            },
            
            // Add to favorites
            toggleFavorite: function(blockId) {
                var index = this.favorites.indexOf(blockId);
                if (index > -1) {
                    this.favorites.splice(index, 1);
                } else {
                    this.favorites.push(blockId);
                }
                localStorage.setItem('blocktemplate_favorites', JSON.stringify(this.favorites));
                return this.favorites.indexOf(blockId) > -1;
            },
            
            // Add to recent blocks
            addToRecent: function(blockId) {
                var index = this.recentBlocks.indexOf(blockId);
                if (index > -1) {
                    this.recentBlocks.splice(index, 1);
                }
                this.recentBlocks.unshift(blockId);
                
                // Keep only last 10 recent blocks
                if (this.recentBlocks.length > 10) {
                    this.recentBlocks = this.recentBlocks.slice(0, 10);
                }
                
                localStorage.setItem('blocktemplate_recent', JSON.stringify(this.recentBlocks));
            },
            
            // Process template variables
            processTemplate: function(html, variables) {
                if (!variables) return html;
                
                return html.replace(/\{\{(\w+)\}\}/g, function(match, key) {
                    return variables[key] || match;
                });
            },
            
            // Track block usage
            trackUsage: function(blockId) {
                try {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', pluginDirectory + 'track_usage.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.send(JSON.stringify({
                        blockId: blockId,
                        timestamp: Date.now(),
                        editor: editor.name || 'unknown'
                    }));
                } catch (error) {
                    console.warn('Block Template: Could not track usage', error);
                }
            },
            
            // Show user-friendly error message
            showError: function(message, technical) {
                if (technical) {
                    console.error('Block Template Error:', technical);
                }
                
                // Show user-friendly message
                if (window.alert) {
                    alert('Block Template: ' + message);
                } else {
                    console.error('Block Template: ' + message);
                }
            }
        };
        
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
        
        // Enhanced dialog with async loading and preview
        function openDialog(editor) {
            var dialogDefinition = {
                title: 'Chọn Block Mẫu',
                minWidth: 1200,
                minHeight: 800,
                contents: [
                    {
                        id: 'tab1',
                        label: 'Chọn Block',
                        elements: [
                            {
                                type: 'html',
                                html: '<div id="block-loading">Đang tải blocks...</div>'
                            }
                        ]
                    }
                ],
                buttons: [CKEDITOR.dialog.cancelButton],
                onShow: function() {
                    var dialog = this;
                    var dialogElement = dialog.getElement();
                    
                    // Load blocks data asynchronously
                    CKEDITOR.plugins.blockTemplate.loadBlocksData()
                        .then(function(blocksData) {
                            // Build enhanced dialog HTML
                            var html = buildEnhancedDialogHtml(blocksData);
                            dialogElement.findOne('#block-loading').setHtml(html);
                            
                            // Setup event handlers after content is loaded
                            setupDialogEventHandlers(dialog, blocksData);
                        })
                        .catch(function(error) {
                            CKEDITOR.plugins.blockTemplate.showError(
                                'Không thể tải dữ liệu blocks. Vui lòng thử lại.',
                                error
                            );
                            
                            // Show fallback content
                            dialogElement.findOne('#block-loading').setHtml(
                                '<div class="error-message">Không thể tải blocks. ' +
                                '<a href="' + pluginDirectory + 'admin.php" target="_blank">Quản lý Block Template</a></div>'
                            );
                        });
                },
                
                onHide: function() {
                    // Cleanup event handlers to prevent memory leaks
                    var dialogElement = this.getElement();
                    if (dialogElement) {
                        dialogElement.removeAllListeners();
                    }
                }
            };
            
            return dialogDefinition;
        }
        
        // Build enhanced dialog HTML with preview pane
        function buildEnhancedDialogHtml(blocksData) {
            var html = '<div class="block-template-dialog">';
            
            // View tabs
            html += '<div class="block-view-tabs">';
            html += '<div class="block-view-tab active" data-view="all">Tất cả</div>';
            html += '<div class="block-view-tab" data-view="favorites">Yêu thích</div>';
            html += '<div class="block-view-tab" data-view="recent">Gần đây</div>';
            html += '</div>';
            
            // Search bar
            html += '<div class="block-search">';
            html += '<input type="text" placeholder="Tìm kiếm block theo tên, mô tả hoặc nội dung..." id="block-search-input">';
            html += '</div>';
            
            // Category filters
            html += '<div class="category-filters">';
            html += '<span class="category-filter active" data-category="all">Tất cả</span>';
            
            if (blocksData.categories) {
                blocksData.categories.forEach(function(category) {
                    html += '<span class="category-filter" data-category="' + category.id + '">' + 
                           escapeHtml(category.name) + '</span>';
                });
            }
            
            html += '</div>';
            
            // Layout container with preview
            html += '<div class="block-layout-container">';
            
            // Block list container
            html += '<div class="block-list-container">';
            html += '<div class="block-list" id="block-list">';
            html += buildBlocksList(blocksData.blocks || []);
            html += '</div>';
            html += '</div>';
            
            // Preview container
            html += '<div class="block-preview-container">';
            html += '<h3>Xem trước</h3>';
            html += '<div class="block-preview-area" id="block-preview-area">';
            html += '<p class="preview-placeholder">Chọn một block để xem trước</p>';
            html += '</div>';
            html += '<div class="preview-actions">';
            html += '<button type="button" class="preview-btn-insert" id="preview-insert-btn" disabled>Chèn Block</button>';
            html += '</div>';
            html += '</div>';
            
            html += '</div>'; // end layout container
            
            // Admin link
            html += '<div class="admin-link">';
            html += '<a href="' + pluginDirectory + 'admin.php" target="_blank">Quản lý Block Template</a>';
            html += '</div>';
            
            html += '</div>';
            
            return html;
        }
        
        // Build blocks list HTML
        function buildBlocksList(blocks) {
            if (!blocks || blocks.length === 0) {
                return '<div class="no-blocks">Chưa có block nào. <a href="' + pluginDirectory + 
                       'admin.php" target="_blank">Thêm block mới</a></div>';
            }
            
            var html = '';
            blocks.forEach(function(block) {
                var isFavorite = CKEDITOR.plugins.blockTemplate.favorites.indexOf(block.id) > -1;
                var isRecent = CKEDITOR.plugins.blockTemplate.recentBlocks.indexOf(block.id) > -1;
                
                html += '<div class="block-item" data-block-id="' + escapeHtml(block.id) + 
                       '" data-category="' + escapeHtml(block.category || '') + '">';
                
                // Thumbnail
                html += '<div class="block-thumbnail">';
                if (block.thumbnail) {
                    html += '<img src="' + pluginDirectory + escapeHtml(block.thumbnail) + 
                           '" alt="' + escapeHtml(block.name || '') + '" loading="lazy">';
                } else {
                    html += '<div class="thumbnail-placeholder">No Image</div>';
                }
                html += '</div>';
                
                // Favorite button
                html += '<button class="block-favorite ' + (isFavorite ? 'active' : '') + 
                       '" data-block-id="' + escapeHtml(block.id) + '" title="' + 
                       (isFavorite ? 'Bỏ yêu thích' : 'Thêm vào yêu thích') + '">★</button>';
                
                // Block info
                html += '<div class="block-info">';
                html += '<div class="block-name">' + escapeHtml(block.name || '') + '</div>';
                html += '<div class="block-description">' + escapeHtml(block.description || '') + '</div>';
                
                // Tags
                if (isRecent) {
                    html += '<span class="block-tag recent">Gần đây</span>';
                }
                if (isFavorite) {
                    html += '<span class="block-tag favorite">Yêu thích</span>';
                }
                
                html += '</div>';
                html += '</div>';
            });
            
            return html;
        }
        
        // Setup event handlers for dialog
        function setupDialogEventHandlers(dialog, blocksData) {
            var dialogElement = dialog.getElement();
            var selectedBlock = null;
            
            // Block item click handlers
            var blockItems = dialogElement.find('.block-item');
            for (var i = 0; i < blockItems.count(); i++) {
                (function(item) {
                    item.on('click', function(event) {
                        // Prevent event bubbling from favorite button
                        if (event.data.getTarget().hasClass('block-favorite')) {
                            return;
                        }
                        
                        var blockId = this.getAttribute('data-block-id');
                        selectedBlock = findBlockById(blockId, blocksData.blocks);
                        
                        if (selectedBlock) {
                            // Update UI - remove selected class from all items
                            var allItems = dialogElement.find('.block-item');
                            for (var k = 0; k < allItems.count(); k++) {
                                allItems.getItem(k).removeClass('selected');
                            }
                            this.addClass('selected');
                            
                            // Update preview
                            updatePreview(dialogElement, selectedBlock);
                            
                            // Enable insert button
                            var insertBtn = dialogElement.findOne('#preview-insert-btn');
                            if (insertBtn) {
                                insertBtn.removeAttribute('disabled');
                            }
                        }
                    });
                })(blockItems.getItem(i));
            }
            
            // Favorite button handlers
            var favoriteButtons = dialogElement.find('.block-favorite');
            for (var j = 0; j < favoriteButtons.count(); j++) {
                (function(btn) {
                    btn.on('click', function(event) {
                        event.data.preventDefault();
                        event.data.stopPropagation();
                        
                        var blockId = this.getAttribute('data-block-id');
                        var isFavorite = CKEDITOR.plugins.blockTemplate.toggleFavorite(blockId);
                        
                        // Update button state
                        if (isFavorite) {
                            this.addClass('active');
                            this.setAttribute('title', 'Bỏ yêu thích');
                        } else {
                            this.removeClass('active');
                            this.setAttribute('title', 'Thêm vào yêu thích');
                        }
                        
                        // Refresh view if in favorites tab
                        var activeTab = dialogElement.findOne('.block-view-tab.active');
                        if (activeTab && activeTab.getAttribute('data-view') === 'favorites') {
                            filterBlocksByView(dialogElement, 'favorites', blocksData.blocks);
                        }
                    });
                })(favoriteButtons.getItem(j));
            }
            
            // View tab handlers
            var viewTabs = dialogElement.find('.block-view-tab');
            for (var k = 0; k < viewTabs.count(); k++) {
                (function(tab) {
                    tab.on('click', function() {
                        var view = this.getAttribute('data-view');
                        
                        // Update active tab - remove active class from all tabs
                        var allTabs = dialogElement.find('.block-view-tab');
                        for (var m = 0; m < allTabs.count(); m++) {
                            allTabs.getItem(m).removeClass('active');
                        }
                        this.addClass('active');
                        
                        // Filter blocks
                        filterBlocksByView(dialogElement, view, blocksData.blocks);
                    });
                })(viewTabs.getItem(k));
            }
            
            // Category filter handlers
            var categoryFilters = dialogElement.find('.category-filter');
            for (var l = 0; l < categoryFilters.count(); l++) {
                (function(filter) {
                    filter.on('click', function() {
                        var categoryId = this.getAttribute('data-category');
                        
                        // Update active filter - remove active class from all filters
                        var allFilters = dialogElement.find('.category-filter');
                        for (var n = 0; n < allFilters.count(); n++) {
                            allFilters.getItem(n).removeClass('active');
                        }
                        this.addClass('active');
                        
                        // Filter blocks
                        filterBlocksByCategory(dialogElement, categoryId);
                    });
                })(categoryFilters.getItem(l));
            }
            
            // Search handler
            var searchInput = dialogElement.findOne('#block-search-input');
            if (searchInput) {
                searchInput.on('keyup', function() {
                    var searchTerm = this.getValue().toLowerCase();
                    advancedSearch(dialogElement, searchTerm, blocksData.blocks);
                });
            }
            
            // Insert button handler
            var insertBtn = dialogElement.findOne('#preview-insert-btn');
            if (insertBtn) {
                insertBtn.on('click', function() {
                    if (selectedBlock) {
                        // Process template variables if any
                        var processedHtml = CKEDITOR.plugins.blockTemplate.processTemplate(
                            selectedBlock.code_html, 
                            getTemplateVariables()
                        );
                        
                        // Insert content
                        insertBlockContent(editor, processedHtml);
                        
                        // Track usage
                        CKEDITOR.plugins.blockTemplate.trackUsage(selectedBlock.id);
                        CKEDITOR.plugins.blockTemplate.addToRecent(selectedBlock.id);
                        
                        // Close dialog
                        dialog.hide();
                    }
                });
            }
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
        
        // Enhanced helper functions
        
        // Find block by ID with error handling
        function findBlockById(id, blocks) {
            if (!blocks) {
                blocks = CKEDITOR.plugins.blockTemplate.blocksData.blocks || [];
            }
            
            for (var i = 0; i < blocks.length; i++) {
                if (blocks[i].id === id) {
                    return blocks[i];
                }
            }
            return null;
        }
        
        // HTML escape function for security
        function escapeHtml(text) {
            if (!text) return '';
            
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            
            return text.toString().replace(/[&<>"']/g, function(m) {
                return map[m];
            });
        }
        
        // Update preview pane
        function updatePreview(dialogElement, block) {
            var previewArea = dialogElement.findOne('#block-preview-area');
            if (!previewArea || !block) return;
            
            try {
                var previewHtml = '<div class="preview-header">';
                previewHtml += '<h4>' + escapeHtml(block.name) + '</h4>';
                previewHtml += '<p class="preview-description">' + escapeHtml(block.description) + '</p>';
                previewHtml += '</div>';
                
                previewHtml += '<div class="preview-content">';
                // Sanitize and limit preview content
                var content = block.code_html || '';
                if (content.length > 1000) {
                    content = content.substring(0, 1000) + '...';
                }
                previewHtml += content;
                previewHtml += '</div>';
                
                previewArea.setHtml(previewHtml);
            } catch (error) {
                console.warn('Block Template: Error updating preview', error);
                previewArea.setHtml('<p class="preview-error">Không thể hiển thị xem trước</p>');
            }
        }
        
        // Advanced search function
        function advancedSearch(dialogElement, searchTerm, blocks) {
            if (!blocks) {
                blocks = CKEDITOR.plugins.blockTemplate.blocksData.blocks || [];
            }
            
            var blockItems = dialogElement.find('.block-item');
            
            if (!searchTerm) {
                // Show all blocks if no search term
                for (var i = 0; i < blockItems.count(); i++) {
                    blockItems.getItem(i).setStyle('display', 'block');
                }
                return;
            }
            
            // Search in name, description, and content
            for (var j = 0; j < blockItems.count(); j++) {
                var item = blockItems.getItem(j);
                var blockId = item.getAttribute('data-block-id');
                var block = findBlockById(blockId, blocks);
                
                var shouldShow = false;
                
                if (block) {
                    var searchFields = [
                        block.name || '',
                        block.description || '',
                        block.code_html || ''
                    ].join(' ').toLowerCase();
                    
                    shouldShow = searchFields.indexOf(searchTerm) !== -1;
                }
                
                item.setStyle('display', shouldShow ? 'block' : 'none');
            }
        }
        
        // Filter blocks by view (all, favorites, recent)
        function filterBlocksByView(dialogElement, view, blocks) {
            if (!blocks) {
                blocks = CKEDITOR.plugins.blockTemplate.blocksData.blocks || [];
            }
            
            var blockItems = dialogElement.find('.block-item');
            var visibleBlocks = [];
            
            switch (view) {
                case 'favorites':
                    visibleBlocks = CKEDITOR.plugins.blockTemplate.favorites;
                    break;
                case 'recent':
                    visibleBlocks = CKEDITOR.plugins.blockTemplate.recentBlocks;
                    break;
                default:
                    // Show all blocks
                    for (var i = 0; i < blockItems.count(); i++) {
                        blockItems.getItem(i).setStyle('display', 'block');
                    }
                    return;
            }
            
            // Hide all blocks first
            for (var j = 0; j < blockItems.count(); j++) {
                blockItems.getItem(j).setStyle('display', 'none');
            }
            
            // Show only visible blocks
            visibleBlocks.forEach(function(blockId) {
                for (var k = 0; k < blockItems.count(); k++) {
                    var item = blockItems.getItem(k);
                    if (item.getAttribute('data-block-id') === blockId) {
                        item.setStyle('display', 'block');
                        break;
                    }
                }
            });
        }
        
        // Enhanced category filter
        function filterBlocksByCategory(dialogElement, categoryId) {
            var blockItems = dialogElement.find('.block-item');
            
            for (var i = 0; i < blockItems.count(); i++) {
                var item = blockItems.getItem(i);
                var itemCategory = item.getAttribute('data-category');
                
                var shouldShow = (categoryId === 'all') || (itemCategory === categoryId);
                item.setStyle('display', shouldShow ? 'block' : 'none');
            }
        }
        
        // Get template variables (for future extension)
        function getTemplateVariables() {
            // This can be extended to show a dialog for variable input
            // For now, return empty object
            return {};
        }
        
        // Enhanced insert function with error handling
        function insertBlockContent(editor, html) {
            try {
                if (!html) {
                    throw new Error('No content to insert');
                }
                
                // Insert HTML content
                editor.insertHtml(html);
                
                // Focus back to editor
                editor.focus();
                
            } catch (error) {
                console.error('Block Template: Error inserting content', error);
                CKEDITOR.plugins.blockTemplate.showError(
                    'Không thể chèn block. Vui lòng thử lại.',
                    error
                );
            }
        }
        
        // Đăng ký dialog
        CKEDITOR.dialog.add('blockTemplateDialog', function(editor) {
            return openDialog(editor);
        });
        
        // Thêm CSS cho dialog
        CKEDITOR.document.appendStyleSheet(pluginDirectory + 'styles.css');
    }
});
