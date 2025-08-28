<?php
// Database Class: Superclass for CRUD operations using PDO
class Database {
    protected $pdo;
    protected $table;

    public function __construct($host = 'localhost', $dbname = 'school', $username = 'root', $password = '') {
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function create($data) {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(',:', array_keys($data));
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function read($criteria = []) {
        $sql = "SELECT * FROM {$this->table}";
        if (!empty($criteria)) {
            $conditions = [];
            foreach ($criteria as $column => $value) {
                $conditions[] = "{$column} = :{$column}";
            }
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($criteria);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $setParts = [];
        foreach ($data as $key => $value) {
            $setParts[] = "{$key} = :{$key}";
        }
        $setString = implode(', ', $setParts);
        $sql = "UPDATE {$this->table} SET {$setString} WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}

// Student Class inheriting from Database
class Student extends Database {
    protected $table = 'students';

    public function __construct($host = 'localhost', $dbname = 'school', $username = 'root', $password = '') {
        parent::__construct($host, $dbname, $username, $password);
    }
}

// Attendance Class inheriting from Database
class Attendance extends Database {
    protected $table = 'attendance';

    public function __construct($host = 'localhost', $dbname = 'school', $username = 'root', $password = '') {
        parent::__construct($host, $dbname, $username, $password);
    }
}

// Instantiate objects for student and attendance CRUD operations
$studentObj = new Student();
$attendanceObj = new Attendance();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';

    if ($type === 'student') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action === 'create') {
                $data = [
                    'name' => $_POST['name'],
                    'age'  => $_POST['age']
                ];
                if ($studentObj->create($data)) {
                    $message = "Student record created successfully.";
                }
            } elseif ($action === 'update') {
                $id = $_POST['id'];
                $data = [
                    'name' => $_POST['name'],
                    'age'  => $_POST['age']
                ];
                if ($studentObj->update($id, $data)) {
                    $message = "Student record updated successfully.";
                }
            } elseif ($action === 'delete') {
                $id = $_POST['id'];
                if ($studentObj->delete($id)) {
                    $message = "Student record deleted successfully.";
                }
            }
        }
    } elseif ($type === 'attendance') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action === 'create') {
                $data = [
                    'student_id' => $_POST['student_id'],
                    'date'       => $_POST['date'],
                    'status'     => $_POST['status']
                ];
                if ($attendanceObj->create($data)) {
                    $message = "Attendance record created successfully.";
                }
            } elseif ($action === 'update') {
                $id = $_POST['id'];
                $data = [
                    'student_id' => $_POST['student_id'],
                    'date'       => $_POST['date'],
                    'status'     => $_POST['status']
                ];
                if ($attendanceObj->update($id, $data)) {
                    $message = "Attendance record updated successfully.";
                }
            } elseif ($action === 'delete') {
                $id = $_POST['id'];
                if ($attendanceObj->delete($id)) {
                    $message = "Attendance record deleted successfully.";
                }
            }
        }
    }
}

// Fetch records for display
$students = $studentObj->read();
$attendances = $attendanceObj->read();
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Operations</title>
    <style>
        form { margin-bottom: 20px; border: 1px solid #ccc; padding: 10px; width: 300px; }
        .container { display: flex; flex-wrap: wrap; gap: 20px; }
    </style>
</head>
<body>
    <h1>CRUD Operations</h1>
    <?php if ($message): ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php endif; ?>

    <div class="container">
        <!-- Student Create Form -->
        <div>
            <h2>Create Student</h2>
            <form method="post" action="">
                <input type="hidden" name="type" value="student">
                <input type="hidden" name="action" value="create">
                <label>Name:</label><br>
                <input type="text" name="name" required><br>
                <label>Age:</label><br>
                <input type="number" name="age" required><br><br>
                <input type="submit" value="Create Student">
            </form>
        </div>

        <!-- Student Update Form -->
        <div>
            <h2>Update Student</h2>
            <form method="post" action="">
                <input type="hidden" name="type" value="student">
                <input type="hidden" name="action" value="update">
                <label>ID:</label><br>
                <input type="number" name="id" required><br>
                <label>Name:</label><br>
                <input type="text" name="name" required><br>
                <label>Age:</label><br>
                <input type="number" name="age" required><br><br>
                <input type="submit" value="Update Student">
            </form>
        </div>

        <!-- Student Delete Form -->
        <div>
            <h2>Delete Student</h2>
            <form method="post" action="">
                <input type="hidden" name="type" value="student">
                <input type="hidden" name="action" value="delete">
                <label>ID:</label><br>
                <input type="number" name="id" required><br><br>
                <input type="submit" value="Delete Student">
            </form>
        </div>

        <!-- Attendance Create Form -->
        <div>
            <h2>Create Attendance</h2>
            <form method="post" action="">
                <input type="hidden" name="type" value="attendance">
                <input type="hidden" name="action" value="create">
                <label>Student ID:</label><br>
                <input type="number" name="student_id" required><br>
                <label>Date (YYYY-MM-DD):</label><br>
                <input type="date" name="date" required><br>
                <label>Status:</label><br>
                <select name="status" required>
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                </select><br><br>
                <input type="submit" value="Create Attendance">
            </form>
        </div>

        <!-- Attendance Update Form -->
        <div>
            <h2>Update Attendance</h2>
            <form method="post" action="">
                <input type="hidden" name="type" value="attendance">
                <input type="hidden" name="action" value="update">
                <label>ID:</label><br>
                <input type="number" name="id" required><br>
                <label>Student ID:</label><br>
                <input type="number" name="student_id" required><br>
                <label>Date (YYYY-MM-DD):</label><br>
                <input type="date" name="date" required><br>
                <label>Status:</label><br>
                <select name="status" required>
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                </select><br><br>
                <input type="submit" value="Update Attendance">
            </form>
        </div>

        <!-- Attendance Delete Form -->
        <div>
            <h2>Delete Attendance</h2>
            <form method="post" action="">
                <input type="hidden" name="type" value="attendance">
                <input type="hidden" name="action" value="delete">
                <label>ID:</label><br>
                <input type="number" name="id" required><br><br>
                <input type="submit" value="Delete Attendance">
            </form>
        </div>
    </div>

    <!-- Display Student Records -->
    <h2>Student Records</h2>
    <?php if (!empty($students)): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
            </tr>
            <?php foreach ($students as $stu): ?>
            <tr>
                <td><?php echo htmlspecialchars($stu['id']); ?></td>
                <td><?php echo htmlspecialchars($stu['name']); ?></td>
                <td><?php echo htmlspecialchars($stu['age']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No student records found.</p>
    <?php endif; ?>

    <!-- Display Attendance Records -->
    <h2>Attendance Records</h2>
    <?php if (!empty($attendances)): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Student ID</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php foreach ($attendances as $att): ?>
            <tr>
                <td><?php echo htmlspecialchars($att['id']); ?></td>
                <td><?php echo htmlspecialchars($att['student_id']); ?></td>
                <td><?php echo htmlspecialchars($att['date']); ?></td>
                <td><?php echo htmlspecialchars($att['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No attendance records found.</p>
    <?php endif; ?>
</body>
</html>