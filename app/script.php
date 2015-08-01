<?php
/**
 * Created by PhpStorm.
 * User: joy
 * Email: joyruet06@gmail.com
 * Date: 12/23/14
 */

$categories = [
    '21151' => 'Agriculture, forestry and fishery',
    '14376' => 'Architecture and building',
    '16747' => 'Arts',
    '17953' => 'Business and administration',
    '15230' => 'Computing',
    '17860' => 'Education',
    '14277' => 'Electrical engineering',
    '14509' => 'Environmental protection',
    '14271' => 'Health',
    '17970' => 'Humanities',
    '17414' => 'Journalism and information',
    '14324' => 'Law',
    '18281' => 'Life sciences',
    '17160' => 'Manufacturing and processing materials',
    '14759' => 'Manufacturing and processing of food',
    '18432' => 'Manufacturing and processing of textiles, clothes, footwear, leather',
    '14546' => 'Mathematics and statistics',
    '14526' => 'Metal processing and mechanical engineering',
    '18132' => 'Personal services',
    '20673' => 'Physical sciences',
    '15103' => 'Security services',
    '17979' => 'Social and behavioural science',
    '16732' => 'Social services',
    '14888' => 'Transport services',
    '14440' => 'Veterinary',
];

function file_get_contents_curl($Url)
{
    if (!function_exists('curl_init'))
    {
        die('Sorry cURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_REFERER, "http://alquran.org.bd");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

foreach($categories as $id => $name){
    //echo file_get_contents_curl('https://ec.europa.eu/esco/hierarchybrowser/-/browser?p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=1&p_p_resource_id=skill/child_of_lsg/en/http://ec.europa.eu/esco/skill/'.$id);
    file_put_contents('./dox/skills/'.$id.'.json', file_get_contents_curl('https://ec.europa.eu/esco/hierarchybrowser/-/browser?p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=1&p_p_resource_id=skill/child_of_lsg/en/http://ec.europa.eu/esco/skill/'.$id));
}