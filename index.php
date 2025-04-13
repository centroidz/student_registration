<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 30px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #343a40;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .modal-header {
            background-color: #007bff;
            color: white;
        }
        .modal-footer .btn {
            background-color: #007bff;
            border-color: #007bff;
        }
        .modal-footer .btn:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        table {
            margin-top: 20px;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        td {
            vertical-align: middle;
        }
        /* Custom alert box styles */
        .alert-box {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px solid #007bff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            padding: 20px;
            text-align: center;
        }
        .alert-box button {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mt-5">Student Registration</h2>
    <button class="btn btn-primary" data-toggle="modal" data-target="#studentModal" onclick="clearForm()">Add Student</button>

    <h2 class="mt-5">Registered Students</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Course</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="studentList">
                <?php
                include 'db.php';
                $sql = "SELECT * FROM students";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr id='student-{$row['id']}'>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['phone']}</td>
                                <td>{$row['course']}</td>
                                <td>
                                    <button class='btn btn-warning' onclick='editStudent({$row['id']}, \"{$row['name']}\", \"{$row['email']}\", \"{$row['phone']}\", \"{$row['course']}\")'>Edit</button>
                                    <button class='btn btn-danger' onclick='confirmDelete({$row['id']})'>Delete</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No students registered.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentModalLabel">Student Registration</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="registrationForm" action="register.php" method="POST">
                    <input type="hidden" id="studentId" name="studentId" value="">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="course">Course:</label>
                        <input type="text" class="form-control" id="course" name="course" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitForm" onclick="submitForm()">Register</button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Alert Box -->
<div class="alert-box" id="alertBox">
    <p>Are you sure you want to delete this student?</p>
    <button class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
    <button class="btn btn-secondary" id="cancelDeleteBtn">Cancel</button>
</div>

<script>
let studentIdToDelete = null;

function clearForm() {
    document.getElementById('studentId').value = '';
    document.getElementById('name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('phone').value = '';
    document.getElementById('course').value = '';
    document.getElementById('studentModalLabel').innerText = 'Student Registration';
}

function editStudent(id, name, email, phone, course) {
    document.getElementById('studentId').value = id;
    document.getElementById('name').value = name;
    document.getElementById('email').value = email;
    document.getElementById('phone').value = phone;
    document.getElementById('course').value = course;
    document.getElementById('studentModalLabel').innerText = 'Edit Student';
    document.getElementById('submitForm').innerText = 'Save Changes';
    $('#studentModal').modal('show'); // Show the modal
}

function submitForm() {
    document.getElementById('registrationForm').submit();
}

function confirmDelete(id) {
    studentIdToDelete = id; // Store the ID of the student to delete
    document.getElementById('alertBox').style.display = 'block'; // Show the alert box
}

document.getElementById('confirmDeleteBtn').onclick = function() {
    if (studentIdToDelete) {
        window.location.href = 'delete.php?id=' + studentIdToDelete; // Redirect to delete
    }
};

document.getElementById('cancelDeleteBtn').onclick = function() {
    document.getElementById('alertBox').style.display = 'none'; // Hide the alert box
};
</script>
</body>
</html>