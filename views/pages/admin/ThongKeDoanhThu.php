<?php
$dataKhachHang = $dataKhachHang ?? [];
$chartData = $chartData ?? [];
?>
<style>
:root{
    --orange:#F28B00;
    --dark:#111;
    --card:#1a1a1a;
    --border:#333;
}

.container-fluid{
    color:#fff;
}

/* KPI CARD */
.stat-card{
    background:linear-gradient(135deg,#1a1a1a,#222);
    border:1px solid var(--border);
    border-radius:18px;
    padding:20px;
    transition:.3s;
    height:100%;
}

.stat-card:hover{
    transform:translateY(-5px);
    border-color:var(--orange);
    box-shadow:0 0 20px rgba(242,139,0,.25);
}

.stat-icon{
    width:60px;
    height:60px;
    border-radius:15px;
    background:rgba(242,139,0,.15);
    display:flex;
    align-items:center;
    justify-content:center;
    color:var(--orange);
    font-size:24px;
}

.stat-value{
    font-size:28px;
    font-weight:700;
    color:#fff;
}

.stat-title{
    color:#999;
    font-size:14px;
}

/* TAB */
.nav-pills .nav-link{
    background:#222;
    color:#ccc;
    border-radius:12px;
    margin-right:8px;
}

.nav-pills .nav-link.active{
    background:var(--orange)!important;
    color:#000!important;
    font-weight:700;
}

/* CARD */
.card{
    background:var(--card)!important;
    border:1px solid var(--border)!important;
    border-radius:18px;
}

/* TABLE */
.table-dark{
    --bs-table-bg:#1a1a1a;
}

.table-dark tbody tr:hover{
    background:#252525;
}

.table-dark thead th{
    color:var(--orange);
    border-color:#333;
}

/* CHART BUTTON */
.chart-btn{
    border:1px solid var(--orange);
    color:var(--orange);
}

.chart-btn.active{
    background:var(--orange);
    color:#000;
}

/* TITLE */
.section-title{
    color:var(--orange);
    font-weight:700;
}
</style>
<div class="container-fluid">
    <h3 class="text-white mb-4"><i class="fas fa-chart-line me-2 text-warning"></i>Báo cáo Thống kê</h3>
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="mt-3 stat-title">
                    Tổng doanh thu
                </div>
                <div class="stat-value">
                    <?= number_format($tongDoanhThu ?? 0,0,',','.') ?>đ
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="mt-3 stat-title">
                    Tổng đơn hàng
                </div>
                <div class="stat-value">
                    <?= number_format($tongDonHang ?? 0) ?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="mt-3 stat-title">
                    Khách hàng VIP
                </div>
                <div class="stat-value">
                    <?= count($dataKhachHang ?? []) ?>
                </div>
            </div>
        </div>

    </div>
        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-doanh-thu">Doanh Thu</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-san-pham">Sản phẩm bán chạy</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-khach-hang">Khách hàng tiềm năng</button></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-doanh-thu">
                <div class="d-flex gap-2 mb-3" id="chart-buttons">

        <button
            class="btn chart-btn active"
            onclick="renderChart('tuan',this)">
            <i class="fas fa-calendar-week me-1"></i>
            Theo tuần
        </button>

        <button
            class="btn chart-btn"
            onclick="renderChart('thang',this)">
            <i class="fas fa-calendar-alt me-1"></i>
            Theo tháng
        </button>

    </div>
            <div class="card bg-dark p-3" style="min-height: 350px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-san-pham">
            <div class="card bg-dark p-3">
              <h5 class="text-white mb-3">Top 10 Sản phẩm bán chạy nhất</h5>
                <table class="table table-dark table-hover">
                    <thead>
                        <tr class="text-warning">
                            <th>Tên sản phẩm</th>
                            <th class="text-center">Số lượng bán</th>
                            <th class="text-end">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dataSanPham)): ?>
                            <?php foreach ($dataSanPham as $sp): ?>
                            <tr>
                                <td><?= htmlspecialchars($sp['ten_san_pham']) ?></td>
                                <td class="text-center"><?= $sp['tong_ban'] ?></td>
                                <td class="text-end text-success fw-bold"><?= number_format($sp['doanh_thu'], 0, ',', '.') ?>đ</td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-muted">Chưa có dữ liệu sản phẩm</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-khach-hang">
            <div class="card bg-dark p-3">
                <h5 class="text-white mb-3">Top 10 khách hàng chi tiêu nhiều nhất</h5>
                <table class="table table-dark table-hover">
                    <thead>
                        <tr class="text-warning">
                            <th>Tên khách hàng</th>
                            <th class="text-center">Số đơn đã đặt</th>
                            <th class="text-end">Tổng chi tiêu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataKhachHang as $kh): ?>
                            <tr>
                                <td><?= htmlspecialchars(($kh['ho_ten_dem'] ?? '') . ' ' . ($kh['ten'] ?? '')) ?></td>
                                
                                <td class="text-center"><?= $kh['so_don'] ?></td>
                                <td class="text-end text-success fw-bold"><?= number_format($kh['chi_tieu'], 0, ',', '.') ?>đ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const allChartData = <?= json_encode($chartData, JSON_UNESCAPED_UNICODE) ?>;

console.log("Chart Data:", allChartData);

let revenueChart = null;

function renderChart(type, btn = null)
{
    // Active button
    document
        .querySelectorAll('#chart-buttons .btn')
        .forEach(b => b.classList.remove('active'));

    if(btn){
        btn.classList.add('active');
    }

    const chartData = allChartData[type];

    if(!chartData){
        console.error('Không tìm thấy dữ liệu:', type);
        return;
    }

    const canvas = document.getElementById('revenueChart');

    if(!canvas){
        console.error('Không tìm thấy canvas revenueChart');
        return;
    }

    const ctx = canvas.getContext('2d');

    if(revenueChart){
        revenueChart.destroy();
    }

    revenueChart = new Chart(ctx,{
        type:'line',
        data:{
            labels: chartData.labels,
            datasets:[{
                label:'Doanh thu',
                data: chartData.values,

                borderColor:'#F28B00',
                backgroundColor:'rgba(242,139,0,.2)',

                borderWidth:4,
                fill:true,

                pointBackgroundColor:'#F28B00',
                pointBorderColor:'#fff',
                pointRadius:6,
                pointHoverRadius:8,

                tension:.45
            }]
        },
        options:{
            responsive:true,
            maintainAspectRatio:false,

            plugins:{
                legend:{
                    labels:{
                        color:'#fff'
                    }
                }
            },

            scales:{
                x:{
                    ticks:{
                        color:'#fff'
                    },
                    grid:{
                        color:'rgba(255,255,255,0.1)'
                    }
                },

                y:{
                    beginAtZero:true,
                    ticks:{
                        color:'#fff',
                        callback:function(value){
                            return value.toLocaleString('vi-VN') + 'đ';
                        }
                    },
                    grid:{
                        color:'rgba(255,255,255,0.1)'
                    }
                }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function(){

    setTimeout(() => {

        renderChart(
            'tuan',
            document.querySelector('#chart-buttons .btn')
        );

    }, 100);

});
</script>