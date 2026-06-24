<?php
if (session_status() === PHP_SESSION_NONE) session_start();
ob_start(); 
$listKM = $listKM ??[];
$listBienThe = $listBienThe??[];
?>

<div class="container-fluid p-4" style="background-color: #111; min-height: 100vh; color: #fff;">

    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
        <div>
            <h3 class="fw-bold text-white mb-1">Quản Lý Khuyến Mãi</h3>
            <p class="text-muted small mb-0">Thiết lập các chương trình giảm giá cho sản phẩm</p>
        </div>
        <button class="btn btn-primary px-4 py-2 rounded-pill fw-bold" 
                data-bs-toggle="modal" data-bs-target="#kmModal" onclick="resetForm()"> 
            <i class="fas fa-plus me-2"></i>Tạo Khuyến Mãi
        </button>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success fw-bold">✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="card border-0 rounded overflow-hidden" style="background-color: #1a1a1a;">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0 text-center">
                <thead>
                    <tr style="border-bottom: 2px solid #F28B00;">
                        <th class="py-3 text-start ps-4">Tên Chương Trình</th>
                        <th class="py-3">Banner</th> <th class="py-3">Giảm Giá</th>
                        <th class="py-3">Thời Gian</th>
                        <th class="py-3">Trạng Thái</th>
                        <th class="py-3">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($listKM as $km): ?>
                    <tr>
                        <td class="py-3 text-start ps-4 fw-bold">
                            <?= htmlspecialchars($km['ten_km']) ?>
                        </td>

                        <td class="py-3">
                            <?php if(!empty($km['hinh_anh_banner'])): ?>
                                <img
                                    src="/LTWNC_LTWNC_WEBTMDT/assets/images/banners/<?= $km['hinh_anh_banner'] ?>"
                                    style="width:60px;height:30px;object-fit:cover;"
                                    class="rounded border border-secondary">
                            <?php else: ?>
                                <span class="text-secondary">Không có</span>
                            <?php endif; ?>
                        </td>

                        <td class="py-3 text-warning fw-bold">
                            -<?= $km['phan_tram_giam'] ?>%
                        </td>

                        <td class="py-3 small text-white-50">
                            <?= date('d/m/Y', strtotime($km['ngay_bat_dau'])) ?>
                            -
                            <?= date('d/m/Y', strtotime($km['ngay_ket_thuc'])) ?>
                        </td>

                        <td class="py-3">
                            <?php if($km['trang_thai'] == 1): ?>
                                <span class="badge bg-success rounded-pill">
                                    Đang chạy
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary rounded-pill">
                                    Tạm dừng
                                </span>
                            <?php endif; ?>
                        </td>

                        <td class="py-3">
                            <a href="index.php?act=toggleStatusKM&id=<?= $km['id_khuyen_mai'] ?>&status=<?= $km['trang_thai'] ? 0 : 1 ?>"
                            class="btn btn-sm btn-outline-warning rounded-circle">
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

<div class="modal fade" id="kmModal" tabindex="-1" aria-hidden="true" style="background-color: rgba(0,0,0,0.7);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-white border-0" style="background-color: #1a1a1a;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold text-primary">Chương Trình Khuyến Mãi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?act=TaoKhuyenMai" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Tên chương trình</label>
                        <input type="text" name="ten_km" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">% Giảm giá</label>
                            <input type="number" name="phan_tram_giam" class="form-control bg-dark border-secondary text-white" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Trạng thái</label>
                            <select name="trang_thai" class="form-select bg-dark border-secondary text-white">
                                <option value="1">Đang chạy</option>
                                <option value="0">Tạm dừng</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Ngày bắt đầu</label>
                            <input type="date" name="ngay_bat_dau" class="form-control bg-dark border-secondary text-white" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Ngày kết thúc</label>
                            <input type="date" name="ngay_ket_thuc" class="form-control bg-dark border-secondary text-white" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Áp dụng cho (Sản phẩm / Biến thể)</label>
                        <select name="selection" class="form-select bg-dark border-secondary text-white">
                            <option value="">-- Chọn sản phẩm hoặc biến thể --</option>
                            <?php 
                                $current_sp = null;
                                foreach($listBienThe as $item): 
                                    // Tiêu đề sản phẩm
                                    if ($current_sp !== $item['id_san_pham']): 
                                        $current_sp = $item['id_san_pham'];
                                ?>
                                    <option value="sp_<?= $item['id_san_pham'] ?>" style="font-weight:bold; background-color: #333; color: #F28B00;">
                                        <?= $item['ten_san_pham'] ?> (Tất cả biến thể)
                                    </option>
                                <?php endif; ?>

                                <?php 
                                    // Lấy màu từ database (giả sử $item['mau_sac'] là mã Hex như #FF0000)
                                    $color = $item['mau_sac']; 
                                ?>
                                    <option value="bt_<?= $item['id_bien_the'] ?>" 
                                            style="color: <?= $color ?>; font-weight: 600;">
                                        &nbsp;&nbsp;&nbsp;&nbsp;⬤ <?= $item['mau_sac'] ?> / Size: <?= $item['kich_co'] ?> (Còn <?= $item['so_luong_ton'] ?>)
                                    </option>
                                <?php endforeach; ?>
                        </select>
                        <small class="text-warning">*Chọn sản phẩm/biến thể cần đẩy hàng tồn</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Ảnh Banner</label>
                        <input type="file" name="hinh_anh_banner" class="form-control bg-dark border-secondary text-white" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Lưu Thông Tin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>