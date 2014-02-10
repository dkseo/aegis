<?php
/**
 * 프레임웍 구동시 필요한 부속들
 *
 * - 모듈로드
 * - 라우트로드
 * - URI 설정
 *
 * @author  daekyu.seo dkseo@pentasecurity.com
 * @copyright   2014 Penta Security
 *
 */

Namespace classes\system\framework;
use classes\system\framework\moduleLoader;
use classes\system\framework\routeLoader;
use classes\system\framework\uriLoader;
use classes\system\framework\scriptFunction;


class dkFrameWork
{

    public $module;
    public $route;

    ## init
    public function __construct()
    {
        // 모듈 로드
        $this->LoadModule();
        // 라우트 로드
        $this->LoadRoute();
        // URI 셋팅
        $this->SettingURI();

        // default 모듈의 config파일 load
        $this->LoadDefaultModuleConfig();

        // 변수 만들기
        $this->BuildValue();

        // 현재 접속한 URI의 환경 설정 Load
        // 모듈, 컨트롤러, 액션, 뷰 등
        $this->LoadUriModuleConfig();
    }


    #################################
    ## 모듈 로드
    #################################
    private function LoadModule()
    {
        $module = new moduleLoader;

        // 모듈명 등록
        $this->setModuleName( $module );
        // 모듈명으로 object 등록
        $this->createModuleObject();
    }

    // 모듈명 등록
    private function setModuleName( $module )
    {
	$this->module = new \stdClass();
        $this->module->name = $module->module_name;
    }

    // 모듈명으로 object 등록
    private function createModuleObject()
    {
        foreach ( $this->module->name as $key => $val ){
            $this->module->$val = new \stdClass();
            $this->module->$val->path = MODULE_PATH . $val;
        };
    }


    #################################
    ## 라우트 로드
    #################################
    private function LoadRoute()
    {
        $route = new routeLoader;
        $this->route = new \stdClass();
        $this->route->name = $route->route;
    }


    #################################
    ## URI 설정
    #################################
    private function SettingURI()
    {
        $uri = new uriLoader;
        $this->uri = new \stdClass();
        $this->uri->list = $uri->uri;
        $this->uri->now_uri = $uri->now_uri;
    }


    #################################
    ## default 모듈의 config 파일 Load
    #################################
    private function LoadDefaultModuleConfig()
    {
        $this->default_module_config = require $this->module->_default->path . DS . "module.config.php";
    }


    #################################
    ## 현재 URI의 모듈 설정 Load
    #################################
    private function LoadUriModuleConfig()
    {
        ### 모듈 Load
        // 현재 uri가 모듈로 등록되어 있으면 모듈 config 파일 로드
        if ( in_array( $this->uri->now_uri, $this->module->name ) ) {
            $now_uri = $this->uri->now_uri;
            $module_config = require $this->module->$now_uri->path . DS . "module.config.php";
            $this->info = new \stdClass();
            $this->info->module = $this->uri->now_uri;
        }
        // 그렇지 않으면 default 설정 로드
        else {
            $module_config = require $this->module->_default->path . DS . "module.config.php";
            $this->info->module = "_default";
        }

        ### 컨트롤러, 액션, arg... Load
        foreach ( $module_config["routes"]["constraints"] as $key => $val ){
            $this->info->$val = @$this->uri->list[$key];
        }

        ### 선언 안한 항목은 config에서 미리 선언한 default값으로 셋팅
        foreach ( $this->info as $key => $val ){
            if ( !$val ) {
                $this->info->$key = @$module_config["routes"]["defaults"][$key];
            }
        }

        ### view 설정
        ### default view 설정 후, 현재 모듈 view로 덮어씌움
        $this->info->view = $this->default_module_config["routes"]["view_manager"];
        $this->info->view["layout"] = ( isset($module_config["routes"]["view_manager"]["layout"]) )?$module_config["routes"]["view_manager"]["layout"] : $this->info->view["layout"];
        $this->info->view["html_path"] = ( isset($module_config["routes"]["view_manager"]["html_path"]) )?$module_config["routes"]["view_manager"]["html_path"] : $this->info->view["html_path"];
        $this->info->view["error"] = ( isset($module_config["routes"]["view_manager"]["error"]) )?$module_config["routes"]["view_manager"]["error"] : $this->info->view["error"];
    }


    #################################
    ## 변수 만들기
    #################################
    public function BuildValue()
    {
        /**
         * Function Load
         */
        $this->script = new scriptFunction;

        /**
         * $_SERVER
         */
        $this->value->server->protocal = ($_SERVER["SERVER_PORT"] == "80")?"http://":"https://";
        $this->value->server->host = $this->value->server->protocal . $_SERVER["HTTP_HOST"];
        $this->value->server->referer = str_replace( $this->value->server->host, "", $_SERVER["HTTP_REFERER"]);
        $this->value->server->remote_addr = $_SERVER["REMOTE_ADDR"];



        //debug($_SERVER);

        /**
         * $_POST
         */
        if(count($_POST)){
            foreach ( $_POST as $key => $val ){
                $this->value->post->$key = $val;
            }
        }

    }


    #################################
    ## 프레임워크 시작
    #################################
    public function RunningFW()
    {
        // 컨트롤러 로드
        $tmpController = "\module\\" . $this->info->module . "\controller\\" . $this->info->controller . "Controller";
        $controller = new $tmpController;

        // action 시작
        $tmpAction = $this->info->action . "Action";
        $action = $controller->$tmpAction();

        // action 에서 리턴한 배열들 변수화
        if( is_array($action) ){
            foreach ( $action as $key => $val ){
                ${$key} = $val;
            }
        }

        // contents 로드
        $contents_path = $this->info->view["html_path"] . DS . $this->info->controller;
        $contents_path .= DS . $this->info->action . ".phtml";

        // error catch
        if ( !is_file($contents_path) ){
            echo "<br>error : view 파일이 없음. ($contents_path)<br><br>";
            $contents = "";
        }else{
            ob_start();
            include( $contents_path );
            $contents = ob_get_clean();
        }

        // layout Load
        $layout = $this->info->view["layout"];
        // error catch
        if ( !is_file($layout) ){
            echo "<br>error : layout 파일이 없음. ($layout)<br><br>";
        }else{
            $layout = require_once $layout;
        }
    }
}
