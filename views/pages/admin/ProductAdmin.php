<?php
session_start();
ob_start(); 

// NHÚNG DATABASE
require_once '../../../config/database.php';
$database = new Database();
$db = $database->getConnection();

// 1. LẤY DANH SÁCH DANH MỤC VÀ THƯƠNG HIỆU CHO THẺ <SELECT>
$categories = [];
$brands = [];
try {
    $catStmt = $db->query("SELECT id_danh_muc, ten_danh_muc FROM danh_muc WHERE trang_thai = 1");
    $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

    $brandStmt = $db->query("SELECT id_thuong_hieu, ten_thuong_hieu FROM thuong_hieu WHERE trang_thai = 1");
    $brands = $brandStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Lỗi lấy dữ liệu danh mục/thương hiệu: " . $e->getMessage();
}

// 2. LOGIC PHÂN TRANG (PAGINATION)
$limit = 5; // Số sản phẩm trên mỗi trang
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalProducts = 0;
$totalPages = 1;
$products = [];

try {
    // Lấy tổng số lượng sản phẩm để tính số trang
    $totalStmt = $db->query("SELECT COUNT(*) FROM san_pham");
    $totalProducts = $totalStmt->fetchColumn();
    $totalPages = ceil($totalProducts / $limit);

    // Lấy dữ liệu sản phẩm (JOIN với bảng Danh mục và Thương hiệu để lấy tên hiển thị)
    $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
              FROM san_pham sp 
              LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
              LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id_thuong_hieu 
              ORDER BY sp.id_san_pham DESC 
              LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    // Nếu bảng san_pham chưa có, ta dùng mảng rỗng để không bị lỗi giao diện
    $products = []; 
}

$IMAGE_BASE_URL = '/LTWNC_BAN_HANG/backend/assets/images/products/';
?>

