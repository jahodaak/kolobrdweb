<?php
include 'common.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'], $_POST['room_id'])) {
    $message = htmlspecialchars($_POST['message']);
    $room_id = intval($_POST['room_id']);
    $user_id = getUserId(getUser());

    $stmt = $conn->prepare("INSERT INTO messages (room_id, user_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $room_id, $user_id, $message);
    $stmt->execute();
    $stmt->close();

    header('Location: chat.php?room_id=' . $room_id);
    exit();
}
?>
