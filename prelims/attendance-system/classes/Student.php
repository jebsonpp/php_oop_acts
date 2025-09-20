<?php
require_once 'User.php';

class Student extends User {

    // ✅ Assign a user to a student profile
    public function addStudent($user_id, $course_id, $year_level) {
        $sql = "INSERT INTO students (user_id, course_id, year_level) VALUES (?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$user_id, $course_id, $year_level]);
    }

    // ✅ Record attendance
    public function recordAttendance($student_id, $is_late) {
        $sql = "INSERT INTO attendance (student_id, is_late, date, time_in) 
                VALUES (?,?,CURDATE(), CURTIME())";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$student_id, $is_late]);
    }

    // ✅ Student’s attendance history
    public function getHistory($student_id) {
        $sql = "SELECT a.date, a.time_in, a.is_late, a.status 
                FROM attendance a 
                WHERE a.student_id=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Admin: Get all students with their assigned course
    public function getAllStudents() {
        $sql = "SELECT s.id, u.username, u.full_name, c.course_name, s.year_level
                FROM students s
                JOIN users u ON s.user_id = u.id
                LEFT JOIN courses c ON s.course_id = c.id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Admin: Get registered users who are not yet in `students` table
    public function getUnassignedStudents() {
        $sql = "SELECT u.id, u.username, u.full_name 
                FROM users u
                WHERE u.role = 'student' 
                AND u.id NOT IN (SELECT user_id FROM students)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Admin: Get list of courses
    public function getCourses() {
        $sql = "SELECT * FROM courses";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Admin: Update student’s course & year level
    public function updateStudent($student_id, $course_id, $year_level) {
        $sql = "UPDATE students SET course_id=?, year_level=? WHERE id=?";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$course_id, $year_level, $student_id]);
    }

    // ✅ Fix: Use connect() not conn
    public function getStudentById($id) {
        $sql = "SELECT * FROM students WHERE id = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Fix: Use connect() not conn
    public function getStudentByUserId($userId) {
        $sql = "SELECT * FROM students WHERE user_id = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
