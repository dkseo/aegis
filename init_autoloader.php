<?php

# session start
session_start();
# enc type
header("Content-Type: text/html; charset=UTF-8");


### Define Variable
define ('DS', DIRECTORY_SEPARATOR);
define ('ROOT_PATH', __DIR__ . DS);
//---
define ('CLASS_PATH', ROOT_PATH . "classes" . DS);
define ('SYSTEM_CLASS_PATH', ROOT_PATH . "classes" . DS . "system" . DS);
//---
define ('MODULE_PATH', ROOT_PATH . "module" . DS);


use classes\system\framework\dkFrameWork;

## 함수 선언
include SYSTEM_CLASS_PATH . "framework" . DS . "dkFunction.php";

## 프레임워크 구동
$fw = new dkFrameWork;

## Run
$fw->RunningFW();
