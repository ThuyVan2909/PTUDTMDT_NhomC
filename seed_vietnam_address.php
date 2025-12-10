<?php
include 'db.php';

$provJson = file_get_contents("https://api.vnappmob.com/api/v2/province/");
if (!$provJson) {
    die("Không lấy được provinces");
}
$provs = json_decode($provJson, true)['results'];

foreach ($provs as $prov) {
    $province_id = intval($prov['province_id']);
    $province_name = $prov['province_name'];

    $stmt = $conn->prepare("INSERT IGNORE INTO provinces(id,name) VALUES(?,?)");
    $stmt->bind_param("is", $province_id, $province_name);
    $stmt->execute();

    // lấy districts
    $distJson = file_get_contents("https://api.vnappmob.com/api/v2/province/district/".$province_id);
    $dists = json_decode($distJson, true)['results'];
    foreach ($dists as $dist) {
        $district_id = intval($dist['district_id']);
        $district_name = $dist['district_name'];

        $stmt = $conn->prepare("INSERT IGNORE INTO districts(id,province_id,name) VALUES(?,?,?)");
        $stmt->bind_param("iis", $district_id, $province_id, $district_name);
        $stmt->execute();
    }
}

echo "Seed provinces + districts hoàn tất.";
