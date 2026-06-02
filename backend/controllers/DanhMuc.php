<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}
    include_once '../config/database.php';
    include_once '../model/Danhmuc.php';
    $database = new Database();
    $db = $database->getConnection();

    $danhMuc =new DanhMuc($db);
    $method=  $_SERVER['REQUEST_METHOD'];
    $data = json_decode(file_get_contents("php://input"));
   switch ($method) {
        case 'GET':
            $stmt = $danhMuc->getDanhMuc(); 
            $danh_muc_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($danh_muc_list);
            break;

        case 'POST':
            if(!empty($data->ten_danh_muc)) {
                $danhMuc->setData($data->ten_danh_muc, $data->mo_ta, $data->trang_thai);
                if($danhMuc->themDanhMuc()) {
                    http_response_code(201);
                    echo json_encode(["message" => "Thêm danh mục thành công!"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["message" => "Không thể thêm danh mục."]);
                }
            }
            break;

        case 'PUT':
            if(!empty($data->id_danh_muc)) {
                $danhMuc->setData($data->ten_danh_muc, $data->mo_ta, $data->trang_thai, $data->id_danh_muc);
                if($danhMuc->updateDanhMuc()) {
                    http_response_code(200);
                    echo json_encode(["message" => "Cập nhật thành công!"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["message" => "Không thể cập nhật."]);
                }
            }
            break;

        case 'DELETE':
            $id_to_delete = !empty($data->id_danh_muc) ? $data->id_danh_muc : (isset($_GET['id']) ? $_GET['id'] : null);
            
            if(!empty($id_to_delete)) {
                $danhMuc->setIdDanhMuc($id_to_delete); 
                if($danhMuc->deleteDanhMuc()) {
                    http_response_code(200);
                    echo json_encode(["message" => "Xóa thành công!"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["message" => "Không thể xóa."]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Thiếu ID danh mục."]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["message" => "Phương thức không được hỗ trợ."]);
            break;
    }
?>  