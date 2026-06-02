<?php
// Cấu hình các Header bắt buộc để React gọi được API (Chống lỗi CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Xử lý tiền kiểm tra (Pre-flight request) của Axios
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Khai báo kết nối cơ sở dữ liệu và Model thương hiệu
include_once '../config/database.php';
include_once '../model/ThuongHieu.php'; // Đảm bảo thư mục là 'model' (hoặc sửa thành 'models' nếu cần)

$database = new Database();
$db = $database->getConnection();

$brand = new ThuongHieu($db);

// Xem phương thức React gửi lên là gì (GET, POST, PUT hay DELETE)
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // LẤY DANH SÁCH THƯƠNG HIỆU
        $stmt = $brand->getTatCaThuongHieu();
        $num = $stmt->rowCount();

        if($num > 0) {
            $brand_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $brand_item = array(
                    "id_thuong_hieu" => $id_thuong_hieu,
                    "ten_thuong_hieu" => $ten_thuong_hieu,
                    "mo_ta" => $mo_ta,
                    "hinh_anh_logo" => $hinh_anh_logo,
                    "trang_thai" => $trang_thai,
                    "ngay_tao" => $ngay_tao
                );
                array_push($brand_arr, $brand_item);
            }
            http_response_code(200);
            echo json_encode($brand_arr);
        } else {
            http_response_code(200);
            echo json_encode(array()); // Trả về mảng rỗng nếu chưa có dữ liệu
        }
        break;

    case 'POST':
        // THÊM MỚI THƯƠNG HIỆU
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->ten_thuong_hieu)) {
           $brand->setData(
                $data->ten_thuong_hieu,
                $data->mo_ta ?? '',
                $data->hinh_anh_logo ?? '',
                $data->trang_thai ?? 'active'
           );

            if($brand->createThuongHieu()) {
                http_response_code(201);
                echo json_encode(array("message" => "Thêm thương hiệu thành công!"));
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Không thể tạo thương hiệu."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Dữ liệu không đầy đủ (Thiếu tên hãng)."));
        }
        break;
case 'PUT':
        // Lấy dữ liệu từ Request Body
        $data = json_decode(file_get_contents("php://input"));

        // Kiểm tra đúng tên trường của Thương Hiệu
        if (!empty($data->id_thuong_hieu) && !empty($data->ten_thuong_hieu)) {
            
            // Dùng đúng object $brand và các thuộc tính thương hiệu
            $brand->setData(
                $data->ten_thuong_hieu,
                $data->mo_ta ?? '',
                $data->hinh_anh_logo ?? '',
                isset($data->trang_thai) ? (int)$data->trang_thai : 1,
                $data->id_thuong_hieu // ID thương hiệu
            );

            // Gọi đúng hàm update của brand
            if ($brand->updateThuongHieu()) {
                http_response_code(200);
                echo json_encode(array("message" => "Cập nhật thương hiệu thành công!"));
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Không thể cập nhật thương hiệu."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Dữ liệu thiếu mã ID hoặc Tên thương hiệu để sửa."));
        }
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id_thuong_hieu)) {
            // Gán ID cần xóa vào model
            $brand->setIdThuongHieu($data->id_thuong_hieu);
            
            if($brand->deleteThuongHieu()) {
                http_response_code(200);
                echo json_encode(array("message" => "Xóa thương hiệu thành công!"));
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Không thể xóa thương hiệu này."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Thiếu mã ID để xóa."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Phương thức không được hỗ trợ."));
        break;
}
?>