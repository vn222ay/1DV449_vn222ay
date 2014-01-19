<?php

//Fakes a incoming sale for demonstration

$ip = $_SERVER['REMOTE_ADDR'];

$channelid = array(13053, 13876, 13754);
$programid = array(250, 407, 168, 56);
$money = rand(15, 200) * 100;

file_get_contents("http://192.168.0.22/Projekt/reciever.php?ordervalue=" . ($money * 5) . "&ordertype=sale&commission=" . $money . "&epi=annons-stor&ip=$ip&clicktime=" . (time() - 5329) . "&referrer=http://www.google.se/&ordertime=" . time() . "&programid=" . ($programid[rand(0, 4)]) . "&channelid=" . ($channelid[rand(0, 3)]) . "&ect_keyword=sškord_hŠr&ect_searchengine=Google&ect_time=123456789123&ect_rank=15&transactionid=12345678");