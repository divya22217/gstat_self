<?php
// Generate RSA keys (this would typically be done once and stored securely)
$res = openssl_pkey_new([
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
]);

// Initialize the $privateKey variable
$privateKey = "";

// Export the private key to the $privateKey variable
if (openssl_pkey_export($res, $privateKey)) {
    // Extract the public key from the generated key pair
    $publicKeyDetails = openssl_pkey_get_details($res);
    $publicKey = $publicKeyDetails['key'];

    // Save the private key and public key to files
    file_put_contents("private_key.pem", $privateKey);
    file_put_contents("public_key.pem", $publicKey);

    echo "RSA keys have been generated and saved successfully!";
} else {
    echo "Failed to export private key: " . openssl_error_string();
}
?>
