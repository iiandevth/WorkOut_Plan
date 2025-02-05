<?php 
include 'helpers/not_authenticated.php';
include 'helpers/config.php';

$edit_id = "";
$edit_title = "";
$edit_image = "";
$edit_description = "";

// Load Workout Data for Editing
if (isset($_GET['id'])) {
    $edit_id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM workouts WHERE id=$edit_id");
    if ($workout = mysqli_fetch_assoc($result)) {
        $edit_title = $workout['title'];
        $edit_image = $workout['image'];
        $edit_description = $workout['description'];
    } else {
        echo "Workout not found!";
        exit();
    }
}

// Handle Update Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_workout'])) {
    $id = $_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $query = "UPDATE workouts SET title='$title', image='$image', description='$description' WHERE id=$id";
    mysqli_query($conn, $query);

    header("Location: index.php"); // Redirect to main page
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Workout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="update_style.css">
</head>
<body>
    <div class="container py-5">
        <div class="text-center mb-4">
            <h1 class="fw-bold">Update Workout</h1>
        </div>

        <div class="mb-4">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $edit_id ?>">
                <input type="text" name="title" class="form-control mb-2" placeholder="Workout Title" value="<?= $edit_title ?>" required>
                <input type="text" name="image" class="form-control mb-2" placeholder="Image URL" value="<?= $edit_image ?>" required>
                <input type="text" name="description" class="form-control mb-2" placeholder="Description" value="<?= $edit_description ?>" required>
                <button type="submit" name="update_workout" class="btn btn-primary">Update Workout</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
