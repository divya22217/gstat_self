<?php
// Get the user under which Apache is running
$apacheUser = exec('whoami');

// Print the Apache user
echo "Apache user: " . $apacheUser;
?>
