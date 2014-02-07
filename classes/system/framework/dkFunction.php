<?php
/**
 * 공통 함수 선언
 *
 * @author  daekyu.seo dkseo@pentasecurity.com
 * @copyright   2014 Penta Security
 *
 */

// Class Loader
// new를 통해 클래서 선언시 디렉토리 구조와 같은 namespace를 유지해야 함.
function __autoload( $className )
{
    $class_path = str_replace("\\", DS, $className . ".php");
    require_once ROOT_PATH . $class_path;
}


// debug
function debug( $array )
{
    echo "<xmp>";
    print_r($array);
    echo "</xmp>";
}


// 디렉토리명 가져오기
function getDir( $path )
{
    $dir_list = scandir( $path );
    foreach ( $dir_list as $key => $val ){
        //echo $val;
        if ( $val == "." || $val == '..' || !is_dir( MODULE_PATH . $val ) ){
            continue;
        }
        else{
            $module[] = $val;
        }
    }
    return $module;
}
