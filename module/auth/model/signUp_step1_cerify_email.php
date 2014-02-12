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

class signUp_step1_cerify_email
{
    private $db;

    public function __construct(  )
    {
        $this->db = new aegisDB;
    }

    public function cerify_email( $email, $passwd )
    {
        $query = "
            SELECT
                COUNT(*)
            FROM
                USER_TEMP
            WHERE
                USER_EMAIL = '" . $email . "' AND
                USER_PASSWORD = '" . $passwd . "'
        ";
        if($this->db->simple_query($query) < 1 ){
            echo "cerify ERROR";
            return false;
        }else{
            return true;
        }
    }
}