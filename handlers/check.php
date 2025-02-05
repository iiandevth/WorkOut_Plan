<?php
include 'helpers/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $completed = intval($_POST['completed']);

    $query = "UPDATE workouts SET completed = $completed WHERE id = $id";
    mysqli_query($conn, $query);

    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}
?>
