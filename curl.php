<?php
$proxy = curl_init('http://www.xicidaili.com/nn/');
curl_setopt($proxy, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0');
curl_setopt($proxy, CURLOPT_RETURNTRANSFER, 1);

$html = curl_exec($proxy);
$regex = '/<table id="ip_list">([\s\S]*?)<\/table>/';
preg_match($regex, $html, $result);
preg_match('/<tr>[\s\S]*?<\/tr>([\s\S]*)/', $result[1], $html);
//$html[1] = preg_replace('\s*', ' ', $html[1]);
//$html[1] = str_replace(PHP_EOL, '', $html[1]);
//print($html[1]);
preg_match_all('/<td>((\d{1,3}\.){3}\d{1,3})<\/td>\s*'
    .'<td>(\d*)<\/td>\s*<td>/', $html[1], $result, PREG_SET_ORDER);
//var_dump($result[0]);
//for ($i=0; $i<count($result[0]); ++$i) {
//    echo '地址：'.$result[0][$i] , '<br>', PHP_EOL;
//}
//var_dump($result);
foreach ($result as $re) {
    echo '地址：'.$re[1].' 端口：'.$re[3] , '<br>', PHP_EOL;
}
//echo $result[1][1];
curl_close($proxy);

exit();

$ch = curl_init();
$result = array();
$result_id =array();

$max = 3000;

for ($i=200; $i<$max; $i++){
    curl_setopt($ch, CURLOPT_URL, 'https://www.v2ex.com/api/members/show.json?id=' . $i);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $temp = json_decode(curl_exec($ch), true);
    if ($temp['status'] == 'found') {
        print_r(array('id'=>$temp['id'],'status'=>$temp['status']));
    } else {
        print_r($temp);
        break;
    }
    $result[] = $temp;
}

var_dump($result_id);

curl_close($ch);
?>
