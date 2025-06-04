<?php
// Include the database connection file
require_once("../db_inc2.php");

// Include the Notification class
require_once("Notification.php");

// Create an instance of the Notification class, passing the $dbonline connection
$notification = new Notification($dbonline);

// Fetch all notifications for a specific user
$receiver_id = 211; // ID of the user receiving the notifications
$notifications = $notification->getNotifications($receiver_id);

// Fetch only unread notifications
$unreadNotifications = $notification->getNotifications($receiver_id, false);

// Display notifications
foreach ($notifications as $note) {
    echo "Message: " . $note['message'] . "<br>";
    echo "Sender: " . ($note['sender_name'] ?? 'System') . "<br>";
    echo "Read: " . ($note['is_read'] ? 'Yes' : 'No') . "<br>";
    echo "Created At: " . $note['created_at'] . "<br><br>";
}
?>
