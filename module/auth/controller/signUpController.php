<?php
/**
 * 회원가입 컨트롤러
 */

namespace module\auth\controller;
use classes\system\framework\dkFrameWork;
use classes\user\db\aegisDB;
use module\auth\model\signUp_step1;
use module\auth\model\signUp_step1_cerify_email;
use module\auth\model\signUp_step3;
use module\auth\model\signUp_step4;
use module\auth\model\signUp_step5;


class signUpController extends dkFrameWork
{

    // init - redirect to step1
    public function indexAction(  )
    {
        // locate
        $this->script->location("/auth/signUp/step1");
    }

    #####################################
    ## step #1
    #####################################
    // step1 이메일 비번 입력 받기
    public function step1Action(  )
    {

    }

    // step1 이메일 비번 입력 받은거 처리하기
    public function step1_processingAction(  )
    {
        // referer 검사
        if ( $this->value->server->referer != "/auth/signUp/step1" ) {
            $this->script->location( $this->value->server->host . "/");
        }
        //debug($this);

        /**
         * 회원가입 step 1 process
         *  - 중복 이메일 검사
         *  - temp 코드 생성
         *  - 인증 메일 발송
         */
        $signUp = new signUp_step1($this);

        $_SESSION["S_tempUserEmail"] = $c_email;

        // step2로 넘기기
        $this->script->location( $this->value->server->host . "/auth/signUp/step2" );
    }

    // step1 이메일 인증 처리
    public function step1_cerify_emailAction(  )
    {
        $key = $this->info->arg1;
        $key = base64_decode($key);
        $key = explode("|", $key);

        $c_email = $key[0];
        $c_passwd = $key[1];

        $signUp = new signUp_step1_cerify_email($this);

        if( $signUp->cerify_email($c_email, $c_passwd) ){
            $this->script->location( $this->value->server->host . "/auth/signUp/step3" );
        }else{
            echo "인증정보가 올바르지 않습니다.";
        }
    }


    #####################################
    ## step #2
    #####################################
    // 인증메일 발송했다는 안내 페이지
    public function step2Action(  )
    {
        return array(
            "userEmail" => $_SESSION["S_tempUserEmail"],
        );
    }


    #####################################
    ## step #3
    #####################################
    // 세부정보 입력
    public function step3Action(  )
    {
        //echo ROOT_PATH;
        //include "simple-php-captcha/simple-php-captcha.php";
        include ROOT_PATH . "public/simple-php-captcha/simple-php-captcha.php";
        $_SESSION['captcha'] = simple_php_captcha();

        return array(
            "captcha_img" => $_SESSION['captcha']['image_src'],
            "tempUserEmail" => $_SESSION["S_tempUserEmail"],
        );
    }

    // 세부정보 입력한거 저장
    public function step3_processAction(  )
    {
        // referer 검사
        if ( $this->value->server->referer != "/auth/signUp/step3" ) {
            $this->script->location( $this->value->server->host . "/");
        }

        $result = new signUp_step3($this);
        if ( $result->insertUserInfo() ) {
            $this->script->location( $this->value->server->host . "/auth/signUp/step4");
        }else{
            // error
            echo $result->error;
        }
    }

    #####################################
    ## step #4
    #####################################
    // 신용카드 정보 입력
    public function step4Action(  )
    {

    }

    // 신용카드 정보 DB 저장
    public function step4_processAction(  )
    {
        // referer 검사
        if ( $this->value->server->referer != "/auth/signUp/step4" ) {
            $this->script->location( $this->value->server->host . "/");
        }

        $result = new signUp_step4($this);
        if ( $result->insertCreditInfo() ) {
            $this->script->location( $this->value->server->host . "/auth/signUp/step5_process");
        }else{
            // error
            echo $result->error;
        }
    }

    #####################################
    ## step #5
    #####################################
    // 가입완료 안내
    public function step5Action(  )
    {
        return array(
            "userEmail" => $_SESSION["S_tempUserEmail"]
        );
    }
    // Temp DB를 본 DB로 이전
    public function step5_processAction(  )
    {
        // referer 검사
        if ( $this->value->server->referer != "/auth/signUp/step4" ) {
            $this->script->location( $this->value->server->host . "/");
        }

        $result = new signUp_step5($this);
        if ( $result->createUser() ) {
            $this->script->location( $this->value->server->host . "/auth/signUp/step5");
        }else{
            // error
            echo $result->error;
        }
    }
}