<?php
/**
 * 회원가입 step 3
 *  - 회원 상세정보 입력
 */

namespace module\auth\model;
use classes\system\framework\dkFrameWork;
use classes\user\db\aegisDB;

class signUp_step3
{

    private $fw;
    private $db;
    public $error;

    public function __construct( $obj )
    {
        $this->fw = $obj;
        $this->db = new aegisDB;
    }

    public function insertUserInfo( )
    {

        // captcha verify
        if ( $_SESSION["captcha"]["code"] !=  $this->fw->value->post->captcha) {
            $this->error = "ERROR|Unverified captcha";
            return false;
        }else{
            $userEmail = $_SESSION["S_tempUserEmail"];

            $query = "
                UPDATE
                    USER_TEMP
                SET
                     USER_NAME = '" . $this->fw->value->post->userName . "'
                    ,USER_COMPANY = '" . $this->fw->value->post->userCompany . "'
                    ,USER_COUNTRY = '" . $this->fw->value->post->userCountry . "'
                    ,USER_ADDRESS_1 = '" . $this->fw->value->post->userAddress1 . "'
                    ,USER_ADDRESS_2 = '" . $this->fw->value->post->userAddress2 . "'
                    ,USER_CITY = '" . $this->fw->value->post->userCity . "'
                    ,USER_ZIPCODE = '" . $this->fw->value->post->userZipCode . "'
                    ,USER_REGION = '" . $this->fw->value->post->userRegion . "'
                    ,USER_PHONENUMBER = '" . $this->fw->value->post->userPhoneNumber . "'
                WHERE
                    USER_EMAIL = '" . $userEmail . "'
            ";
            $this->db->update($query);

            return true;
        }
    }
}
