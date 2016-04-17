<?php


// 这里是我们上面得到的deviceToken，直接复制过来（记得去掉空格）
//9cacd09361c5d24fcda942a60c8a17b3e882f6f7d707e1022d5b230be1e6d2e5

$deviceToken = '9a8d7ea36d4fa2843edd4884db4b00063b5428491e3a0659def028f895da80cd';
//$deviceToken = 'f7806d0a533cccfeedc99849ab85a4aaeac8d455b2bc98a397cdb8472e05baa8';


// Put your private key's passphrase here:




// Put your alert message here:

$message = 'coco222 '.date('Y-m-d H:i',time());


////////////////////////////////////////////////////////////////////////////////


$ctx = stream_context_create();

stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck_ciq_new.pem');
$passphrase = '';


//$passphrase = '123456';
//stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck_030401.pem');

stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);


// Open a connection to the APNS server

//这个为正是的发布地址

//$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);

//这个是沙盒测试地址，发布到appstore后记得修改哦

$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
		
if (!$fp)

exit("Failed to connect: $err $errstr" . PHP_EOL);


echo 'Connected to APNS' . PHP_EOL;

$info = array('name'=>'你好','content'=>'你好你好你好你你好你好你好你你好');

// Create the payload body
$body['userinfo'] = array('index' =>1,'info'=>$info);
$body['aps'] = array(
'alert' => $message,
'badge' => 1,
'sound' => 'default'

);


// Encode the payload as JSON

$payload = json_encode($body);
//print_r($payload);

// Build the binary notification

$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;


// Send it to the server

$result = fwrite($fp, $msg, strlen($msg));


if (!$result)

echo 'Message not delivered' . PHP_EOL;

else

echo 'Message successfully delivered' . PHP_EOL;


// Close the connection to the server

fclose($fp);

?>