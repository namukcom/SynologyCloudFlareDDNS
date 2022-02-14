#!/usr/bin/php -d open_basedir=/usr/syno/bin/ddns
<?php

if ($argc !== 5) {
    echo 'badparam';
    exit();
}

$account = (string)$argv[1];
$pwd = (string)$argv[2];
$hostname = (string)$argv[3];
$fullname = (string)$argv[3];
$ip = (string)$argv[4];

// check the hostname contains '.'
if (strpos($hostname, '.') === false) {
    echo "badparam";
    exit();
}
if(strlen($pwd) == 37) /* Global key 37byte*/
{
	$header = array("X-Auth-Email: ${account}", "X-Auth-Key: ${pwd}", "Content-Type: application/json");
}
else /* API Token 40byte*/
{
	$header = array("Authorization: Bearer ${pwd}", "Content-Type: application/json");
}

// only for IPv4 format
if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
    echo "badparam";
    exit();
}

/* 
1. Check Validity && Query Zone ID
*/
$url = "https://api.cloudflare.com/client/v4/zones";

$req = curl_init();

$options = array(
	CURLOPT_URL => $url,
	CURLOPT_HTTPGET => true,
	CURLOPT_HEADER => false,
	CURLOPT_VERBOSE => false,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_HTTPHEADER => $header
);

curl_setopt_array($req, $options);
$res = curl_exec($req);
curl_close($req);
$json = json_decode($res, true);

if ('false' != $json['success']) {
	echo 'badauth';
	exit();
}

$domain_total = $json['result_info']['total_count'];

$zoneID = -1;
for ($i = 0; $i < $domain_total; $i++) {
	$domain = (string)$json['result'][$i]['name'];
	if (substr( $hostname, strlen( $hostname ) - strlen( $domain ) ) === $domain){
		$zoneID = $json['result'][$i]['id'];
		break;
	}
}

if ($zoneID === -1) {
    echo 'nohost';
    exit();
}

/*
2. Query Record ID
*/
$url = "https://api.cloudflare.com/client/v4/zones/${zoneID}/dns_records?type=A&name=${fullname}";

$req = curl_init();

$options = array(
	CURLOPT_HTTPGET=>true,
	CURLOPT_URL=>$url,
	CURLOPT_HEADER=>false,
	CURLOPT_VERBOSE=>false,
	CURLOPT_RETURNTRANSFER=>true,
	CURLOPT_HTTPHEADER=> $header
);

curl_setopt_array($req, $options);
$res = curl_exec($req);
curl_close($req);
$json = json_decode($res, true);

if ('false' != $json['success']) {
	echo 'badauth';
	exit();
}

if(1 != $json['result_info']['total_count'])
{
	echo 'nohost';
	exit();
}

$recordID = $json['result'][0]['id'];
$ttl = $json['result'][0]['ttl'];
$proxied = $json['result'][0]['proxied'];

/*
3. Update DNS
*/
$url = "https://api.cloudflare.com/client/v4/zones/${zoneID}/dns_records/$recordID";
$post = array(
    'type'=>'A',
    'name'=>$fullname,
    'content'=>$ip,
    'ttl'=>$ttl,
    'proxied'=>$proxied
	
);

$req = curl_init();

$options = array(
	CURLOPT_URL => $url,
	CURLOPT_HTTPGET => false,
	CURLOPT_CUSTOMREQUEST => "PUT",	
	CURLOPT_HEADER => false,
	CURLOPT_VERBOSE => false,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_HTTPHEADER => $header,
	CURLOPT_POST => false,
	CURLOPT_POSTFIELDS => json_encode($post)
);

curl_setopt_array($req, $options);
$res = curl_exec($req);
curl_close($req);
$json = json_decode($res, true);

if ('false' != $json['success']) {
	echo 'Update Record failed';
	exit();
}

printf("good");
?>
