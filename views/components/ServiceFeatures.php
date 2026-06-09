<?php
// Khai báo mảng chứa thông tin các dịch vụ để dễ quản lý
$services = [
    [
        'icon'  => 'fa-sync-alt',
        'title' => 'Đổi Trả Miễn Phí',
        'desc'  => 'Hoàn tiền trong vòng 30 ngày!',
        'delay' => '0.1s'
    ],
    [
        'icon'  => 'fab fa-telegram-plane',
        'title' => 'Freeship',
        'desc'  => 'Miễn phí vận chuyển mọi đơn hàng',
        'delay' => '0.2s'
    ],
    [
        'icon'  => 'fas fa-life-ring',
        'title' => 'Hỗ Trợ 24/7',
        'desc'  => 'Luôn túc trực giải đáp thắc mắc',
        'delay' => '0.3s'
    ],
    [
        'icon'  => 'fas fa-credit-card',
        'title' => 'Quà Tặng Khủng',
        'desc'  => 'Tặng voucher cho đơn từ 1 triệu',
        'delay' => '0.4s'
    ],
    [
        'icon'  => 'fas fa-lock',
        'title' => 'Bảo Mật Cao',
        'desc'  => 'Thanh toán an toàn tuyệt đối',
        'delay' => '0.5s'
    ],
    [
        'icon'  => 'fas fa-blog',
        'title' => 'Dịch Vụ Nhanh',
        'desc'  => 'Giải quyết khiếu nại trong 24h',
        'delay' => '0.6s'
    ]
];
?>

<style>
    /* Ép tông màu eSports Đen - Cam cho dải tính năng */
    .service-bg {
        background-color: #1a1a1a;
        border-top: 1px solid #333;
        border-bottom: 1px solid #333;
    }
    
    /* Chỉnh màu viền giữa các cột cho tối lại */
    .service-border { border-color: #333 !important; }
    
    /* Màu cam thương hiệu */
    .text-orange { color: #F28B00 !important; }

    /* Hiệu ứng hover cho từng khối (Tự động nảy nhẹ lên) */
    .service-item-box {
        transition: all 0.3s ease;
        height: 100%;
    }
    .service-item-box:hover {
        background-color: #222;
        transform: translateY(-5px);
    }
</style>

<div class="container-fluid px-0 service-bg my-5">
    <div class="row g-0">
        
        <?php foreach ($services as $index => $item): ?>
            <div class="col-6 col-md-4 col-lg-2 border-end service-border <?= $index === 0 ? 'border-start' : '' ?> wow fadeInUp" data-wow-delay="<?= $item['delay'] ?>">
                
                <div class="p-4 service-item-box d-flex flex-column align-items-center text-center justify-content-center">
                    <i class="<?= $item['icon'] ?> fa-2x text-orange mb-3"></i>
                    <div>
                        <h6 class="text-uppercase text-white mb-2" style="font-size: 0.9rem; letter-spacing: 1px;">
                            <?= $item['title'] ?>
                        </h6>
                        <p class="mb-0 text-white-50 small">
                            <?= $item['desc'] ?>
                        </p>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>

    </div>
</div>