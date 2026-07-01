<?php
if (session_status() === PHP_SESSION_NONE) session_start();
ob_start(); 
$listKM = $listKM ?? [];
$listSanPham = $listSanPham ?? [];
 //echo "<pre>";
   // print_r($listSanPham);
    //die();
?>
<style>
    .btn-km {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;      /* giảm px-4 */
        font-weight: 600;
        border-radius: 999px;   /* pill đẹp */
        white-space: nowrap;    /* không xuống dòng */
        width: auto !important; /* bỏ w-100 */
    }
    .product-list{
        max-height:350px;
        overflow-y:auto;
        border:1px solid #444;
        border-radius:10px;
        background:#121212;
        padding:10px;
    }

    .product-item{
        background:#1f1f1f;
        border:1px solid #333;
        border-radius:10px;
        margin-bottom:10px;
        transition:.3s;
    }

    .product-item:hover{
        border-color:#f28b00;
    }

    .product-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        cursor:pointer;
        padding:12px 15px;
        font-weight:bold;
    }

    .product-header:hover{
        background:#2b2b2b;
    }

    .variant-list{
        display:none;
        border-top:1px solid #333;
        padding:10px 20px;
    }

    .variant-item{
        display:flex;
        align-items:center;
        gap:10px;
        padding:8px 0;
    }

    .variant-item:hover{
        color:#ffc107;
    }

    .search-box input{
        background:#111;
        color:#fff;
        border:1px solid #444;
        border-radius:8px;
    }
