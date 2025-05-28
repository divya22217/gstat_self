 <?php
echo "My first PHP script!";
echo "<form action='upload_filnal.php' method='post' enctype='multipart/form-data'>
    Select image to upload:
    <input type='file' name='fileToUpload' id='fileToUpload'>
    <input type='submit' value='Upload Image' name='submit'>
</form>";
 
?>