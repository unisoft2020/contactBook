<?php
class Contacts{
    
    private $mysqli; 
    public $response = []; 
    
    public function __construct(){
       $this->mysqli = new mysqli("localhost", "root", "", "clients");
       if($this->mysqli->connect_errno) exit('Connect Error...');
       $this->mysqli->set_charset("utf8");
       $this->mysqli->query("SET `time_zone` = '+5:00'");
    }
    
    public function __call($name, $value){
        return false;
    }
    
    public function addContact($data = []){
        
        if(!isset($data["source_id"]) || empty($data["source_id"])){
            $this->response["field"][] = [
                "type"=>"error",
                "name"=>"source_id",
                "message"=>"Заполните идентификтор"
            ];
        }

        for($i=0; $i < count($data["items"]); $i++){
            if(!isset($data["items"][$i]["name"]) || empty($data["items"][$i]["name"])){
                $this->response["field"][] = [
                    "type"=>"error",
                    "name"=>'items['.$i.'][name]',
                    "message"=>"Заполните Имя"
                ];
            }

            if(!isset($data["items"][$i]["email"]) || empty($data["items"][$i]["email"])){
                $this->response["field"][] = [
                    "type"=>"error", 
                    "name"=>'items['.$i.'][email]', 
                    "message"=>"Заполните эл. почту"
                ];
            }

            if(!isset($data["items"][$i]["phone"]) || empty($data["items"][$i]["phone"])){
                $this->response["field"][] = [
                    "type"=>"error", 
                    "name"=>'items['.$i.'][phone]', 
                    "message"=>"Заполните телефон"
                ];
            }

            $data["items"][$i]["phone"] = preg_replace('/[\/(\/)\-_ ]/', '', $data["items"][$i]["phone"]);

            if(strpos($data["items"][$i]["phone"], "+") !== false){
                $data["items"][$i]["phone"] = substr($data["items"][$i]["phone"], 2);
            }

            if(strlen($data["items"][$i]["phone"]) != 10){
                $this->response["field"][] = [
                    "type"=>"error", 
                    "name"=>'items['.$i.'][phone]', 
                    "message"=>"Формат телефона не валидный"
                ];
            }

            if(count($this->getContacts(["source_id"=>$data["source_id"], "phone"=>$data["items"][$i]["phone"], "start_date"=>date("Y-m-d")." 00:00:00"])) > 0){
                $this->response["field"][] = [
                    "type"=>"error", 
                    "name"=>'items['.$i.'][phone]', 
                    "message"=>"Телефон сегодня был уже добавлен"
                ];
            }

        }

        if(count($this->response) > 0){
            echo json_encode($this->response, JSON_UNESCAPED_UNICODE);
            return false;
        }
        
        $source_id = $data["source_id"];
        $contact_data = json_encode($data["items"], JSON_UNESCAPED_UNICODE);
        
        $stmt = $this->mysqli->prepare("INSERT INTO `contacts`(`source_id`, `contact_data`, `date`)VALUES(?, ?, NOW())");
        $stmt->bind_param("is", $source_id, $contact_data);
        $stmt->execute();
        $stmt->close();
        
        $this->response["field"][] = [
            "type"=>"success", 
            "name"=>'add', 
            "message"=>"".count($data["items"])." контакта было успешно добавлено"
        ];
        $this->response["reset"] = true;
        
        echo json_encode($this->response, JSON_UNESCAPED_UNICODE);
        return false;
    }
    
    
    public function getContacts($data = []){
        $where = "WHERE `id` IS NOT NULL";
        $where_types = [];
        $where_params = [];
        
        if(isset($data["source_id"])){
            $where .= " AND `source_id`=?";
            array_push($where_types, "i");
            array_push($where_params, $data["source_id"]);
        }
        
        if(isset($data["phone"])){
            $where .= " AND `contact_data` LIKE ?";
            array_push($where_types, "s");
            array_push($where_params, "%".$data["phone"]."%");
        }
        
        if(isset($data["start_date"])){
            $where .= " AND `date` >= ?";
            array_push($where_types, "s");
            array_push($where_params, $data["start_date"]);
        }

        $stmt = $this->mysqli->prepare("SELECT * FROM `contacts` $where");
        if(!empty($where_params)){
            $where_types = implode("", $where_types);
            $stmt->bind_param($where_types, ...$where_params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $response =  $result->fetch_all(MYSQLI_ASSOC);
        
        if(isset($_GET) && !empty($_GET)){
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }else{
            return $response;
        }
    }
    
}