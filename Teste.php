<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 22/11/18
 * Time: 11:23
 */

use Encryption\Encryption;

require_once __DIR__ . '/vendor/autoload.php';
require_once 'src/Encryption.php';

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

$encryption = new Encryption('teste', 'aes-128-cbc-hmac-sha256');

$testeObj = (object) array(
  'name' => 'Paulo',
  'idade' => 26
);

$obj = $encryption->objectEncrypt($testeObj);
//$obj = $encryption->objectDencrypt($obj);

echo $obj->idade;
//echo $encryption->encript('Paulo');
//echo $encryption->decript('iDWyYKEELPyfmUk0Fzq94IOcnmAgUn/UQ5/n5C2ShVb3Sv3IQHHqI3sKcIyrazf9lR4dyYQszgJnPT0ioMlewQ==');
