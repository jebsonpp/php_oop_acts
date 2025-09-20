<?php

class Notification extends Database {

    /**
     * Inserts a new notification for a user.
     * @param int $user_id
     * @param string $message
     * @return bool
     */
    public function createNotification($user_id, $message) {
        $sql = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
        return $this->executeNonQuery($sql, [$user_id, $message]);
    }

    /**
     * Retrieves notifications for a given user.
     * @param int $user_id
     * @return array
     */
    public function getNotificationsByUserId($user_id) {
        $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    public function notifyEditRequest($author_id, $article_title, $writer_id) {
        $message = "Writer (ID: {$writer_id}) requested an edit on your article: '{$article_title}'.";
        return $this->createNotification($author_id, $message);
    }

    public function notifyEditResponse($writer_id, $article_title, $status) {
        $message = "Your edit request for article '{$article_title}' has been {$status}.";
        return $this->createNotification($writer_id, $message);
    }
}
?>