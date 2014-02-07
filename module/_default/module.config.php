<?php
/**
 * 모듈 설정
 *
 * - 파일명, 위치 바꾸면 안됨.
 *
 * @author  daekyu.seo dkseo@pentasecurity.com
 * @copyright   2013 Penta Security
 *
*/

return array(
    "routes" => array(
        /**
         * 현재 모듈 위치 알기 위한 참고용.
        */
        "route" => "/",

        /**
         * REQUEST URI에서 구분자는 "/" 이고, 구분자로 구분했을 때(explode) 배열의 순서대로 설정한다.
         * 배열의 갯수는 무한대이고, 순서도 마음대로 정의할 수 있다.
         * controller와 action을 정의하지 않을시 defaults의 내용을 그대로 따라 간다.
        */
        "constraints" => array(
            "controller",
            "action",
            "arg1",
            "arg2",
        ),

        /**
         * 컨트롤러와 액션의 기본 설정
         * constraints의 항목과 이름이 동일해야 함 (대소문자 구분)
         * System에서 controller 이름을 기준으로 파일과 클래스명을 찾음.
         * 그러므로 반드시 같은 파일명과 클래스명으로 선언해야 함.
         * 선언된 action명으로 controller내 함수명을 찾아가므로
         * 반드시 선언한 action명과 동일하게 함수를 선언해야 함.
        */
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

        /**
         * 기본 뷰 페이지임.
         * 사용자 정의 모듈에서 view_manager를 설정하지 않으면 기본으로 home의 view_manager를 따라감.
         * 모듈별 뷰를 따로 가져가고 싶으면 해당 모듈의 module.config.php에 별도 등록해야 함.
        */
        "view_manager" => array(
            "layout"    => __DIR__ . DS . "view" . DS . "layout" . DS . "layout.phtml",
            "html_path"   => __DIR__ . DS . "view" . DS . "html",
            "error"     => __DIR__ . DS . "view" . DS . "error" . DS . "error.phtml",
        ),
    ),
);
