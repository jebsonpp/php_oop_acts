<?php
require_once __DIR__ . '/../config/Database.php';
class ExcuseLetter {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Submit new excuse letter
    public function submitExcuse($student_id, $program, $letter_text, $attachment = null) {
        $sql = "INSERT INTO excuse_letters (student_id, program, letter_text, attachment) 
                VALUES (:student_id, :program, :letter_text, :attachment)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":student_id", $student_id);
        $stmt->bindParam(":program", $program);
        $stmt->bindParam(":letter_text", $letter_text);
        $stmt->bindParam(":attachment", $attachment);
        return $stmt->execute();
    }

    // Get all excuse letters by student
    public function getStudentExcuses($student_id) {
        $sql = "SELECT * FROM excuse_letters WHERE student_id = :student_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":student_id", $student_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all excuse letters (for admin), optionally filter by program
public function getAllExcuses($programFilter = null) {
    $sql = "SELECT e.id, e.letter_text, e.attachment, e.status, e.created_at,
                   u.full_name AS student_name,
                   c.course_name AS student_program
            FROM excuse_letters e
            JOIN students s ON e.student_id = s.id
            JOIN users u ON s.user_id = u.id
            LEFT JOIN courses c ON s.course_id = c.id";

    if ($programFilter) {
        $sql .= " WHERE c.course_name LIKE :program";
    }

    $stmt = $this->conn->prepare($sql);

    if ($programFilter) {
        $stmt->bindValue(':program', "%$programFilter%");
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    // Approve or reject excuse letter
    public function updateStatus($id, $status) {
        $sql = "UPDATE excuse_letters SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Get single excuse letter
    public function getExcuseById($id) {
        $sql = "SELECT * FROM excuse_letters WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
