<?php
// Include the database connection file
require_once("../db_inc2.php");

// Include the Notification class
require_once("Notification.php");

// Create an instance of the Notification class, passing the $dbonline connection
$notification = new Notification($dbonline);

// Insert a new notification
$receiver_id = 81; // ID of the user receiving the notification
$sender_id = 194; // ID of the user sending the notification
$filing_no = 'DEF456';
$schema_id = 10;
$type = 'message2';
$message = 'User234 sent you a message2';
$data = ['message_id' => 789];

if ($notification->insertNotification($receiver_id, $sender_id, $filing_no, $schema_id, $type, $message, $data)) {
    echo "Notification added successfully!";
} else {
    echo "Failed to add notification.";
}
?>
