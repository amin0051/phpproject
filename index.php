<?php
$host = 'localhost';
$db   = 'crud';
$user = 'root';
$pass = ''; // Replace with your password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Variable to store messages
$message = '';
// Variable to store data for editing
$student_to_edit = null;



if (isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];

    $sql = "INSERT INTO students (name, email, course) VALUES (:name, :email, :course)";
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters and execute

    
    if ($stmt->execute([':name' => $name, ':email' => $email, ':course' => $course])) {
        $message = "Student **added** successfully!";
    } else {
        $message = "Error adding student.";
    }
}


if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $sql = "SELECT * FROM students WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $student_to_edit = $stmt->fetch();
}




if (isset($_POST['update_student'])) {
    $id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];

    $sql = "UPDATE students SET name = :name, email = :email, course = :course WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([':name' => $name, ':email' => $email, ':course' => $course, ':id' => $id])) {
        $message = "Student **updated** successfully!";
        // Redirect to clear URL parameters
        header("Location: index.php"); 
        exit();
    } else {
        $message = "Error updating student.";
    }
}


if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM students WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([':id' => $id])) {
        $message = "Student **deleted** successfully!";
    } else {
        $message = "Error deleting student.";
    }
    // Redirect to clear URL parameter
    header("Location: index.php"); 
    exit();
}

// Fetch all students for display
$stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
$students = $stmt->fetchAll();





?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP PDO Single-Page CRUD</title>
</head>
<body>
    <h1>Student Management System</h1>

    <?php if ($message): ?>
        <p style="color: green; font-weight: bold;"><?= $message ?></p>
    <?php endif; ?>
    
    <h2><?= $student_to_edit ? 'Edit Student' : 'Add New Student' ?></h2>

    <form method="POST">

        <?php if ($student_to_edit): ?>
            <input type="hidden" name="student_id" value="<?= $student_to_edit['id'] ?>">
        <?php endif; ?>
        
        <label>Name:</label>
        <input type="text" name="name" value="<?= $student_to_edit['name'] ?? '' ?>" required><br><br>
        
        <label>Email:</label>
        <input type="email" name="email" value="<?= $student_to_edit['email'] ?? '' ?>" required><br><br>
        
        <label>Course:</label>
        <input type="text" name="course" value="<?= $student_to_edit['course'] ?? '' ?>"><br><br>
        
        <button type="submit" name="<?= $student_to_edit ? 'update_student' : 'add_student' ?>">
            <?= $student_to_edit ? 'Update Student' : 'Add Student' ?>
        </button>
        
        <?php if ($student_to_edit): ?>
            <a href="index.php">Cancel Edit</a>
        <?php endif; ?>

    </form>
    
    <hr>

    <h2>Student List</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($students) > 0): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= $student['id'] ?></td>
                        <td><?= htmlspecialchars($student['name']) ?></td>
                        <td><?= htmlspecialchars($student['email']) ?></td>
                        <td><?= htmlspecialchars($student['course']) ?></td>
                        <td>
                            <a href="?edit_id=<?= $student['id'] ?>">Edit</a> | 
                            <a href="?delete_id=<?= $student['id'] ?>" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

