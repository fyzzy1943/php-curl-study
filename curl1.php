<?php
/**
 * Date: 2016/4/15
 */
$id = 720;
$max_id = 168371;
try{
    $db = new PDO('mysql:dbname=v2ex;host=127.0.0.1', 'root', 'black');
    $db->query('set names utf8');

} catch (Exception $e) {
    echo $e->getMessage(), PHP_EOL;
}

while ($id < $max_id) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    for ($i=1; $i<200; $i++,$id++){
        curl_setopt($ch, CURLOPT_URL, 'https://www.v2ex.com/api/members/show.json?id=' . $id);
        $temp = json_decode(curl_exec($ch), true);

        if ($temp['status'] == 'found') {
            print_r(array('id'=>$temp['id'],'status'=>$temp['status']));
//            print_r($temp);
            echo $count=$db->exec("INSERT INTO `user` VALUES('$temp[id]','$temp[url]','$temp[username]','$temp[website]','$temp[twitter]','$temp[psn]','$temp[github]','$temp[btc]','$temp[location]','$temp[tagline]','$temp[bio]','$temp[avatar_mini]','$temp[avatar_normal]','$temp[avatar_large]','$temp[created]')");
        } else {
//            echo 'wrong', PHP_EOL;
            print_r($temp);
            break 2;
        }
    }
    curl_close($ch);
}