<div class="container-fluid p-4" style="background-color: #111; min-height: 100vh; color: #fff;">
    <h3 class="text-white fw-bold mb-4">Quản Lý Danh Mục Sản Phẩm</h3>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success fw-bold border-0 shadow-sm" style="background-color: #064e3b; color: #34d399;">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger fw-bold border-0 shadow-sm" style="background-color: #7f1d1d; color: #f87171;">
            <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card border-0 rounded p-4" style="background-color: #1a1a1a;">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div class="d-flex gap-2 flex-grow-1" style="max-width: 500px;">
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" onkeyup="filterProducts()" class="form-control bg-dark border-secondary text-white" placeholder="Tìm kiếm tên sản phẩm, mã SP...">
                </div>
                <select id="categoryFilter" onchange="filterProducts()" class="form-select bg-dark border-secondary text-white" style="max-width: 150px;">
                    <option value="">Tất cả</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['ten_danh_muc']) ?>"><?= htmlspecialchars($cat['ten_danh_muc']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button onclick="openAddModal()" class="btn fw-bold text-white shadow-sm" style="background-color: #F28B00;">
                <i class="fas fa-plus me-2"></i> Thêm Sản Phẩm
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                    <tr style="border-bottom: 2px solid #F28B00;">
                        <th scope="col" class="py-3 px-3">Mã SP</th>
                        <th scope="col" class="py-3 text-center">Hình Ảnh</th>
                        <th scope="col" class="py-3">Tên Sản Phẩm</th>
                        <th scope="col" class="py-3 text-center">Danh Mục</th>
                        <th scope="col" class="py-3 text-end">Giá Bán</th>
                        <th scope="col" class="py-3 text-center">Kho</th>
                        <th scope="col" class="py-3 text-center">Trạng Thái</th>
                        <th scope="col" class="py-3 text-center">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $product): ?>
                            <tr class="product-row" style="border-bottom: 1px solid #333;">
                                <td class="fw-bold text-muted py-3 px-3 product-id">#<?= $product['id_san_pham'] ?></td>
                                <td class="py-3 text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-dark rounded border border-secondary" style="width: 50px; height: 50px; overflow: hidden;">
                                        <?php if (!empty($product['hinh_anh'])): ?>
                                            <img src="<?= $IMAGE_BASE_URL . $product['hinh_anh'] ?>" alt="IMG" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <i class="fas fa-box fs-4" style="color: #F28B00;"></i>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-white fw-bold py-3 product-name"><?= htmlspecialchars($product['ten_san_pham']) ?></td>
                                <td class="text-muted py-3 text-center product-category"><?= htmlspecialchars($product['ten_danh_muc']) ?></td>
                                <td class="text-primary fw-bold py-3 text-end"><?= number_format($product['gia'], 0, ',', '.') ?> đ</td>
                                <td class="text-white fw-bold py-3 text-center"><?= $product['so_luong_kho'] ?? 0 ?></td>
                                <td class="py-3 text-center">
                                    <span class="badge rounded-pill px-3 py-2 <?= ($product['trang_thai'] ?? 1) == 1 ? 'bg-success' : 'bg-danger' ?>">
                                        <?= ($product['trang_thai'] ?? 1) == 1 ? 'Đang Bán' : 'Ngừng Bán' ?>
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button onclick='openEditModal(<?= htmlspecialchars(json_encode($product)) ?>)' class="btn btn-sm btn-outline-info" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="../../../controllers/SanPhamController.php" method="POST" class="d-inline" onsubmit="return confirm('Xóa sản phẩm này?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_san_pham" value="<?= $product['id_san_pham'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Chưa có sản phẩm nào trong kho.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 border-top border-secondary pt-4">
            <span class="text-muted small">
                Hiển thị <?= $totalProducts > 0 ? $offset + 1 : 0 ?> - <?= min($offset + $limit, $totalProducts) ?> trong tổng số <?= $totalProducts ?> sản phẩm
            </span>
            <nav>
                <ul class="pagination pagination-sm mb-0 d-flex flex-row gap-2">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a href="?page=<?= $page - 1 ?>" class="page-link bg-dark border-secondary <?= $page <= 1 ? 'text-muted' : 'text-white' ?> rounded">
                            <i class="fas fa-chevron-left"></i> Trước
                        </a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                            <a href="?page=<?= $i ?>" class="page-link border-0 rounded px-3" style="background-color: <?= $page == $i ? '#F28B00' : '#212529' ?>; color: #fff;">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a href="?page=<?= $page + 1 ?>" class="page-link bg-dark border-secondary <?= $page >= $totalPages ? 'text-muted' : 'text-white' ?> rounded">
                            Sau <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>

    <div id="productModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.85); z-index: 9999; display: none; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
        <div class="card border-secondary shadow-lg" style="width: 100%; max-width: 700px; background-color: #1a1a1a; border-radius: 15px; overflow: hidden;">
            
            <div class="p-3 d-flex justify-content-between align-items-center" style="background-color: #222; border-bottom: 2px solid #F28B00;">
                <h5 class="text-white fw-bold mb-0" id="modalTitle">
                    <i class="fas fa-plus-circle me-2 text-warning"></i>THÊM SẢN PHẨM MỚI
                </h5>
                <button onclick="closeModal()" class="btn-close btn-close-white"></button>
            </div>

            <div class="p-4" style="max-height: 80vh; overflow-y: auto;">
                <form action="../../../controllers/SanPhamController.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" id="formAction" value="add">
                    <input type="hidden" name="id_san_pham" id="prodId">

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-muted fw-bold small">TÊN SẢN PHẨM</label>
                            <input type="text" name="ten_san_pham" id="prodName" class="form-control bg-dark border-secondary text-white py-2" placeholder="Ví dụ: Laptop Gaming ASUS ROG..." required />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted fw-bold small">DANH MỤC</label>
                            <select name="id_danh_muc" id="prodCat" class="form-select bg-dark border-secondary text-white" required>
                                <option value="">-- Chọn Danh Mục --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id_danh_muc'] ?>"><?= htmlspecialchars($cat['ten_danh_muc']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-bold small">GIÁ BÁN (VNĐ)</label>
                            <input type="number" name="gia" id="prodPrice" class="form-control bg-dark border-secondary text-white" placeholder="0" required />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted fw-bold small">SỐ LƯỢNG KHO</label>
                            <input type="number" name="so_luong_kho" id="prodStock" class="form-control bg-dark border-secondary text-white" placeholder="0" required />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted fw-bold small">THƯƠNG HIỆU</label>
                            <select name="id_thuong_hieu" id="prodBrand" class="form-select bg-dark border-secondary text-white">
                                <option value="">-- Chọn Thương Hiệu --</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= $brand['id_thuong_hieu'] ?>"><?= htmlspecialchars($brand['ten_thuong_hieu']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted fw-bold small">MÔ TẢ CHI TIẾT</label>
                            <textarea name="mo_ta" id="prodDesc" class="form-control bg-dark border-secondary text-white" rows="4" placeholder="Nhập đặc điểm nổi bật..."></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-bold small">TRẠNG THÁI</label>
                            <select name="trang_thai" id="prodStatus" class="form-select bg-dark border-secondary text-white">
                                <option value="1">Đang Bán</option>
                                <option value="0">Ngừng Bán</option>
                            </select>
                        </div>

                        <div class="col-12 mt-3">
                            <label class="form-label text-muted fw-bold small">TẢI LÊN HÌNH ẢNH SP</label>
                            <input type="file" name="hinh_anh" class="form-control bg-dark border-secondary text-white" accept="image/*">
                            <small class="text-secondary mt-1 d-block">Lưu ý: Nếu sửa SP mà không chọn ảnh mới, hệ thống sẽ giữ nguyên ảnh cũ.</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary">
                        <button type="button" onclick="closeModal()" class="btn btn-outline-secondary px-4 fw-bold rounded-pill">HỦY</button>
                        <button type="submit" class="btn px-4 fw-bold text-white rounded-pill" style="background-color: #F28B00;">LƯU SẢN PHẨM</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    function openAddModal() {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle me-2 text-warning"></i> THÊM SẢN PHẨM MỚI';
        document.getElementById('formAction').value = 'add';
        document.getElementById('prodId').value = '';
        document.getElementById('prodName').value = '';
        document.getElementById('prodCat').value = '';
        document.getElementById('prodPrice').value = '';
        document.getElementById('prodStock').value = '';
        document.getElementById('prodBrand').value = '';
        document.getElementById('prodDesc').value = '';
        document.getElementById('prodStatus').value = '1';
        
        document.getElementById('productModal').style.display = 'flex';
    }

    function openEditModal(product) {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2 text-info"></i> CẬP NHẬT SẢN PHẨM';
        document.getElementById('formAction').value = 'edit';
        
        document.getElementById('prodId').value = product.id_san_pham;
        document.getElementById('prodName').value = product.ten_san_pham;
        document.getElementById('prodCat').value = product.id_danh_muc;
        document.getElementById('prodPrice').value = product.gia;
        document.getElementById('prodStock').value = product.so_luong_kho || 0;
        document.getElementById('prodBrand').value = product.id_thuong_hieu;
        document.getElementById('prodDesc').value = product.mo_ta;
        document.getElementById('prodStatus').value = product.trang_thai;
        
        document.getElementById('productModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('productModal').style.display = 'none';
    }

    // Lọc bảng ngay trên Front-end
    function filterProducts() {
        let searchInput = document.getElementById('searchInput').value.toLowerCase();
        let catInput = document.getElementById('categoryFilter').value.toLowerCase();
        let rows = document.getElementsByClassName('product-row');
        
        for (let i = 0; i < rows.length; i++) {
            let id = rows[i].querySelector('.product-id').innerText.toLowerCase();
            let name = rows[i].querySelector('.product-name').innerText.toLowerCase();
            let cat = rows[i].querySelector('.product-category').innerText.toLowerCase();
            
            let matchText = name.includes(searchInput) || id.includes(searchInput);
            let matchCat = (catInput === "") || (cat === catInput);
            
            if (matchText && matchCat) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
</script>

<?php
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>