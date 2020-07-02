<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 22/11/18
 * Time: 10:01
 */

namespace Encryption;

class Encryption
{
    private $key;
    private $cipher;

    public function __construct($key, $cipher)
    {
        $this->key = $key;
        $this->cipher = $cipher;
    }

    //Função de Encriptação

    public function encript($textToEncript) {

        $ivlen = openssl_cipher_iv_length($this->cipher);

        $iv = openssl_random_pseudo_bytes($ivlen);

        $ciphertext_raw = openssl_encrypt($textToEncript, $this->cipher, $this->key, $options = OPENSSL_RAW_DATA, $iv);

        $hmac = hash_hmac('sha256', $ciphertext_raw, $this->key, $as_binary = true);

        $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);

        return $ciphertext;

    }

    //Função de Decriptação

    public function decript($textToDecript) {

        $c = base64_decode($textToDecript);

        $ivlen = openssl_cipher_iv_length($this->cipher);

        $iv = substr($c, 0, $ivlen);

        $hmac = substr($c, $ivlen, $sha2len = 32);

        $ciphertext_raw = substr($c, $ivlen + $sha2len);

        $original_plaintext = openssl_decrypt($ciphertext_raw, $this->cipher, $this->key, $options = OPENSSL_RAW_DATA, $iv);

        $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->key, $as_binary = true);

        if (hash_equals($hmac, $calcmac))
            return $original_plaintext;
        else
            return "decript error!";
    }

    public function objectEncrypt($object)
    {
        foreach ($object as $key => $value){
            $object->{$key} = $this->encript($value);
        }

        return $object;
    }

    public function objectDencrypt($object)
    {
        foreach ($object as $key => $value){
            $object->{$key} = $this->decript($value);
        }

        return $object;
    }
    
    public function encriptSalt($textToDecript)
    {
        return base64_encode($this->key.$textToDecript);
    }

    public function decriptSalt($textToDecript)
    {
        $textToDecript = base64_decode($textToDecript);

        $salt = substr($textToDecript, 0, strlen($this->key));

        if ($salt != $this->key)
            throw new \Exception('Invalid salt encrypt');

        return substr($textToDecript,  strlen($this->key) - 1, strlen($textToDecript));
    }
}
