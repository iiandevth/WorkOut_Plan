<?php 
include 'helpers/not_authenticated.php';
include 'helpers/config.php';
include 'handlers/gender_goal.php';

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_workout'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $query = "INSERT INTO workouts (title, image, description) VALUES ('$title', '$image', '$description')";
    mysqli_query($conn, $query);
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM workouts WHERE id=$id");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FITNESS PLANS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container py-5">
        <div class="text-center mb-4">
            <h1 class="fw-bold">WORKOUT PLANS</h1>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label for="goal" class="form-label">Goal:</label>
                <select id="goal" class="form-select" name="goal">
                    <?php
                        foreach (getGoalOptions() as $goal) {
                            echo "<option value='$goal'>$goal</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="gender" class="form-label">Gender:</label>
                <select id="gender" class="form-select" name="gender">
                    <?php
                        foreach (getGenderOptions() as $gender) {
                            echo "<option value='$gender'>$gender</option>";
                        }
                    ?>
                </select>
            </div>
        </div>

        <!-- Add Workout Form -->
        <div class="mb-4">
            <form method="POST">
                <input type="text" name="title" class="form-control mb-2" placeholder="Workout Title" required>
                <input type="text" name="image" class="form-control mb-2" placeholder="Enter Image URL (Example Picture of Work Out)" required>
                <input type="text" name="description" class="form-control mb-2" placeholder="Work Out Description" required>
                <button type="submit" name="add_workout" class="btn btn-success">Add Workout</button>
            </form>
        </div>

        <div class="row g-4">
    <?php 
        $result = mysqli_query($conn, "SELECT * FROM workouts");
        while ($workout = mysqli_fetch_assoc($result)) {
            $checked = $workout['completed'] ? "checked" : "";
            echo "<div class='col-md-4'>
                    <div class='card shadow-lg'>
                        <img src='" . htmlspecialchars($workout['image']) . "' class='card-img-top' alt='" . htmlspecialchars($workout['title']) . "' onerror='this.onerror=null; this.src=\"default-image.jpg\";'>
                        <div class='card-body text-center'>
                            <h5 class='card-title'>" . htmlspecialchars($workout['title']) . "</h5>
                            <p class='card-text'>" . htmlspecialchars($workout['description']) . "</p>
                            <div class='form-check'>
                                <input type='checkbox' class='form-check-input workout-check' data-id='" . $workout['id'] . "' $checked>
                                <label class='form-check-label'>Completed</label>
                            </div>
                            <a href='update.php?id=" . $workout['id'] . "' class='btn btn-warning'>Edit</a>
                            <a href='?delete=" . $workout['id'] . "' class='btn btn-danger'>Delete</a>
                        </div>
                    </div>
                  </div>";
        }
    ?>
</div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".workout-check").forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            let workoutId = this.getAttribute("data-id");
            let completed = this.checked ? 1 : 0;

            fetch("update_status.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id=" + workoutId + "&completed=" + completed
            })
            .then(response => response.json())
            .then(data => console.log(data))
            .catch(error => console.error("Error:", error));
        });
    });
});
</script>

</body>
</html>
