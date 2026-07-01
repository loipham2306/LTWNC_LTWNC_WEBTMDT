<?php
$dataKhachHang = $dataKhachHang ?? [];
$chartData = $chartData ?? [];
$thongKeSanPham = $thongKeSanPham ?? [];
$thongKeBienThe = $thongKeBienThe ?? [];
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
            <li class="nav-item"> <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-thong-ke-sp"> Thống kê sản phẩm </button> </li>
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
        <div class="tab-pane fade" id="tab-thong-ke-sp">
            
        <div class="card p-3 mb-4">
            <h5 class="text-warning mb-3">
                Thống kê theo sản phẩm
            </h5>
            <div class="row mb-3 g-2 align-items-center">
                <div class="col-md-4">
                    <input
                        type="text"
                        id="searchSanPham"
                        class="form-control"
                        placeholder="🔍 Tìm tên sản phẩm...">
                </div>

                <div class="col-md-3">
                    <select id="filterDanhGia" class="form-select">
                        <option value="">Tất cả đánh giá</option>
                        <option value="Bán chạy">Bán chạy</option>
                        <option value="Ổn định">Ổn định</option>
                        <option value="Bán chậm">Bán chậm</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select id="sortSanPham" class="form-select">
                        <option value="">Sắp xếp</option>
                        <option value="ban_desc">Đã bán ↓</option>
                        <option value="ban_asc">Đã bán ↑</option>
                        <option value="ton_desc">Tồn kho ↓</option>
                        <option value="ton_asc">Tồn kho ↑</option>
                        <option value="dt_desc">Doanh thu ↓</option>
                        <option value="dt_asc">Doanh thu ↑</option>
                    </select>
                </div>

            </div>               
            <table class="table table-dark table-hover">
                <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th class="text-center">Đã bán</th>
                    <th class="text-center">Tồn kho</th>
                    <th class="text-end">Doanh thu</th>
                    <th class="text-center">Đánh giá</th>
                </tr>
                </thead>

                <tbody id="tbodySanPham">
                    <?php foreach($thongKeSanPham as $sp): ?>
                    <tr
                        data-ten="<?= mb_strtolower($sp['ten_san_pham'],'UTF-8') ?>"
                        data-ban="<?= $sp['da_ban'] ?>"
                        data-ton="<?= $sp['ton_kho'] ?>"
                        data-doanhthu="<?= $sp['doanh_thu'] ?>"
                        data-danhgia="<?=
                            $sp['da_ban'] >= 10 ? 'Bán chạy' :
                            ($sp['da_ban'] >= 5 ? 'Ổn định' : 'Bán chậm')
                        ?>">
                        <td><?= htmlspecialchars($sp['ten_san_pham']) ?></td>
                        <td class="text-center">
                            <?= $sp['da_ban'] ?>
                        </td>
                        <td class="text-center">
                            <?= $sp['ton_kho'] ?>
                        </td>
                        <td class="text-end text-success">
                            <?= number_format($sp['doanh_thu']) ?>đ
                        </td>
                        <td class="text-center">
                            <?php
                            if ($sp['da_ban'] >= 10) {
                                echo '<span class="badge bg-success">Bán chạy</span>';
                            } elseif ($sp['da_ban'] >= 5) {
                                echo '<span class="badge bg-warning text-dark">Ổn định</span>';
                            } else {
                                if ($sp['co_khuyen_mai']) {
                                    echo '<span class="badge bg-info">
                                            <i class="fas fa-tags me-1"></i>
                                            Đã có khuyến mãi
                                        </span>';
                                } else {
                                    echo '
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <span class="badge bg-danger">Bán chậm</span>
                                            <a href="index.php?act=QuanLyKhuyenMai"
                                            class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-tags"></i> Khuyến mãi
                                            </a>
                                        </div>';
                                }
                            }
                            ?>
                            </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
            </table>

        </div>
        <div class="card p-3">

        <h5 class="text-warning mb-3">
            Thống kê theo biến thể
        </h5>
        <div class="row mb-3 g-2">
            <div class="col-md-3">
                <input
                    type="text"
                    id="searchBienThe"
                    class="form-control"
                    placeholder="🔍 Tìm sản phẩm">
            </div>

            <div class="col-md-2">
                <select id="filterSize" class="form-select">
                    <option value="">Tất cả Size</option>

                    <?php
                    $sizes = array_unique(array_column($thongKeBienThe,'kich_co'));
                    sort($sizes);
                    foreach($sizes as $size):
                    ?>
                        <option value="<?= $size ?>">
                            <?= $size ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="col-md-2">
                <select id="filterMau" class="form-select">
                    <option value="">Tất cả màu</option>

                    <?php
                    $maus = array_unique(array_column($thongKeBienThe,'mau_sac'));
                    foreach($maus as $mau):
                    ?>
                        <option value="<?= $mau ?>">
                            <?= $mau ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="col-md-3">
                <select id="sortBienThe" class="form-select">
                    <option value="">Mặc định</option>

                    <option value="ban_desc">Đã bán ↓</option>
                    <option value="ban_asc">Đã bán ↑</option>

                    <option value="ton_desc">Tồn ↓</option>
                    <option value="ton_asc">Tồn ↑</option>

                    <option value="dt_desc">Doanh thu ↓</option>
                    <option value="dt_asc">Doanh thu ↑</option>
                </select>
            </div>

        </div>                
        <table class="table table-dark table-hover">
            <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Size</th>
                <th>Màu</th>
                <th class="text-center">Đã bán</th>
                <th class="text-center">Tồn</th>
                <th class="text-end">Doanh thu</th>
            </tr>
            </thead>
            <tbody id="tbodyBienThe">

                <?php foreach($thongKeBienThe as $bt): ?>

                <tr
                data-ten="<?= mb_strtolower($bt['ten_san_pham'],'UTF-8') ?>"
                data-size="<?= $bt['kich_co'] ?>"
                data-mau="<?= $bt['mau_sac'] ?>"
                data-ban="<?= $bt['da_ban'] ?>"
                data-ton="<?= $bt['so_luong_ton'] ?>"
                data-doanhthu="<?= $bt['doanh_thu'] ?>"
                >

                    <td><?= htmlspecialchars($bt['ten_san_pham']) ?></td>

                    <td><?= $bt['kich_co'] ?></td>

                    <td>
                        <span style="
                            display:inline-block;
                            width:18px;
                            height:18px;
                            border-radius:50%;
                            background:<?= $bt['mau_sac'] ?>;
                            border:1px solid #999;
                        "></span>
                    </td>

                    <td class="text-center"><?= $bt['da_ban'] ?></td>

                    <td class="text-center"><?= $bt['so_luong_ton'] ?></td>

                    <td class="text-end text-success">
                        <?= number_format($bt['doanh_thu']) ?>đ
                    </td>

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
const searchSP = document.getElementById("searchSanPham");
const filterDG = document.getElementById("filterDanhGia");
const sortSP = document.getElementById("sortSanPham");
const tbodySP = document.getElementById("tbodySanPham");
const originalRows = [...tbodySP.querySelectorAll("tr")];
function locSanPham(){

    let rows = [...tbodySP.querySelectorAll("tr")];

    const keyword = searchSP.value
        .trim()
        .toLocaleLowerCase("vi-VN");
    const dg = filterDG.value;

    rows.forEach(row=>{

        const ten = row.dataset.ten;
        const danhgia = row.dataset.danhgia;

        const okTen = ten.includes(keyword);
        const okDG = dg=="" || danhgia===dg;

        row.style.display = (okTen && okDG) ? "" : "none";

    });

}
function sapXep(){
    let rows=[...tbodySP.querySelectorAll("tr")];
    const value=sortSP.value;
    if(value===""){
        tbodySP.innerHTML="";
        originalRows.forEach(r=>tbodySP.appendChild(r));
        return;
    }
    rows.sort((a,b)=>{
        switch(value){
            case "ban_desc":
                return Number(b.dataset.ban)-Number(a.dataset.ban);
            case "ban_asc":
                return Number(a.dataset.ban)-Number(b.dataset.ban);
            case "ton_desc":
                return Number(b.dataset.ton)-Number(a.dataset.ton);
            case "ton_asc":
                return Number(a.dataset.ton)-Number(b.dataset.ton);
            case "dt_desc":
                return Number(b.dataset.doanhthu)-Number(a.dataset.doanhthu);
            case "dt_asc":
                return Number(a.dataset.doanhthu)-Number(b.dataset.doanhthu);
            default:
                return 0;
        }
    });
    tbodySP.innerHTML="";
    rows.forEach(r=>tbodySP.appendChild(r));
}
searchSP.addEventListener("keyup",locSanPham);
filterDG.addEventListener("change",locSanPham);
sortSP.addEventListener("change",function(){
    sapXep();
    locSanPham();
});
document.addEventListener('DOMContentLoaded', function(){
    setTimeout(() => {
        renderChart(
            'tuan',
            document.querySelector('#chart-buttons .btn')
        );

    }, 100);

});
// lọc theo biến thể
const searchBT = document.getElementById("searchBienThe");
const filterSize = document.getElementById("filterSize");
const filterMau = document.getElementById("filterMau");
const sortBT = document.getElementById("sortBienThe");

