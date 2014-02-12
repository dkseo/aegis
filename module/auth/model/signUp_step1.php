<?php
/**
 * 회원가입 step 1
 *  - 중복 이메일 검사
 *  - temp 코드 생성
 *  - 인증 메일 발송
 */

namespace module\auth\model;
use classes\system\framework\dkFrameWork;
use classes\user\db\aegisDB;
use classes\user\sendmail\sendmail;

class signUp_step1
{
    private $fw;
    private $db;
    private $user_email;
    private $user_password;
    private $user_password_re;
    private $return_value;

    // init
    public function __construct( $obj )
    {
        // super 변수 받기
        $this->fw = $obj;

        // 변수 검사 - 실패
        if ( !$this->check_value() ) {
            echo $this->return_value;
            return $this->return_value;

        }else{
            ### DB connect
            $this->db = new aegisDB;

            ### 이메일 중복 확인
            $this->check_duplication_email();
            ### Temp User 저장
            $userTempCode = $this->createTempUser();
            ### 인증메일 발송
            $userTempCode = $this->sendMail();
        }
    }


    // 변수 검사
    private function check_value(  )
    {
        // 이메일정보 있는지
        if ( !isset($this->fw->value->post->user_email) ) {
            $this->return_value = "ERROR|empty email address.";
            return false;
        }
        // 비밀번호정보 있는지
        if ( !isset($this->fw->value->post->user_password) ) {
            $this->return_value = "ERROR|empty password.";
            return false;
        }
        // 다시 비밀번호정보 있는지
        if ( !isset($this->fw->value->post->user_password_re) ) {
            $this->return_value = "ERROR|empty retry.";
            return false;
        }
        // 이메일주소 형식이 맞는지
        if ( filter_var($this->user_email, FILTER_VALIDATE_EMAIL) ) {
            $this->return_value = "ERROR|email address is not valid";
            return false;
        }
        // 비밀번호 재 입력한게 일치한지
        if ( $this->fw->value->post->user_password != $this->fw->value->post->user_password_re ) {
            $this->return_value = "ERROR|password != retry";
            return false;
        }

        return true;
    }


    // 이메일 중복 검사
    private function check_duplication_email( )
    {

        $query = "SELECT COUNT(*) FROM USER WHERE USER_EMAIL = ('" . $this->fw->value->post->user_email . "')";

        // 중복 됨
        if ( $this->db->simple_query($query) > 0 ) {
            $this->return_value = "ERROR|email address duplicated";
            return false;
        }

        return true;
    }

    // 임시 회원 생성
    private function createTempUser(  )
    {
        // 임시테이블에 같은 메일정보 있으면 덮어쓰기
        $query = "
            INSERT IGNORE INTO
                USER_TEMP
            SET
                 USER_EMAIL = '" . $this->fw->value->post->user_email . "'
                ,USER_PASSWORD = '" . md5($this->fw->value->post->user_password) . "'
                ,USER_NAME = ''
                ,CERTIFYED = ''
                ,USER_COMPANY = ''
                ,USER_COUNTRY = ''
                ,USER_ADDRESS_1 = ''
                ,USER_ADDRESS_2 = ''
                ,USER_ZIPCODE = ''
                ,USER_CITY = ''
                ,USER_REGION = ''
                ,USER_PHONENUMBER = ''
                ,CREDITCARD_NUMBER = ''
                ,CREDITCARD_OWNER = ''
                ,CREDITCARD_EXPIREDATE = ''
                ,CREDITCARD_TYPE = ''
                ,REGISTER_DATE= ''
        ";
        $result = $this->db->insert($query);
    }

    // 인증메일 발송
    private function sendMail(  )
    {

        // 인증링크 만들기
        $key = $this->fw->value->post->user_email . "|" . md5($this->fw->value->post->user_password);
        $key = base64_encode($key);
        $link = $this->fw->value->server->host . "/auth/signUp/step1_cerify_email/" . $key;

        $subject = "Welcome to AEGIS";
        $body = "
            Email address Cerification
            Click this link : $link
        ";

        $sendmail = new sendmail;
        $sendmail->name = "AEGIS team";
        $sendmail->from = "aegis@aegis.com";
        $sendmail->to = $this->fw->value->post->user_email;
        $sendmail->subject = $subject;
        $sendmail->body = $body;
        $sendmail->sending();
    }
}