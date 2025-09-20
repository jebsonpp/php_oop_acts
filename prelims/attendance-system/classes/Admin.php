<?php
require_once 'User.php';

class Admin extends User {
    public function addCourse($course_name) {
        $sql = "INSERT INTO courses (course_name) VALUES (?)";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$course_name]);
    }

    public function viewAttendanceByCourse($course_id, $year_level) {
        $sql = "SELECT u.full_name, s.year_level, c.course_name, a.date, a.time_in, a.is_late, a.status
                FROM attendance a
                JOIN students s ON a.student_id = s.id
                JOIN courses c ON s.course_id = c.id
                JOIN users u ON s.user_id = u.id
                WHERE s.course_id=? AND s.year_level=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$course_id, $year_level]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Method to assign a student to a course
    public function assignCourseToStudent($student_id, $course_id) {
        $sql = "UPDATE students SET course_id = ? WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$course_id, $student_id]);
    }
    
    // New method to assign a user to "student" role and create a student record
    public function assignUserToStudent($user_id, $course_id, $year_level) {
        $conn = $this->connect();
        $conn->beginTransaction();
        try {
            // Update the user's role to student
            $stmt = $conn->prepare("UPDATE users SET role = 'student' WHERE id = ?");
            $stmt->execute([$user_id]);
            
            // Insert a new record into the students table
            $stmt2 = $conn->prepare("INSERT INTO students (user_id, course_id, year_level) VALUES (?, ?, ?)");
            $stmt2->execute([$user_id, $course_id, $year_level]);
            
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }
}
?>