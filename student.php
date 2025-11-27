<?php


include 'database.php';


// insert data

if(isset($_POST['add_student'])){

    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $course = $_POST['course'];

    $sql  = "INSERT INTO students (name , email , course) VALUES (:name , :email , :course)";
    $stmt =  $pdo->prepare($sql);
    $stmt->bindParam(':name' , $name);
    $stmt->bindParam(':email'  , $email);
    $stmt->bindParam(':course', $course);
    $stmt->execute();

}


// single data face

if(isset($_GET['edit_id'])){

    $id = $_GET['edit_id'];

    $sql = "SELECT * FROM students WHERE id = :id";
    $stmt =$pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $student_edit  =  $stmt->fetch();
}


// upadate data

if(isset($_POST['update_student'])){

     $id = $_POST['student_id'];
     $name = $_POST['name'];
     $email = $_POST['email'];
     $course = $_POST['course'];

     $sql = "UPDATE students SET name = :name , email = :email, course = :course WHERE id = :id";
     $stmt = $pdo->prepare($sql);
     $stmt->bindParam(':id' , $id);
     $stmt->bindParam(':name' , $name);
     $stmt->bindParam(':email', $email);
     $stmt->bindParam(':course', $course);
     $stmt->execute();
}


// delete data;

if(isset($_POST['delete_id'])){

    $id = $_POST['delete_id'];

    $sql = "DELETE FROM students";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id' , $id);
    $stmt->execute();
     
}



// read data

$sql = "SELECT * FROM students";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$students = $stmt->fetchAll();




?>






<h1>Add Studnt</h1>

<form  action="student.php" method="POST">

<label>Name:</label>
<input type="text" name="name" ><br><br>

<label>Email:</label>
<input type="email" name="email" ><br><br>

<label>Course:</label>
<input type="text" name="course" ><br><br>

<button type="submit" name="add_student">Add Student</button>

</form>


<h1>Edit Studetn</h1>


<form  action="student.php" method="POST">
<input type="hidden" name="student_id" value="<?php echo $student_edit['id'] ?>">
<label>Name:</label>
<input type="text" name="name" value="<?php echo $student_edit['name'] ?>" ><br><br>

<label>Email:</label>
<input type="email" name="email" value="<?php echo $student_edit['email'] ?>" ><br><br>

<label>Course:</label>
<input type="text" name="course" value="<?php echo $student_edit['course'] ?>" ><br><br>

<button type="submit" name="update_student">Update Student</button>

</form>









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
           
            <?php foreach($students as $student){ ?>


                <tr>
                    <td><?php  echo $student['id'] ?></td>
                    <td><?php  echo $student['name'] ?></td>
                    <td><?php  echo $student['email'] ?></td>
                    <td><?php echo $student['course'] ?></td>
                    <td>
                        <a href="?edit_id=<?php echo $student['id'] ?>">Edit</a>
                        <a href="?delete_id=<?php echo $student['id'] ?>">Delete</a>
                    </td>
                </tr>
               

            <?php }  ?>
          
        </tbody>
    </table>


