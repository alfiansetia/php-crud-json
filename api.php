<?php

class Api
{
    protected $data;

    public function __construct()
    {
        $filePath = 'master.json';
        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath);
            $this->data = json_decode($jsonData, true);
        } else {
            $this->data = [];
        }
    }

    public function add(array $newData)
    {
        $this->data['router'][] = $newData;
        $this->save();
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode(["status" => true, "message" => "Data berhasil ditambah!"]);
    }


    private function save()
    {
        $jsonData = json_encode($this->data);
        $filePath = 'master.json';
        if (file_put_contents($filePath, $jsonData) === false) {
            throw new Exception("Gagal menyimpan data ke file JSON");
        }
    }
}


$api = new Api();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        if (isset($_POST['name']) && isset($_POST['ip'])) {
            $data['name'] = $_POST['name'];
            $data['ip'] = $_POST['ip'];
            $data['last_active'] = null;
            $data['id'] = uniqid();
            $api->add($data);
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(["status" => false, "message" => "Data yang diperlukan tidak lengkap!"]);
        }
    } else {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(["status" => false, "message" => "Invalid Action!"]);
    }
}