</style>
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-white mb-1">
                <i class="fas fa-tags text-warning me-2"></i>Quản Lý Khuyến Mãi
            </h4>
            <p class="text-muted small mb-0">Thiết lập các chương trình giảm giá cho sản phẩm</p>
        </div>
        <button class="btn btn-warning btn-km"
                data-bs-toggle="modal"
                data-bs-target="#kmModal"
                onclick="resetForm()">
            <i class="fas fa-plus me-2"></i>Tạo KM
        </button>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success fw-bold border-0 shadow-sm mb-4" style="background-color: #064e3b; color: #34d399;">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="card border-0 rounded p-3 p-md-4 shadow-sm" style="background-color: #1a1a1a;">
        <div class="table-responsive border border-secondary rounded">
            <table class="table table-dark table-hover mb-0 text-center" style="min-width: 900px;">
                <thead>
                    <tr style="border-bottom: 2px solid #F28B00;">
                        <th class="py-3 text-start ps-4">Tên Chương Trình</th>
                        <th class="py-3">Banner</th> 
                        <th class="py-3">Giảm Giá</th>
                        <th class="py-3">Thời Gian</th>
                        <th class="py-3">Trạng Thái</th>
                        <th class="py-3">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($listKM as $km): ?>
                    <tr>
                        <td class="py-3 text-start ps-4 fw-bold text-white"><?= htmlspecialchars($km['ten_km']) ?></td>
                        <td class="py-3">
                            <?php if(!empty($km['hinh_anh_banner'])): ?>
                                <img src="/LTWNC_LTWNC_WEBTMDT/assets/images/banners/<?= $km['hinh_anh_banner'] ?>"
                                     style="width:60px;height:30px;object-fit:cover;" class="rounded border border-secondary">
                            <?php else: ?>
                                <span class="text-secondary small">Không có</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 text-warning fw-bold">-<?= $km['phan_tram_giam'] ?>%</td>
                        <td class="py-3 small text-white-50">
                            <?= date('d/m/Y', strtotime($km['ngay_bat_dau'])) ?> <br> đến <?= date('d/m/Y', strtotime($km['ngay_ket_thuc'])) ?>
                        </td>
                        <td class="py-3">
                            <?php if($km['trang_thai'] == 1): ?>
                                <span class="badge bg-success rounded-pill px-3">Đang chạy</span>
                            <?php else: ?>
                                <span class="badge bg-secondary rounded-pill px-3">Tạm dừng</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3">
                            <a href="index.php?act=toggleStatusKM&id=<?= $km['id_khuyen_mai'] ?>&status=<?= $km['trang_thai'] ? 0 : 1 ?>"
                               class="btn btn-sm btn-outline-warning rounded-circle" style="width: 35px; height: 35px;">
                                <i class="fas fa-power-off"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="kmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content text-white border-0 shadow-lg" style="background-color: #1a1a1a;">
            <div class="modal-header border-secondary" style="background-color: #222;">
                <h5 class="modal-title fw-bold text-primary">Tạo Chương Trình Khuyến Mãi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?act=TaoKhuyenMai" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted">Tên chương trình</label>
                        <input type="text" name="ten_km" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label text-muted">% Giảm giá</label>
                            <input type="number" name="phan_tram_giam" class="form-control bg-dark border-secondary text-white" required>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label text-muted">Trạng thái</label>
                            <select name="trang_thai" class="form-select bg-dark border-secondary text-white">
                                <option value="1">Đang chạy</option>
                                <option value="0">Tạm dừng</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label text-muted">Ngày bắt đầu</label>
                            <input type="date" name="ngay_bat_dau" class="form-control bg-dark border-secondary text-white" required>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label text-muted">Ngày kết thúc</label>
                            <input type="date" name="ngay_ket_thuc" class="form-control bg-dark border-secondary text-white" required>
                        </div>
                    </div>
                    <div class="mb-3">

                    <label class="form-label">
                        Áp dụng cho sản phẩm / biến thể
                    </label>

                    <div class="search-box mb-3">
                        <input
                            type="text"
                            id="searchProduct"
                            class="form-control"
                            placeholder="🔍 Tìm sản phẩm..."
                        >
                    </div>
                        <div class="product-list">
                        <?php foreach($listSanPham as $sp): ?>
                        <div class="product-item">
                        <div class="product-header" onclick="toggleVariants(this)">
                        <div>
                        <i class="fas fa-box text-warning me-2"></i>
                        <?= $sp['ten_san_pham'] ?>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="variant-list">
                        <div class="variant-item">
                        <label>
                        <input
                        type="radio"
                        name="selection"
                        value="sp_<?= $sp['id_san_pham']?>">
                        <b>Áp dụng toàn bộ sản phẩm</b>
                        </label>
                        </div>
                        <?php foreach($sp['bien_the'] as $bt): ?>
                        <div class="variant-item">
                        <label>
                        <input
                        type="radio"
                        name="selection"
                        value="bt_<?= $bt['id_bien_the']?>">
                        <?= $bt['kich_co'] ?>
                        -
                        <span style=" width:18px; height:18px; display:inline-block;  border-radius:50%;
                        background:<?= $bt['mau_sac'] ?>;  border:1px solid #fff; vertical-align:middle;margin:0 6px;
                        "></span>
                        </label>
                        <?php if ($bt['so_luong_ton'] > 0): ?>
                            <span class="badge bg-success">
                                Tồn <?= $bt['so_luong_ton'] ?>
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger">
                                Hết hàng
                            </span>
                        <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                        </div>
                        </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-muted">Ảnh Banner</label>
                        <input type="file" name="hinh_anh_banner" class="form-control bg-dark border-secondary text-white" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-secondary" style="background-color: #222;">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Lưu Thông Tin</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

function toggleVariants(header){
    let item=header.parentElement;
    let list=item.querySelector(".variant-list");
    let open=item.classList.contains("active");
    document.querySelectorAll(".product-item").forEach(function(i){
        i.classList.remove("active");
        i.querySelector(".variant-list").style.display="none";
    });
    if(!open){
        item.classList.add("active");
        list.style.display="block";
    }
}

document.getElementById("searchProduct").addEventListener("keyup",function(){

    let keyword=this.value.toLowerCase();

    document.querySelectorAll(".product-item").forEach(function(item){

        let name=item.querySelector(".product-header").innerText.toLowerCase();

        if(name.indexOf(keyword)>-1){

            item.style.display="block";

        }else{

            item.style.display="none";

        }

    });

});

</script>
<?php
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>