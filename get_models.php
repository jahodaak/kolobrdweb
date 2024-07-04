<?php
include 'common.php';

if (isset($_GET['manufacturer_id'])) {
    $manufacturer_id = intval($_GET['manufacturer_id']);
    $stmt = $conn->prepare("SELECT id, model_name FROM models WHERE manufacturer_id = ?");
    $stmt->bind_param("i", $manufacturer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $models = [];
    while ($row = $result->fetch_assoc()) {
        $models[] = $row;
    }
    $stmt->close();
    echo json_encode($models);
}
?>
