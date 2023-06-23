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

    public function store(array $newData)
    {
        $this->data['router'][] = $newData;
        $this->save();
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode(["status" => true, "message" => "Data berhasil ditambah!"]);
    }


    public function update($id, array $updatedData)
    {
        $index = $this->findIndexById($id);
        if ($index !== false) {
            $this->data['router'][$index] = array_merge($this->data['router'][$index], $updatedData);
            $this->save();
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(["status" => true, "message" => "Data dengan ID $id berhasil diperbarui!"]);
        } else {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(["status" => false, "message" => "Data dengan ID $id tidak ditemukan!"]);
        }
    }


    private function save()
    {
        $jsonData = json_encode($this->data, JSON_PRETTY_PRINT);
        $filePath = 'master.json';
        if (file_put_contents($filePath, $jsonData) === false) {
            throw new Exception("Gagal menyimpan data ke file JSON");
        }
    }

    public function show($id)
    {
        $data = [];
        foreach ($this->data['router'] as $item) {
            if ($item['id'] == $id) {
                $data = $item;
            }
        }
        if (count($data) < 1) {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(["status" => false, "message" => "Data Not Found!"]);
            return;
        } else {
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode($data);
            return;
        }
    }

    public function delete($id)
    {
        $index = $this->findIndexById($id);
        if ($index !== false) {
            unset($this->data['router'][$index]);
            $this->data['router'] = array_values($this->data['router']);
            $this->save();
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(["status" => true, "message" => "Data dengan ID $id berhasil dihapus!"]);
        } else {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(["status" => false, "message" => "Data dengan ID $id tidak ditemukan!"]);
        }
    }

    protected function findIndexById($id)
    {
        foreach ($this->data['router'] as $index => $data) {
            if ($data['id'] == $id) {
                return $index;
            }
        }
        return false;
    }

    public function setActive($id)
    {
        $index = $this->findIndexById($id);
        if ($index !== false) {
            $this->data['router'][$index]['last_active'] = date('Y-m-d H:i:s');
            $this->save();
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(["status" => true, "message" => "Data dengan ID $id berhasil diaktifkan!"]);
        } else {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(["status" => false, "message" => "Data dengan ID $id tidak ditemukan!"]);
        }
    }

    public function setNonactive($id)
    {
        $index = $this->findIndexById($id);
        if ($index !== false) {
            $this->data['router'][$index]['last_active'] = null;
            $this->save();
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(["status" => true, "message" => "Data dengan ID $id berhasil dinonaktifkan!"]);
        } else {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(["status" => false, "message" => "Data dengan ID $id tidak ditemukan!"]);
        }
    }
}


$api = new Api();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'store') {
        if (isset($_POST['name']) && isset($_POST['ip'])) {
            $data['name'] = htmlspecialchars($_POST['name']);
            $data['ip'] = htmlspecialchars($_POST['ip']);
            $data['last_active'] = null;
            $data['id'] = uniqid();
            $api->store($data);
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(["status" => false, "message" => "Data yang diperlukan tidak lengkap!"]);
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update') {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $data['name'] = htmlspecialchars($_POST['name']);
            $data['ip'] = htmlspecialchars($_POST['ip']);
            $api->update($id, $data);
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(["status" => false, "message" => "Data Tidak lengkap!"]);
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $api->delete($id);
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(["status" => false, "message" => "Data Tidak lengkap!"]);
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'active') {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $api->setActive($id);
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(["status" => false, "message" => "Data Tidak lengkap!"]);
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'nonactive') {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $api->setNonactive($id);
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(["status" => false, "message" => "Data Tidak lengkap!"]);
        }
    } else {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(["status" => false, "message" => "Invalid Action!"]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] == 'show') {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $api->show($id);
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(["status" => false, "message" => "Data Tidak lengkap!"]);
        }
    } else {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(["status" => false, "message" => "Invalid Action!"]);
    }
}
