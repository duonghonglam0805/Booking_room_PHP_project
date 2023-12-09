<?php
class App{
//khai báo các thuộc tính
    private $__controller, $__action, $__params;
//hàm tạo
    function __construct(){
        global $routes;
        if(!empty($routes['default_controller'])){
            $this-> __controller = $routes  ['default_controller'];
        }
        $this -> __action = "index";
        $this -> __params = [];

        $this->handleUrl();
    }
    //hàm lấy ra url
    function getUrl(){
        if(!empty($_SERVER['PATH_INFO'])){
            $url = $_SERVER['PATH_INFO'];
        }else {
            $url = "/";
        }
        return $url;
    }
    //hàm phân tích url ra thành các phần tử của một mảng
    public function handleUrl(){
        $url = $this->getUrl();
        $urlArr = array_filter(explode("/",$url));
        $urlArr = array_values($urlArr); 
//Xứ lý controller
        if(!empty($urlArr[0])){
            $this->__controller = ucfirst($urlArr[0]);
        }else{
            $this->__controller = ucfirst($this->__controller);
        }
            if(file_exists("app/controllers/".($this->__controller).".php")){
                require_once("controllers/".$this->__controller . ".php");
                if (class_exists($this->__controller)){
                $this ->__controller = new $this->__controller();
                unset($urlArr[0]);
                }else{
                    $this->loadError();
                }
            }else{
                $this -> loadError(); //được sử dụng để gọi phương thức loadError().
        }

        
//Xử lí action
        if(!empty($urlArr[1])){
            $this->__action = $urlArr[1]; 
            unset($urlArr[1]);
        }
//Xử lý params
    $this->__params = array_values($urlArr);
    if(method_exists($this ->__controller, $this->__action)){
        call_user_func_array([$this ->__controller ,$this->__action],$this->__params);
    }else {
        $this->loadError();
    }
    }

    //viết hàm hiển thị lỗi
    public function loadError($name = "404"){
    require_once 'errors/' . $name . '.php'; 
    }
}
?>