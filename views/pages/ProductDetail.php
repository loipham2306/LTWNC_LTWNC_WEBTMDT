<?php
// 1. KẾT NỐI DATABASE
require_once '../../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// 2. LẤY ID SẢN PHẨM TỪ URL (?id=...)
$id_san_pham = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;

if ($id_san_pham > 0) {
    // Lấy thông tin chi tiết sản phẩm thật từ Database
    $sql = "SELECT sp.*, dm.ten_danh_muc 
            FROM san_pham sp 
            LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
            WHERE sp.id_san_pham = :id AND sp.trang_thai = 1 LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id_san_pham, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

// LẤY TẤT CẢ DANH MỤC TỪ DATABASE ĐỂ TỰ ĐỘNG VẼ SIDEBAR BÊN TRÁI
$sql_dm = "SELECT * FROM danh_muc";
$stmt_dm = $conn->prepare($sql_dm);
$stmt_dm->execute();
$danhMucs = $stmt_dm->fetchAll(PDO::FETCH_ASSOC);

// 3. XỬ LÝ DỮ LIỆU GIÁ VÀ ẢNH ĐỂ KHÔNG BỊ LỖI
$gia_hien_tai = 0;
$imgPath = '/LTWNC_LTWNC_WEBTMDT/assets/images/products/default.png';

if ($product) {
    $gia_hien_tai = isset($product['gia_ban']) ? $product['gia_ban'] : (isset($product['gia_co_ban']) ? $product['gia_co_ban'] : (isset($product['gia']) ? $product['gia'] : 0));
    
    $tenAnh = isset($product['hinh_anh']) ? $product['hinh_anh'] : '';
    if (!empty($tenAnh)) {
        $imgPath = '/LTWNC_LTWNC_WEBTMDT/assets/images/products/' . $tenAnh;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product ? htmlspecialchars($product['ten_san_pham']) : 'Sản phẩm không tồn tại' ?> - LuLoShop</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #111; color: #fff; font-family: 'Segoe UI', sans-serif; }
        .detail-wrapper { background-color: #1a1a1a; min-height: 80vh; }
        
        /* Sidebar Styling */
        .sidebar-box { background-color: #222; border: 1px solid #333; padding: 20px; border-radius: 12px; margin-bottom: 24px; }
        .form-control { background-color: #111 !important; border: 1px solid #444 !important; color: #fff !important; }
        
        .btn-sidebar-cat { 
            display: block; width: 100%; text-align: left; padding: 10px 15px; 
            background: transparent; border: none; color: #ccc; 
            border-radius: 6px; transition: all 0.3s ease; text-decoration: none; font-size: 0.95rem; 
        }
        .btn-sidebar-cat:hover { background-color: #2a2a2a; color: #F28B00; padding-left: 20px; }
        .btn-sidebar-cat.active { background-color: #F28B00; color: #fff !important; font-weight: bold; }

        .sidebar-sale-banner { background-color: #222; border: 1px solid #333; border-radius: 12px; padding: 20px; text-align: center; position: relative; overflow: hidden; }
        
        /* Product Info Styling */
        .main-img-box { background-color: #222; border: 1px solid #333; border-radius: 12px; height: 400px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .thumb-box { background-color: #222; border: 1px solid #333; border-radius: 8px; width: 75px; height: 75px; display: flex; align-items: center; justify-content: center; cursor: pointer; overflow: hidden; }
        .thumb-box.active { border-color: #F28B00; }
        
        .text-orange { color: #F28B00 !important; }
        .info-gray-box { background-color: #222; border-left: 3px solid #444; padding: 12px 20px; border-radius: 4px; color: #aaa; font-size: 0.9rem; }
        
        /* Quantity & Button */
        .qty-container { display: flex; align-items: center; background-color: #222; border: 1px solid #444; border-radius: 20px; width: fit-content; overflow: hidden; }
        .qty-btn { background: transparent; border: none; color: #fff; width: 35px; height: 35px; font-size: 1.1rem; }
        .qty-btn:hover { background-color: #333; color: #F28B00; }
        .qty-input { background: transparent; border: none; color: #fff; text-align: center; width: 45px; font-weight: bold; }
        
        .btn-orange-lg { background-color: #F28B00; color: #fff; font-weight: bold; border: none; border-radius: 25px; padding: 12px 40px; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s; }
        .btn-orange-lg:hover { background-color: #d67a00; color: #fff; transform: translateY(-2px); }
        
        /* Tabs Styling */
        .nav-tabs { border-bottom: 1px solid #333; }
        .nav-tabs .nav-link { color: #aaa; border: none; font-weight: bold; padding: 12px 20px; }
        .nav-tabs .nav-link.active { background: transparent; color: #F28B00; border-bottom: 2px solid #F28B00; }
    </style>
</head>
<body>

    <?php
    $pageTitle = "Chi Tiết Sản Phẩm";
    $pageBreadcrumb = "Chi Tiết";
    include '../components/Header.php';
    include '../components/PageHeader.php';
    ?>

    <div class="container-fluid detail-wrapper py-5">
        <div class="container py-4">
            
            <?php if (!$product): ?>
                <div class="text-center py-5">
                    <i class="fas fa-box-open fs-1 text-secondary mb-3"></i>
                    <h2 class="text-white">Sản phẩm này không tồn tại hoặc đã bị xóa!</h2>
                    <a href="Shop.php" class="btn btn-orange-lg mt-3">Quay Lại Cửa Hàng</a>
                </div>
            <?php else: ?>
                
                <div class="row g-4">
                    <div class="col-lg-3">
                        <div class="sidebar-box">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Tìm kiếm...">
                                <span class="input-group-text bg-transparent border-0 text-orange"><i class="fa fa-search"></i></span>
                            </div>
                        </div>

                        <div class="sidebar-box">
                            <h6 class="fw-bold text-orange text-uppercase mb-3">Danh Mục</h6>
                            
                            <a href="Shop.php" class="btn-sidebar-cat">
                                <i class="fas fa-th-large me-2"></i> Tất Cả Sản Phẩm
                            </a>

                            <?php 
                            // Lấy tên danh mục của sản phẩm hiện tại
                            $current_cat = isset($product['ten_danh_muc']) ? $product['ten_danh_muc'] : ''; 
                            
                            // Vòng lặp in ra toàn bộ danh mục từ database
                            if (!empty($danhMucs)) {
                                foreach ($danhMucs as $dm) {
                                    $catName = $dm['ten_danh_muc'];
                                    // Kiểm tra xem danh mục đang in ra có trùng với danh mục của sản phẩm không
                                    $isActive = ($current_cat === $catName) ? 'active' : '';
                                    
                                    echo '<a href="Shop.php" class="btn-sidebar-cat '.$isActive.'">
                                            <i class="fas fa-angle-right me-2 text-orange"></i> '.htmlspecialchars($catName).'
                                          </a>';
                                }
                            }
                            ?>
                        </div>
                        <div class="sidebar-sale-banner">
                            <h4 class="fw-bold text-white mb-1">SALE</h4>
                            <p class="text-orange fw-bold mb-3">Giảm Đến 50%</p>
                            <button class="btn btn-orange-lg py-2 px-4 fs-6">Mua Ngay</button>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="main-img-box p-3 shadow-sm">
                                    <img src="<?= $imgPath ?>" class="img-fluid" style="max-height: 100%; object-fit: contain;" alt="<?= htmlspecialchars($product['ten_san_pham']) ?>">
                                </div>
                                
                                <div class="d-flex gap-2 mt-3 justify-content-center">
                                    <div class="thumb-box active"><img src="<?= $imgPath ?>" class="img-fluid p-1" style="max-height: 100%;"></div>
                                    <div class="thumb-box"><img src="<?= $imgPath ?>" class="img-fluid p-1" style="max-height: 100%; filter: grayscale(50%);"></div>
                                    <div class="thumb-box"><img src="<?= $imgPath ?>" class="img-fluid p-1" style="max-height: 100%; filter: grayscale(50%);"></div>
                                    <div class="thumb-box"><img src="<?= $imgPath ?>" class="img-fluid p-1" style="max-height: 100%; filter: grayscale(50%);"></div>
                                </div>
                            </div>

                            <div class="col-md-6 ps-md-4">
                                <h2 class="text-white fw-bold mb-1"><?= htmlspecialchars($product['ten_san_pham']) ?></h2>
                                <p class="mb-3 text-muted">Danh mục: <span class="text-orange fw-bold"><?= htmlspecialchars($product['ten_danh_muc'] ?? 'Chưa phân loại') ?></span></p>
                                
                                <h3 class="text-orange fw-bold mb-3"><?= number_format($gia_hien_tai, 0, ',', '.') ?> VNĐ</h3>
                                
                                <div class="text-orange mb-4 small">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <span class="text-muted ms-2">(15 đánh giá)</span>
                                </div>

                                <div class="info-gray-box d-flex justify-content-between mb-4">
                                    <span>Mã SP: <strong>SKU-#<?= $product['id_san_pham'] ?></strong></span>
                                    <span>Tình trạng: <strong class="text-success">Còn <?= isset($product['so_luong_kho']) ? $product['so_luong_kho'] : 10 ?> sản phẩm</strong></span>
                                </div>

                                <p class="text-white-50 mb-4" style="line-height: 1.7; font-size: 0.95rem;">
                                    <?= !empty($product['mo_ta']) ? nl2br(htmlspecialchars($product['mo_ta'])) : 'Sản phẩm chính hãng chất lượng cao phân phối trực tiếp tại Trạm Hiệu. Thiết kế hiện đại, bền bỉ mang đến trải nghiệm sử dụng hoàn hảo.' ?>
                                </p>

                                <div class="d-flex align-items-center gap-4 mt-4 pt-2">
                                    <div class="qty-container">
                                        <button class="qty-btn" onclick="updateQty(-1)">-</button>
                                        <input type="text" id="buyQty" class="qty-input" value="1" readonly>
                                        <button class="qty-btn" onclick="updateQty(1)">+</button>
                                    </div>
                                    
                                    <button onclick="addToCartDetail()" class="btn btn-orange-lg shadow">
                                        <i class="fas fa-shopping-bag me-2"></i> Thêm Vào Giỏ Hàng
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-4">
                            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-content" type="button" role="tab">Mô Tả Sản Phẩm</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review-content" type="button" role="tab">Đánh Giá (2)</button>
                                </li>
                            </ul>
                            <div class="tab-content p-4" style="background-color: #151515; border: 1px solid #222; border-top: none; border-radius: 0 0 8px 8px;">
                                <div class="tab-pane fade show active text-white-50" id="desc-content" role="tabpanel" style="line-height: 1.8;">
                                    <?= !empty($product['mo_ta']) ? nl2br(htmlspecialchars($product['mo_ta'])) : 'Chưa có thông tin mô tả mở rộng cho dòng sản phẩm này.' ?>
                                </div>
                                <div class="tab-pane fade text-white-50" id="review-content" role="tabpanel">
                                    <p>⭐️ <strong>Nguyễn Văn A:</strong> Giao hàng cực nhanh, hàng đóng gói cẩn thận y như hình chụp!</p>
                                    <p>⭐️ <strong>Trần Thị B:</strong> Chất lượng tuyệt vời vượt tầm giá, sẽ tiếp tục ủng hộ LuLoShop dài dài.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
            <?php endif; ?>
        </div>
    </div>

    <?php include '../components/Footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateQty(change) {
            let qtyInput = document.getElementById('buyQty');
            let currentVal = parseInt(qtyInput.value);
            let newVal = currentVal + change;
            if (newVal >= 1) qtyInput.value = newVal;
        }

        function addToCartDetail() {
            <?php if ($product): ?>
                const product = {
                    id: <?= $product['id_san_pham'] ?>,
                    name: "<?= addslashes($product['ten_san_pham']) ?>",
                    price: <?= $gia_hien_tai ?>,
                    img: "<?= $imgPath ?>",
                    category: "<?= addslashes($product['ten_danh_muc'] ?? 'Khác') ?>"
                };

                const qty = parseInt(document.getElementById('buyQty').value);
                let currentCart = JSON.parse(localStorage.getItem('cart')) || [];
                let existingItemIndex = currentCart.findIndex(item => item.id === product.id);

                if (existingItemIndex !== -1) {
                    currentCart[existingItemIndex].quantity += qty;
                } else {
                    product.quantity = qty;
                    product.selected = true;
                    currentCart.push(product);
                }

                localStorage.setItem('cart', JSON.stringify(currentCart));
                if (typeof updateCartBadge === 'function') updateCartBadge();
                alert(`Thành công! Đã thêm ${qty} sản phẩm "${product.name}" vào giỏ hàng.`);
            <?php endif; ?>
        }
    </script>
</body>
</html>