const tbodyBT = document.getElementById("tbodyBienThe");

const originalRowsBT = [...tbodyBT.querySelectorAll("tr")];

function locBienThe(){
    const keyword = searchBT.value.trim().toLowerCase();
    const size = filterSize.value;
    const mau = filterMau.value;

    [...tbodyBT.querySelectorAll("tr")].forEach(row=>{

        const okTen = row.dataset.ten.includes(keyword);
        const okSize = size==="" || row.dataset.size===size;
        const okMau = mau==="" || row.dataset.mau===mau;

        row.style.display =
            (okTen && okSize && okMau)
            ? ""
            : "none";

    });
}
function sapXepBienThe(){
    let rows=[...tbodyBT.querySelectorAll("tr")];
    const value = sortBT.value;
    if(value===""){
        tbodyBT.innerHTML="";
        originalRowsBT.forEach(r=>tbodyBT.appendChild(r));
        locBienThe();
        return;
    }
    rows.sort((a,b)=>{
        switch(value){
            case "ban_desc":
                return Number(b.dataset.ban)-Number(a.dataset.ban);
            case "ban_asc":
                return Number(a.dataset.ban)-Number(b.dataset.ban);
            case "ton_desc":
                return Number(b.dataset.ton)-Number(a.dataset.ton);
            case "ton_asc":
                return Number(a.dataset.ton)-Number(b.dataset.ton);
            case "dt_desc":
                return Number(b.dataset.doanhthu)-Number(a.dataset.doanhthu);
            case "dt_asc":
                return Number(a.dataset.doanhthu)-Number(b.dataset.doanhthu);
            default:
                return 0;
        }
    });
    tbodyBT.innerHTML="";
    rows.forEach(r=>tbodyBT.appendChild(r));
    locBienThe();
}
searchBT.addEventListener("keyup",locBienThe);
filterSize.addEventListener("change",locBienThe);
filterMau.addEventListener("change",locBienThe);
sortBT.addEventListener("change",sapXepBienThe);
</script>