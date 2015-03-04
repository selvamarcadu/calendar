<?php

date_default_timezone_set('UTC');

require __DIR__ . '/vendor/autoload.php';

$calendar = new \loganhenson\Calendar\Calendar(\Carbon\Carbon::now());

header('Content-Type: application/json');
echo json_encode($calendar->generateYearCalendar());