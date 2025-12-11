<?php
include 'db.php';

// Kiểm tra kết nối API
function getAPI($url) {
    $res = file_get_contents($url);
    if ($res === false) {
        die("Lỗi gọi API: " . $url);
    }
    return json_decode($res, true);
}

// 1. Lấy provinces
$provData = getAPI("https://api.vnappmob.com/api/v2/province/");

if (!isset($provData['results'])) {
    die("API provinces trả về sai format");
}

foreach ($provData['results'] as $prov) {

    $province_id   = intval($prov['province_id']);
    $province_name = $prov['province_name'];

    // Insert province
    $stmt = $conn->prepare("INSERT IGNORE INTO provinces (id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $province_id, $province_name);
    $stmt->execute();

    // 2. Lấy districts theo province
    $distData = getAPI("https://api.vnappmob.com/api/v2/province/district/" . $province_id);

    if (isset($distData['results'])) {
        foreach ($distData['results'] as $dist) {

            $district_id   = intval($dist['district_id']);
            $district_name = $dist['district_name'];

            $stmt = $conn->prepare("
                INSERT IGNORE INTO districts (id, province_id, name) 
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("iis", $district_id, $province_id, $district_name);
            $stmt->execute();
        }
    }
}

echo "Seed provinces + districts hoàn tất.";
?>
