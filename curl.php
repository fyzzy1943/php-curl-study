<?php
$proxy = curl_init('http://www.xicidaili.com/nn/');
curl_setopt($proxy, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0');
curl_setopt($proxy, CURLOPT_RETURNTRANSFER, 1);
$html = curl_exec($proxy);
curl_close($proxy);

preg_match('/<table id="ip_list">\s*<tr>[\s\S]*?<\/tr>([\s\S]*)<\/table>/', $html, $result);
$result[1] = preg_replace('/\s*/', '', $result[1]);

preg_match_all('/<td>((\d{1,3}\.){3}\d{1,3})<\/td>' //地址
    .'<td>(\d*)<\/td>'  //端口
    .'<td><ahref="\S*?">(\S*?)<\/a><\/td>'   //位置
    .'<td>高匿<\/td><td>(\S*?)<\/td>' //类型
    .'<td><divtitle="(\S*?)"class="bar">\S*?<\/div><\/div><\/td>' //速度
    .'<td><divtitle="(\S*?)"class="bar">\S*?<\/div><\/div><\/td>' //连接时间
    .'<td>(\S*?)<\/td>' //验证时间
    .'/',
    $result[1], $result, PREG_SET_ORDER);

$proxies = array();
foreach ($result as $re) {
    $proxies[] = array(
        'ip'=>$re[1],
        'port'=>$re[3],
        'address'=>$re[4],
        'type'=>$re[5],
        'speed'=>$re[6],
        'link_time'=>$re[7],
        'verify_time'=>preg_replace('/(\d{2}-\d{2}-\d{2})(\d{2}:\d{2})/', '$1 $2', $re[8]),
    );
}
//echo <<<EOT
//<table>
//<tr>
//<th>地址</th>
//<th>端口</th>
//<th>位置</th>
//<th>类型</th>
//<th>速度</th>
//<th>连接时间</th>
//<th>验证时间</th>
//</tr>
//EOT;
//foreach ($proxies as $re) {
//    echo '<tr><td>'.$re[1].'</td>'
//        .'<td>'.$re[3].'</td>'
//        .'<td>'.$re[4].'</td>'
//        .'<td>'.$re[5].'</td>'
//        .'<td>'.$re[6].'</td>'
//        .'<td>'.$re[7].'</td>'
//        .'<td>'.preg_replace('/(\d{2}-\d{2}-\d{2})(\d{2}:\d{2})/', '$1 $2', $re[8]).'</td></tr>'
//    , PHP_EOL;
//}
//echo '</table>';

//exit();

$ch = curl_init();
$proxy_cursor = 0;
$result = array();
$result_id =array();

$max_id = 3000;

for ($i=200; $i<$max_id; $i++){
    curl_setopt($ch, CURLOPT_URL, 'https://www.v2ex.com/api/members/show.json?id=' . $i);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_PROXY, $proxies[$proxy_cursor]['ip']);
    curl_setopt($ch, CURLOPT_PROXYPORT, $proxies[$proxy_cursor]['port']);
    $temp = json_decode(curl_exec($ch), true);
    if ($temp['status'] == 'found') {
        print_r(array('id'=>$temp['id'],'status'=>$temp['status']));
    } else {
        print_r($temp);
        break;
    }
//    $result[] = $temp;
}

//var_dump($result_id);

curl_close($ch);
?>
