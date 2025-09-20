<?php
require_once 'Database.php';

class Category extends Database {

    public function __construct() {
        parent::__construct(); // ensure DB connection is initialized
    }

    // Fetch all categories
    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        return $this->executeQuery($sql);
    }

    // Fetch a single category by ID
    public function getCategoryById($id) {
        $sql = "SELECT * FROM categories WHERE category_id = ?";
        return $this->executeQuerySingle($sql, [$id]);
    }

    // Create a new category
    public function createCategory($name) {
        $sql = "INSERT INTO categories (name) VALUES (?)";
        return $this->executeNonQuery($sql, [$name]);
    }

    // Update an existing category
    public function updateCategory($category_id, $name) {
        $sql = "UPDATE categories SET name = ? WHERE category_id = ?";
        return $this->executeNonQuery($sql, [$name, $category_id]);
    }

    // Delete a category
    public function deleteCategory($category_id) {
        $sql = "DELETE FROM categories WHERE category_id = ?";
        return $this->executeNonQuery($sql, [$category_id]);
    }
}
?>
