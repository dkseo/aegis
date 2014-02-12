<?php
/**
 * 회원가입 step 5
 *  - temp db 이전 및 회원 생성
 */

namespace module\auth\model;
use classes\system\framework\dkFrameWork;
use classes\user\db\aegisDB;

class signUp_step5
{
    private $fw;
    private $db;
    public $error;

    public function __construct( $obj )
    {
        $this->fw = $obj;
        $this->db = new aegisDB;
    }

    public function createUser( )
    {
        $userEmail = $_SESSION["S_tempUserEmail"];

        // 회원코드 생성
        $code = new userCode;
        $newUserCode = $code->getUserCode( $userEmail );

        // temp db 정보 가져오기
        $query = "
            SELECT * FROM USER_TEMP WHERE USER_EMAIL = '" . $userEmail . "'
        ";
        $this->db->query($query);
        $tempInfo = $this->db->next_row();

        // USER TABLE INSERT
        $query = "
            INSERT IGNORE INTO
                USER
            SET
                 USER_CODE      = '" . $newUserCode . "'
                ,USER_NAME      = '" . $tempInfo['USER_NAME'] . "'
                ,USER_EMAIL     = '" . $userEmail . "'
                ,USER_PASSWORD  = '" . $tempInfo['USER_PASSWORD'] . "'
                ,USER_TYPE      = 'UN'
                ,USER_STATS     = 'NN'
        ";
        $this->db->insert($query);

        // USER_DEAIL TABLE INSERT
        $query = "
            INSERT IGNORE INTO
                USER_DETAIL
            SET
                USER_CODE               = '" . $newUserCode . "'
                ,USER_COMPANY           = '" . $tempInfo['USER_COMPANY'] . "'
                ,USER_COUNTRY           = '" . $tempInfo['USER_COUNTRY'] . "'
                ,USER_ADDRESS_1         = '" . $tempInfo['USER_ADDRESS_1'] . "'
                ,USER_ADDRESS_2         = '" . $tempInfo['USER_ADDRESS_2'] . "'
                ,USER_ZIPCODE           = '" . $tempInfo['USER_ZIPCODE'] . "'
                ,USER_CITY              = '" . $tempInfo['USER_CITY'] . "'
                ,USER_REGION            = '" . $tempInfo['USER_REGION'] . "'
                ,USER_PHONENUMBER       = '" . $tempInfo['USER_PHONENUMBER'] . "'
                ,CREDITCARD_NUMBER      = '" . $tempInfo['CREDITCARD_NUMBER'] . "'
                ,CREDITCARD_OWNER       = '" . $tempInfo['CREDITCARD_OWNER'] . "'
                ,CREDITCARD_EXPIREDATE  = '" . $tempInfo['CREDITCARD_EXPIREDATE'] . "'
                ,CREDITCARD_TYPE        = '" . $tempInfo['CREDITCARD_TYPE'] . "'
                ,REGISTER_IP            = '" . $_SERVER['REMOTE_ADDR'] . "'
                ,REGISTER_DATE          = NOW()
        ";
        $this->db->insert($query);

        // DELETE TEMP DATA
        $query = "
            DELETE FROM USER_TEMP WHERE USER_EMAIL = '" . $userEmail . "'
        ";
        $this->db->query($query);

        // LOG
        $query = "
            INSERT IGNORE INTO
                USER_LOG
            SET
                 USER_CODE = '" . $newUserCode . "'
                ,LOG_TYPE = 'NEW'
                ,DESCRIPTION = ''
                ,REGISTER_DATE = NOW()
        ";
        $this->db->query($query);

        return true;
    }
}