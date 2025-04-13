<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentId = $_POST['studentId'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];

    if (empty($studentId)) {
       
        $sql = "INSERT INTO students (name, email, phone, course) VALUES ('$name', '$email', '$phone', '$course')";
    } else {
       
        $sql = "UPDATE students SET name='$name', email='$email', phone='$phone', course='$course' WHERE id='$studentId'";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>