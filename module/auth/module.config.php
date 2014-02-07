<?php
/**
 * auth 모듈 설정
 *
 * @author  daekyu.seo dkseo@pentasecurity.com
 * @copyright   2014 Penta Security
 *
*/

return array(
    "routes" => array(
        "route" => "/auth",

        "constraints" => array(
            "module",
            "controller",
            "action",
        ),

        "defaults" => array(
            "controller"    => "index",
            "action"        => "index",
        ),

        /**
         * 컨트롤러 mapping 설정
         * 일부 컨트롤러는 기존의 컨트롤러를 사용할 수 있도록 하는 설정
         * 컨트롤러 명 => "매핑할 컨트롤러 명"
        "mapping" => array(
            "mapping"   => "index",
            "mapping1"   => "index",
            "mapping2"   => "index",
        ),
        */

        "view_manager" => array(
            //"layout"    => __DIR__ . DS . "view" . DS . "layout" . DS . "layout.phtml",
            "html_path"   => __DIR__ . DS . "view" . DS . "html",

        ),
    ),
);
