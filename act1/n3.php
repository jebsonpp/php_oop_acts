<?php

class Student
{
    public $name;
    public $age;
    public $courses;

    public function __construct($name, $age, $courses = []) {
        $this->name = $name;
        $this->age = $age;
        $this->courses = $courses;
    }

    public function addCourse($course) {
        $this->courses[] = $course;
    }

    // This method removes a course by name if it exists
    public function deleteCourse($course) {
        $index = array_search($course, $this->courses);
        if ($index !== false) {
            unset($this->courses[$index]);
            // reindex the courses array
            $this->courses = array_values($this->courses);
        }
    }

    public function calculateTotalTuition($tuitionPerCourse) {
        return count($this->courses) * $tuitionPerCourse;
    }
}

// Create a new Student and simulate enrollment
$student = new Student('John Doe', 20);

// Add some courses
$student->addCourse('Mathematics');
$student->addCourse('English');
$student->addCourse('History');

// Remove a course
$student->deleteCourse('English');

// Define cost per course
$tuitionPerCourse = 1450;

// Calculate the total tuition (enrollment fee)
$totalFee = $student->calculateTotalTuition($tuitionPerCourse);

// Display the result
echo "Total enrollment fee is PHP " . $totalFee;
?>