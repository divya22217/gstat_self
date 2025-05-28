<?php include('header.php');?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP DataTable</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
    </style>
</head>
<body>

<h2 style="margin-top:160px;">Notifications</h2>
<table id="notifications" class="display" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Message</th>
            <th>Is Read</th>
            <th>Created At</th>
        </tr>
    </thead>
</table>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#notifications').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "Notification.php?function=allNotification",
        "columns": [
            { "data": "id" },
            { "data": "type" },
            { "data": "message" },
            { "data": "is_read" },
            { "data": "created_at" }
        ]
    });
});
</script>
</body>
</html>
