# Bootstrap Table Plugin for CKEditor

## 📋 Tổng quan

Plugin Bootstrap Table được thiết kế để hỗ trợ tốt hơn các thuộc tính table của Bootstrap trong CKEditor. Plugin cung cấp giao diện trực quan để áp dụng các classes Bootstrap cho bảng.

## ✨ Tính năng chính

### 🎨 **Bootstrap Table Classes**
- **table** - Kiểu bảng cơ bản Bootstrap
- **table-striped** - Hàng xen kẽ màu
- **table-bordered** - Viền bảng
- **table-hover** - Hiệu ứng hover
- **table-sm** - Bảng nhỏ gọn
- **table-dark** - Bảng tối
- **table-light** - Bảng sáng

### 📱 **Responsive Support**
- **table-responsive** - Tự động wrap bảng với `<div class="table-responsive">`
- Cuộn ngang trên thiết bị mobile
- Tự động quản lý wrapper div

### 🎯 **Contextual Classes**
Áp dụng màu contextual cho hàng hoặc ô:
- **table-primary** - Xanh chính
- **table-secondary** - Xám
- **table-success** - Xanh lá (thành công)
- **table-danger** - Đỏ (nguy hiểm)
- **table-warning** - Vàng (cảnh báo)
- **table-info** - Xanh nhạt (thông tin)
- **table-light** - Sáng
- **table-dark** - Tối

### 🔧 **Advanced Features**
- **Live Preview** - Xem trước thời gian thực trong dialog
- **Custom Classes** - Thêm CSS classes tùy chỉnh
- **Auto Table Class** - Tự động thêm class `table` khi tạo bảng mới
- **Context Menu** - Truy cập nhanh từ menu chuột phải
- **Toolbar Integration** - Button trên toolbar

## 🚀 Cách sử dụng

### 1. **Áp dụng Bootstrap Classes cho Bảng**
1. Chọn một bảng trong editor
2. Click nút **Bootstrap Table** trên toolbar hoặc
3. Chuột phải → chọn **Bootstrap Table Classes**
4. Chọn các classes mong muốn trong dialog
5. Xem preview thời gian thực
6. Click **OK** để áp dụng

### 2. **Tạo Responsive Table**
- Tick vào checkbox **table-responsive**
- Plugin sẽ tự động wrap bảng với `<div class="table-responsive">`
- Bảng sẽ có scrollbar ngang trên mobile

### 3. **Áp dụng Contextual Colors**
1. Đặt cursor vào hàng hoặc ô cần tô màu
2. Mở dialog Bootstrap Table
3. Chuyển sang tab **Row/Cell Classes**
4. Chọn màu contextual
5. Chọn áp dụng cho hàng hoặc ô
6. Click **OK**

### 4. **Custom Classes**
1. Chuyển sang tab **Advanced**
2. Nhập các CSS classes tùy chỉnh (phân cách bằng dấu cách)
3. Có thể chọn xóa tất cả classes hiện tại trước khi áp dụng

## 📁 Cấu trúc Plugin

```
admin/ckeditor/plugins/bootstrapTable/
├── plugin.js                  # Plugin chính
├── dialogs/
│   └── bootstrapTable.js      # Dialog interface
├── styles.css                 # Plugin styles
└── README.md                  # Tài liệu này
```

## 🔌 API Reference

### **editor.bootstrapTable**

Plugin cung cấp các utility functions:

```javascript
// Thêm class vào bảng
editor.bootstrapTable.addClass(table, 'table-striped');

// Xóa class khỏi bảng
editor.bootstrapTable.removeClass(table, 'table-striped');

// Kiểm tra bảng có class hay không
var hasClass = editor.bootstrapTable.hasClass(table, 'table-striped');

// Lấy bảng hiện tại từ selection
var table = editor.bootstrapTable.getCurrentTable();

// Tạo responsive wrapper
editor.bootstrapTable.makeResponsive(table);

// Xóa responsive wrapper
editor.bootstrapTable.removeResponsive(table);

// Áp dụng contextual class cho hàng
editor.bootstrapTable.applyRowClass(row, 'table-success');

// Áp dụng contextual class cho ô
editor.bootstrapTable.applyCellClass(cell, 'table-danger');
```

