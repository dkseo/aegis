<?php
/**
 * 회원가입 컨트롤러
 */

namespace module\auth\controller;
use classes\system\framework\dkFrameWork;

class signUpController extends dkFrameWork
{

    // init - redirect to step1
    public function indexAction(  )
    {
        header("Location:/auth/signUp/step1");
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
            echo $this->value->server->host;
            $this->script->location( $this->value->server->host . "/");

        }
        //debug($this->value);
    }

    #####################################
    ## step #2
    #####################################
    public function step2Action(  )
    {

    }

    #####################################
    ## step #3
    #####################################
    public function step3Action(  )
    {

    }

    #####################################
    ## step #4
    #####################################
    public function step4Action(  )
    {

    }

    #####################################
    ## step #5
    #####################################
    public function step5Action(  )
    {

    }

}