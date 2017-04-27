<?php
$month = mb_substr($timestamp, 5, 2);
$day = mb_substr($timestamp, 8, 2);
$year = mb_substr($timestamp, 0, 4);
$time = mb_substr($timestamp, 11, 5);
$timeString = $month . '/' . $day . '/' . $year . ' At ' . $time;