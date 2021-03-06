<?php

include '../phpseclib/vendor/autoload.php';

$plaintext = 'Something very secret.';
$password = 'VdcpDTWTc5Aehxgv2uL9haaFddDBhrc8uCMG3ykg';

$ivSize = 8;

$randomIV = phpseclib\Crypt\Random::string($ivSize);

echo 'Plaintext: ' . $plaintext . "\r\n";

//Create new DES object for encryption
$des_encrypt = new \phpseclib\Crypt\DES(\phpseclib\Crypt\DES::MODE_CBC);
//Set preferred engine to OPENSSL
$des_encrypt->setPreferredEngine(phpseclib\Crypt\DES::ENGINE_OPENSSL);
//set key length to 256
$des_encrypt->setKeyLength(64);
//set pbkdf2 with sha512 and 4096 iterations as default password hashing method
$des_encrypt->setPassword($password, 'pbkdf2', 'sha512', NULL, 4096);

$des_encrypt->setIV($randomIV);

$ciphertext_raw = $des_encrypt->encrypt($plaintext);

echo 'Ciphertext(RAW): ' . $ciphertext_raw . "\r\n";

$ciphertext = base64_encode($randomIV . $ciphertext_raw);

echo 'Ciphertext(base64): ' . $ciphertext . "\r\n";

//Create new DES object for decryption
$des_decrypt = new phpseclib\Crypt\DES(phpseclib\Crypt\DES::MODE_CBC);
//set OPENSSL as preferred engine
$des_decrypt->setPreferredEngine(phpseclib\Crypt\DES::ENGINE_OPENSSL);
//set key length to 256
$des_decrypt->setKeyLength(64);
//set pbkdf2 with sha512 and 4096 iterations as default password hashing method
$des_decrypt->setPassword($password, 'pbkdf2', 'sha512', NULL, 4096);

$ciphertext_decoded = base64_decode($ciphertext);

$des_decrypt->setIV(substr($ciphertext_decoded, 0, $ivSize));

//decode from base64 and decrypt
$decrypted = $des_decrypt->decrypt(substr($ciphertext_decoded, $ivSize));

echo 'Decrypted: ' . $decrypted . "\r\n";

//is everything ok?
var_dump($plaintext == $decrypted);
