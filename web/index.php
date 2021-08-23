<?php

require '../bootstrap.php';
require '../ScheduleApplication.php';

$app = new ScheduleApplication(true);
$app->run();
