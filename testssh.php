<?php
require __DIR__ . '/vendor/autoload.php';

use phpseclib\Net\SSH2;

$ssh = new SSH2('01tools.com');
if (!$ssh->login('dashboard', '2HrueAfsVuCv0EyWslzb')) {
    exit('Login Failed');
}

echo $ssh->exec('pwd');
echo $ssh->exec('ls -la');
?>