## 🎯 Ví dụ Output

### **Basic Table**
```html
<table class="table">
    <thead>
        <tr><th>Header 1</th><th>Header 2</th></tr>
    </thead>
    <tbody>
        <tr><td>Data 1</td><td>Data 2</td></tr>
    </tbody>
</table>
```

### **Enhanced Table**
```html
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr><th>Header 1</th><th>Header 2</th></tr>
    </thead>
    <tbody>
        <tr class="table-success"><td>Success row</td><td>Data</td></tr>
        <tr><td class="table-warning">Warning cell</td><td>Data</td></tr>
    </tbody>
</table>
```

### **Responsive Table**
```html
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr><th>Col 1</th><th>Col 2</th><th>Col 3</th><th>Col 4</th></tr>
        </thead>
        <tbody>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td><td>Data 4</td></tr>
        </tbody>
    </table>
</div>
```

## ⚙️ Configuration

Plugin được cấu hình trong `config.js`:

```javascript
config.extraPlugins = 'bootstrapTable';

// Plugin yêu cầu Bootstrap CSS
config.contentsCss = [
    'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css'
];
```

## 🔧 Customization

### **Thêm Bootstrap Classes mới**

Chỉnh sửa `plugin.js`:

```javascript
var bootstrapTableClasses = {
    'table': 'Bảng cơ bản',
    'table-striped': 'Hàng xen kẽ',
    'your-custom-class': 'Your Description' // Thêm class mới
};
```

### **Tùy chỉnh Contextual Colors**

```javascript
var contextualClasses = {
    'table-primary': 'Primary',
    'your-custom-color': 'Your Color' // Thêm màu mới
};
```

## 🐛 Troubleshooting

### **Plugin không hiển thị**
- Kiểm tra `config.extraPlugins` có chứa `'bootstrapTable'`
- Đảm bảo files plugin tồn tại trong đúng thư mục

### **Styles không hoạt động**
- Kiểm tra Bootstrap CSS đã được load
- Đảm bảo `config.contentsCss` có Bootstrap CSS

### **Dialog lỗi**
- Kiểm tra file `dialogs/bootstrapTable.js` tồn tại
- Kiểm tra JavaScript console để xem lỗi chi tiết

## 📈 Performance

- **Lightweight**: Plugin chỉ khoảng 15KB
- **Lazy Loading**: Dialog chỉ load khi cần thiết
- **Memory Efficient**: Proper cleanup khi đóng dialog
- **Fast Rendering**: Optimized DOM manipulation

## 🤝 Tương thích

- **CKEditor**: 4.x
- **Bootstrap**: 4.x, 5.x
- **Browsers**: Modern browsers (IE11+)
- **Dependencies**: table, tabletools, contextmenu plugins

## 📊 Browser Support

| Browser | Version | Status |
|---------|---------|--------|
| Chrome | 70+ | ✅ Full |
| Firefox | 65+ | ✅ Full |
| Safari | 12+ | ✅ Full |
| Edge | 79+ | ✅ Full |
| IE | 11 | ⚠️ Limited |

## 🔮 Roadmap

### **Version 1.1**
- [ ] Thêm Bootstrap 5 breakpoint classes
- [ ] Support cho table-responsive-{breakpoint}
- [ ] Batch operations cho multiple tables

### **Version 1.2**
- [ ] Visual table designer
- [ ] Template presets
- [ ] Export/Import table configs

## 📝 Changelog

### **Version 1.0.0** (Current)
- ✅ Basic Bootstrap table classes support
- ✅ Contextual row/cell colors
- ✅ Responsive wrapper management
- ✅ Live preview dialog
- ✅ Custom classes support
- ✅ Context menu integration
- ✅ Auto table class detection

## 👥 Credits

Plugin được phát triển để cải thiện trải nghiệm làm việc với Bootstrap tables trong CKEditor, tích hợp tốt với workflow Bootstrap development.

---

**📧 Support**: Nếu gặp vấn đề, vui lòng tạo issue với thông tin chi tiết về môi trường và bước tái hiện lỗi.
