<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa Hàng - LuLoShop</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel="stylesheet">

    <style>
        body { background-color: #111; color: #fff; font-family: 'Segoe UI', sans-serif; }
        .shop-wrapper { background-color: #1a1a1a; min-height: 80vh; }
        
        /* Sidebar lọc dữ liệu */
        .sidebar-box { background-color: #222; border: 1px solid #333; padding: 25px; border-radius: 12px; }
        
        /* Định dạng Form input & Ô tìm kiếm tối màu */
        .form-control { background-color: #111 !important; border: 1px solid #444 !important; color: #fff !important; }
        .form-control:focus { border-color: #F28B00 !important; box-shadow: 0 0 0 0.25rem rgba(242, 139, 0, 0.25) !important; }
        .form-control::placeholder { color: #555 !important; }
        .input-group-text { background-color: #333; border: 1px solid #444; color: #F28B00; }

        /* Nút chuyển danh mục kiểu eSports */
        .btn-category { 
            display: block; width: 100%; text-align: left; padding: 10px 15px; 
            background: transparent; border: none; color: #ccc; 
            border-radius: 6px; transition: all 0.3s ease; text-decoration: none;
        }
        .btn-category:hover { background-color: #2a2a2a; color: #F28B00; padding-left: 20px; }
        .btn-category.active { background-color: #F28B00; color: #fff !important; font-weight: bold; }

        .text-orange { color: #F28B00 !important; }
    </style>
</head>
<body>

    <?php
    // Thiết lập props truyền cho PageHeader cha
    $pageTitle = "Cửa Hàng Của Chúng Tôi";
    $pageBreadcrumb = "Cửa Hàng";

    // Gọi các thành phần giao diện dạng PascalCase
    include '../components/Header.php';
    include '../components/PageHeader.php';
    ?>

    <div class="mb-5">
        <?php include '../components/ServiceFeatures.php'; ?>
    </div>

    <div class="container-fluid shop-wrapper py-5">
        <div class="container py-4">
            <div class="row g-4">
                
                <div class="col-lg-3 wow fadeInLeft" data-wow-delay="0.1s">
                    <div class="sidebar-box">
                        
                        <h5 class="fw-bold text-white text-uppercase mb-3" style="font-size: 0.95rem; letter-spacing: 1px;">Tìm Kiếm</h5>
                        <div class="input-group mb-4">
                            <input type="text" id="searchInput" class="form-control p-3" placeholder="Nhập tên sản phẩm..." oninput="handleSearch(this)">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>

                        <h5 class="fw-bold text-white text-uppercase mb-3 mt-2" style="font-size: 0.95rem; letter-spacing: 1px;">Danh Mục</h5>
                        <div class="d-flex flex-column gap-1" id="categoryGrid">
                            </div>

                    </div>
                </div>

                <div class="col-lg-9 wow fadeInRight" data-wow-delay="0.2s">
                    <div class="row g-4 justify-content-start" id="productsContainer">
                        </div>
                </div>

            </div> 
        </div> 
    </div> 

    <?php include '../components/Footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    
    <script>
        new WOW().init();

        // MẢNG DỮ LIỆU SẢN PHẨM CHUẨN ĐƯỜNG DẪN /images/img/
        const products = [
            { id: 1, name: 'Smart Camera Pro Max', category: 'Điện Tử', slug: 'dien-tu', price: 3350000, oldPrice: 4110000, img: '/LTWNC_BAN_HANG/assets/images/img/product-4.png' },
            { id: 2, name: 'Apple iPad Mini G2356', category: 'Tablet', slug: 'tablet', price: 10500000, oldPrice: 12500000, img: '/LTWNC_BAN_HANG/assets/images/img/product-3.png' },
            { id: 3, name: 'Microphone Đa Hướng', category: 'Phụ Kiện', slug: 'phu-kien', price: 850000, oldPrice: 1000000, img: '/LTWNC_BAN_HANG/assets/images/img/product-5.png' },
            { id: 4, name: 'Tai nghe Bluetooth 5.0', category: 'Âm Thanh', slug: 'am-thanh', price: 1250000, oldPrice: 1500000, img: '/LTWNC_BAN_HANG/assets/images/img/product-6.png' }
        ];

        // ĐỊNH NGHĨA DANH MỤC KHỚP VỚI MẢNG SẢN PHẨM THỰC TẾ
        const categoryNames = {
            all: 'Tất Cả Sản Phẩm',
            'dien-tu': 'Thiết Bị Điện Tử',
            'tablet': 'Máy Tính Bảng',
            'phu-kien': 'Phụ Kiện Gaming',
            'am-thanh': 'Thiết Bị Âm Thanh'
        };

        // Quản lý trạng thái Lọc (Tương đương bộ đôi useState của React)
        let searchQuery = '';
        let selectedCategory = 'all';

        // Hàm khởi tạo vẽ danh sách nút bấm Danh mục ở sidebar
        function renderCategories() {
            const container = document.getElementById('categoryGrid');
            let html = '';
            
            Object.keys(categoryNames).forEach(key => {
                const isActive = selectedCategory === key ? 'active' : '';
                html += `
                    <button class="btn-category ${isActive}" onclick="changeCategory('${key}')">
                        <i class="fas fa-chevron-right me-2 small text-orange"></i> ${categoryNames[key]}
                    </button>
                `;
            });
            container.innerHTML = html;
        }

        // Hàm lọc và xuất bản ProductCard (Thay thế khối filteredProducts.map của React)
        function renderProducts() {
            const container = document.getElementById('productsContainer');
            
            // Xử lý logic lọc mảng y hệt hàm .filter() bên React
            const filtered = products.filter(p => {
                const matchCategory = (selectedCategory === 'all' || p.slug === selectedCategory);
                const matchSearch = p.name.toLowerCase().includes(searchQuery.toLowerCase());
                return matchCategory && matchSearch;
            });

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <h3 class="text-white-50">Không tìm thấy sản phẩm phù hợp 😅</h3>
                        <p class="text-muted small">Thử tìm kiếm với một từ khóa khác xem sao bạn nhé.</p>
                    </div>`;
                return;
            }

            // Vẽ chuỗi cấu trúc layout HTML cho sản phẩm
            let html = '';
            filtered.forEach(product => {
                const formattedPrice = product.price.toLocaleString('vi-VN') + ' đ';
                const formattedOldPrice = product.oldPrice ? product.oldPrice.toLocaleString('vi-VN') + ' đ' : '&nbsp;';
                
                // Chuẩn hóa chuỗi dữ liệu JSON gửi cho hàm giỏ hàng dùng chung
                const productJson = JSON.stringify(product).replace(/'/g, "&apos;");

                html += `
                    <div class="col-md-6 col-lg-6 col-xl-4 mb-2">
                        <div class="card h-100 product-card-anim" style="background-color: #222; border: 1px solid #333; transition: all 0.3s ease; border-radius: 12px; overflow: hidden;">
                            <div class="position-relative overflow-hidden" style="height: 220px; background-color: #111;">
                                <img src="${product.img}" class="img-fluid w-100 h-100" style="object-fit: cover;" alt="${product.name}">
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5); opacity: 0; transition: opacity 0.3s;" onmouseenter="this.style.opacity=1" onmouseleave="this.style.opacity=0">
                                    <a href="/LTWNC_BAN_HANG/views/pages/ProductDetail.php?id=${product.id}" class="btn rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #1a1a1a; color: #F28B00; border: 1px solid #F28B00;">
                                        <i class="fa fa-eye fs-5"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body text-center d-flex flex-column p-4">
                                <p class="text-white-50 small mb-1">${product.category}</p>
                                <a href="/LTWNC_BAN_HANG/views/pages/ProductDetail.php?id=${product.id}" class="card-title h6 fw-bold mb-2 text-white text-decoration-none d-block">${product.name}</a>
                                <div class="d-flex justify-content-center align-items-center mb-3">
                                    <del class="me-2 text-white-50 small">${formattedOldPrice}</del>
                                    <span style="color: #F28B00;" class="fs-5 fw-bold">${formattedPrice}</span>
                                </div>
                                <button onclick='handleAddToCartFromShop(${productJson})' class="btn btn-orange w-100 rounded-pill py-2 fw-bold shadow-sm mt-auto">
                                    <i class="fas fa-cart-plus me-2"></i> Thêm Giỏ Hàng
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        // XỬ LÝ SỰ KIỆN KHI THAY ĐỔI DANH MỤC KHÁC
        function changeCategory(catKey) {
            selectedCategory = catKey;
            renderCategories(); // Vẽ lại nút active
            renderProducts();   // Lọc lại lưới sản phẩm tương ứng
        }

        // XỬ LÝ LIVE-SEARCH KHI GÕ PHÍM KÝ TỰ TỪ Ô INPUT
        function handleSearch(inputEl) {
            searchQuery = inputEl.value;
            renderProducts();
        }

        // HÀM BỎ GIỎ HÀNG ĐỒNG BỘ LOCALSTORAGE
        function handleAddToCartFromShop(product) {
            let currentCart = JSON.parse(localStorage.getItem('cart')) || [];
            let existingItemIndex = currentCart.findIndex(item => item.id === product.id);

            if (existingItemIndex !== -1) {
                currentCart[existingItemIndex].quantity += 1;
            } else {
                product.quantity = 1;
                product.selected = true;
                currentCart.push(product);
            }

            localStorage.setItem('cart', JSON.stringify(currentCart));
            if (typeof updateCartBadge === 'function') updateCartBadge();
            alert(`Thành công! Đã thêm sản phẩm ${product.name} vào giỏ hàng.`);
        }

        // Thực thi xuất bản dữ liệu lần đầu khi cấu trúc cây DOM sẵn sàng
        document.addEventListener('DOMContentLoaded', () => {
            renderCategories();
            renderProducts();
        });
    </script>
</body>
</html>