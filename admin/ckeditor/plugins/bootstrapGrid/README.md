# Bootstrap Grid Plugin cho CKEditor - Phiên bản cải tiến

Plugin Bootstrap Grid cho phép chèn layout grid responsive Bootstrap vào CKEditor với nhiều tính năng nâng cao.

## 🚀 Tính năng mới

### ✅ Đã cải tiến từ phiên bản gốc:

1. **Responsive Design đầy đủ**
   - Hỗ trợ tất cả breakpoints: XS, SM, MD, LG, XL
   - Thiết kế layout khác nhau cho từng màn hình
   - Preview responsive real-time

2. **Advanced Grid Features**
   - Offset columns cho mọi breakpoint
   - Vertical và horizontal alignment
   - Custom gutters (no-gutters)
   - Template system với preset layouts

3. **Template System**
   - Hero Section template
   - Three Column Cards
   - Two Column Content
   - Content + Sidebar layout
   - Custom content cho từng template

4. **Improved User Experience**
   - Giao diện 3 tabs: Cơ bản, Responsive, Nâng cao
   - Preview trực quan với thông tin chi tiết
   - Validation input tốt hơn
   - Breakpoint switcher

5. **Code Quality**
   - Namespace functions trong CKEDITOR.plugins.bootstrapGrid
   - Better error handling
   - Cleaner HTML output
   - Support Bootstrap 4 & 5

## 📋 Hướng dẫn sử dụng

### 1. Tab "Bố cục cơ bản"
- **Template có sẵn**: Chọn template preset hoặc tùy chỉnh
- **Chọn bố cục**: Layout preset phổ biến
- **Bố cục tùy chỉnh**: Nhập layout theo ý muốn (ví dụ: "3,6,3")
- **Preview**: Xem trước layout real-time

### 2. Tab "Responsive"
- **Breakpoint buttons**: Click XS, SM, MD, LG, XL để chuyển đổi
- **Layout cho breakpoint**: Thiết kế riêng cho từng màn hình
- **Offset**: Thêm margin trái cho các cột

### 3. Tab "Nâng cao"
- **Container**: Bọc grid trong container Bootstrap
- **Gutters**: Bật/tắt khoảng cách giữa các cột
- **Căn chỉnh ngang**: justify-content options
- **Căn chỉnh dọc**: align-items options

## 🎯 Ví dụ sử dụng

### Layout cơ bản
```html
<div class="container">
    <div class="row">
        <div class="col-md-6">Cột 1</div>
        <div class="col-md-6">Cột 2</div>
    </div>
</div>
```

### Layout responsive với offset
```html
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-4 offset-md-2">Nội dung</div>
        <div class="col-xs-12 col-md-4">Sidebar</div>
    </div>
</div>
```

### Layout với alignment
```html
<div class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-8">Nội dung căn giữa</div>
    </div>
</div>
```

## 🔧 Templates có sẵn

### 1. Hero Section
- Layout: 1 cột full width
- Content: Jumbotron với title, description và CTA button

### 2. Three Column Cards
- Layout: 3 cột bằng nhau (4-4-4)
- Content: Bootstrap cards với title, text và button

### 3. Two Column Content
- Layout: 2 cột bằng nhau (6-6)
- Content: Tiêu đề và đoạn văn đơn giản

### 4. Content + Sidebar
- Layout: Nội dung chính + sidebar (8-4)
- Content: Article + navigation sidebar

## 🛠️ Cấu hình nâng cao

### Breakpoints Bootstrap
```javascript
xs: 0px - 575px    (Mobile phones)
sm: 576px - 767px  (Large phones)
md: 768px - 991px  (Tablets)
lg: 992px - 1199px (Small laptops)
xl: 1200px+        (Large laptops/desktops)
```

### CSS Classes được sử dụng
- `col-{breakpoint}-{size}`: Kích thước cột
- `offset-{breakpoint}-{size}`: Offset cột
- `justify-content-{alignment}`: Căn chỉnh ngang
- `align-items-{alignment}`: Căn chỉnh dọc
- `no-gutters`: Loại bỏ gutters

## 🐛 Troubleshooting

### Vấn đề thường gặp:

1. **Layout không responsive**
   - Kiểm tra đã thiết lập cho tất cả breakpoint cần thiết
   - Đảm bảo tổng cột không vượt quá 12

2. **Preview không hiển thị**
   - Kiểm tra file CSS đã được load
   - Xóa cache browser và reload

3. **HTML output không đúng**
   - Kiểm tra validation input
   - Đảm bảo template được chọn đúng

### CSS Conflicts
Nếu có xung đột CSS, thêm `!important` vào styles hoặc increase specificity:

```css
.cke_dialog .grid-preview {
    /* Your custom styles */
}
```

## 📚 API Reference

### CKEDITOR.plugins.bootstrapGrid Methods

```javascript
// Update preview
CKEDITOR.plugins.bootstrapGrid.updatePreview(config, editorId);

// Validate layout
CKEDITOR.plugins.bootstrapGrid.validateLayout(layout, breakpoint);

// Generate HTML
CKEDITOR.plugins.bootstrapGrid.generateGridHTML(config);
```

### Config Object Structure
```javascript
{
    layout: { 
        xs: [12], 
        sm: [6,6], 
        md: [4,4,4], 
        lg: [3,3,3,3], 
        xl: [2,2,2,2,2,2] 
    },
    offsets: { 
        md: {0: 2, 1: 0} 
    },
    alignment: { 
        horizontal: 'center', 
        vertical: 'middle' 
    },
    useContainer: true,
    useGutters: true,
    template: 'hero'
}
```

## 🔄 Changelog

### Version 2.0 (Current)
- ✅ Full responsive breakpoint support
- ✅ Template system
- ✅ Advanced alignment options
- ✅ Offset support
- ✅ Improved UI/UX
- ✅ Better code structure

### Version 1.0 (Original)
- Basic grid layout
- MD breakpoint only
- Simple preview
- Manual layout input

## 📄 License

MIT License - Sử dụng tự do cho mục đích cá nhân và thương mại.

## 🤝 Đóng góp

Để đóng góp cho plugin:
1. Fork repository
2. Tạo feature branch
3. Commit changes
4. Push và tạo Pull Request

## 📞 Hỗ trợ

Nếu gặp vấn đề, vui lòng:
1. Kiểm tra documentation này
2. Tìm trong Issues
3. Tạo Issue mới với thông tin chi tiết

---

**Phát triển bởi**: Team Dev  
**Phiên bản**: 2.0  
**Tương thích**: CKEditor 4.x, Bootstrap 4.x/5.x
