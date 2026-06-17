
<?php if (!empty($danh_sach_bien_the)): ?>
    <?php foreach ($danh_sach_bien_the as $v): ?>
        <tr>
            <td><input type="text" name="size[]" class="form-control variant-input border-0 bg-transparent" value="<?= htmlspecialchars($v['kich_co'] ?? '') ?>" readonly></td>
            <td><input type="text" name="mau[]" class="form-control variant-input border-0 bg-transparent" value="<?= htmlspecialchars($v['mau_sac'] ?? '') ?>" readonly></td>
            <td>
                <input type="text" 
                    class="form-control variant-input border-0 bg-transparent" 
                    value="<?= number_format($v['gia_ban'] ?? 0, 0, ',', '.') ?>đ" 
                    readonly>
                <input type="hidden" name="gia_ban[]" value="<?= $v['gia_ban'] ?? 0 ?>">
            </td>
            <td><input type="number" name="ton_kho[]" min="0" class="form-control variant-input border-0 bg-transparent" value="<?= $v['stock'] ?? 0 ?>" readonly></td>
            <td>
                <input type="hidden" name="anh_cu_bien_the[]" value="<?= $v['hinh_anh_bien_the'] ?? '' ?>">
                <img src="/LTWNC_LTWNC_WEBTMDT/assets/images/products/Bien_The_Products/<?= !empty($v['hinh_anh_bien_the']) ? $v['hinh_anh_bien_the'] : 'default.jpg' ?>" width="40" class="mb-1 rounded">
                <input type="file" name="anh_bien_the[]" class="form-control form-control-sm mt-1" style="display:none;">
            </td>
            <td>
                <button type="button" class="btn btn-warning btn-sm" onclick="toggleEditMode(this)">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>