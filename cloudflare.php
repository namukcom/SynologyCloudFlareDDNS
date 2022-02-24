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
$options = array(
    CURLOPT_URL => "https://api.cloudflare.com/client/v4/zones",
    CURLOPT_HTTPGET => true,
    CURLOPT_HEADER => false,
    CURLOPT_VERBOSE => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $header
);

if (empty($data = exec_curl($options))) {
    echo 'badauth';
    exit();
}

$zone_id = -1;
$result = array_filter(array_get($data, 'result', []), function($row) use ($hostname) {
    return preg_match('/\.'.$row['name'].'$/i', $hostname) > 0 || strtolower($row['name']) === strtolower($hostname);
});

if (empty($zone_info = array_pop($result))) {
    echo 'nohost';
    exit();
}
$zone_id = $zone_info['id'];

/*
2. Query Record ID
*/
$options = array(
    CURLOPT_URL => "https://api.cloudflare.com/client/v4/zones/${zone_id}/dns_records?type=A&name=${fullname}",
    CURLOPT_HTTPGET => true,
    CURLOPT_HEADER => false,
    CURLOPT_VERBOSE => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER =>  $header
);

if (empty($data = exec_curl($options))) {
    echo 'badauth';
    exit();
}

$result = array_filter(array_get($data, 'result', []), function($row) use ($hostname) {
    return $row['name'] === $hostname;
});

if(empty($record_info = array_pop($result))) {
    echo 'nohost';
    exit();
}

$record_id = $record_info['id'];
$ttl = $record_info['ttl'];
$proxied = $record_info['proxied'];

/*
3. Update DNS
*/
$options = array(
    CURLOPT_URL => "https://api.cloudflare.com/client/v4/zones/${zone_id}/dns_records/${record_id}",
    CURLOPT_POST => true,
    CURLOPT_CUSTOMREQUEST => "PUT",
    CURLOPT_HEADER => false,
    CURLOPT_VERBOSE => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $header,
    CURLOPT_POSTFIELDS => json_encode(array(
        'type'=>'A',
        'name'=>$fullname,
        'content'=>$ip,
        'ttl'=>$ttl,
        'proxied'=>$proxied
    ))
);

if (empty($data = exec_curl($options))) {
    echo 'Update Record failed';
    exit();
}

echo 'good';



/*
 * Helpers
 */

/**
 * Get array member by key
 * @param $array
 * @param $key
 * @param $default
 * @return mixed|null
 */
function array_get(&$array, $key, $default = null) {
    return isset($array[$key]) ? $array[$key] : $default;
}

/**
 * execute curl and parse return data
 * @param $options
 * @return mixed|null
 */
function exec_curl($options) {
    $req = curl_init();
    curl_setopt_array($req, $options);
    $res = curl_exec($req);
    curl_close($req);
    $result = json_decode($res, true);

    // echo "\n\n";
    // echo var_export($result, true);
    // echo "\n\n";

    if (array_get($result, 'success', false)) {
        return $result;
    }

    return null;
}
