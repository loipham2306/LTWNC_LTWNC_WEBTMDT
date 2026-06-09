<?php
class HomeController {

    private $spModel;
    private $thModel;

    public function __construct($db) {
        $this->spModel = new SanPham($db);
        $this->thModel = new ThuongHieu($db);
    }

    public function index() {
        $products = $this->spModel->getSanPhamHome();
        $brands = $this->thModel->getTatCaThuongHieu();
        
        include '../index.php';
    }
}
?>