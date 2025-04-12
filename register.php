<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentId = $_POST['studentId'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];

    if (empty($studentId)) {
        // Insert new student
        $sql = "INSERT INTO students (name, email, phone, course) VALUES ('$name', '$email', '$phone', '$course')";
    } else {
        // Update existing student
        $sql = "UPDATE students SET name='$name', email='$email', phone='$phone', course='$course' WHERE id='$studentId'";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // Redirect to the homepage
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>