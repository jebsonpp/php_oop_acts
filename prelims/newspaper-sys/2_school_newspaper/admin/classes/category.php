<?php
require_once 'Database.php'; // Make sure this extends your PDO Database class

class Category extends Database {

    // Get all categories
    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        return $this->executeQuery($sql);
    }

    // Get category by ID
    public function getCategoryById($id) {
        $sql = "SELECT * FROM categories WHERE category_id = ?";
        return $this->executeQuerySingle($sql, [$id]);
    }

    // Create a new category
    public function createCategory($name) {
        $sql = "INSERT INTO categories (name, created_at) VALUES (?, NOW())";
        return $this->executeNonQuery($sql, [$name]);
    }

    // Update a category name
    public function updateCategory($id, $name) {
        $sql = "UPDATE categories SET name = ? WHERE category_id = ?";
        return $this->executeNonQuery($sql, [$name, $id]);
    }

    // Delete a category
    public function deleteCategory($id) {
        $sql = "DELETE FROM categories WHERE category_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>
