<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../config/database.php';
include_once '../model/SanPham.php';

$database = new Database();
$db = $database->getConnection();
$sp = new SanPham($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $stmt = $sp->LayTatCaSanPham(); // Hàm này đã có JOIN
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products ? $products : array());
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->ten_san_pham)) {
            $sp->setData($data->ten_san_pham, $data->gia, $data->giam_gia, $data->mo_ta, $data->hinh_anh, $data->id_danh_muc, $data->id_thuong_hieu, $data->trang_thai);
            
            if($sp->ThemSanPham()) {
                http_response_code(201);
                echo json_encode(array("message" => "Thêm sản phẩm thành công!"));
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Lỗi tạo sản phẩm."));
            }
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->id_san_pham)) {
            $sp->id_san_pham = $data->id_san_pham;
            $sp->setData($data->ten_san_pham, $data->gia, $data->giam_gia, $data->mo_ta, $data->hinh_anh, $data->id_danh_muc, $data->id_thuong_hieu, $data->trang_thai);
            
            if($sp->CapNhatSanPham()) {
                http_response_code(200);
                echo json_encode(array("message" => "Cập nhật sản phẩm thành công!"));
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Cập nhật thất bại."));
            }
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->id_san_pham)) {
            $sp->id_san_pham = $data->id_san_pham;
            if($sp->XoaSanPham()) {
                http_response_code(200);
                echo json_encode(array("message" => "Đã xóa sản phẩm!"));
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Không thể xóa sản phẩm."));
            }
        }
        break;
}
?>