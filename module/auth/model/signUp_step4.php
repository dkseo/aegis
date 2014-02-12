<?php
/**
 * 회원가입 step 4
 *  - 신용카드 정보 입력
 */

namespace module\auth\model;
use classes\system\framework\dkFrameWork;
use classes\user\db\aegisDB;

class signUp_step4
{
    private $fw;
    private $db;
    public $error;

    public function __construct( $obj )
    {
        $this->fw = $obj;
        $this->db = new aegisDB;
    }

    public function insertCreditInfo( )
    {
        $this->db = new aegisDB;

        $userEmail = $_SESSION["S_tempUserEmail"];

        $cardNumber = $this->fw->value->post->cardNumber["0"]."-".
                      $this->fw->value->post->cardNumber["1"]."-".
                      $this->fw->value->post->cardNumber["2"]."-".
                      $this->fw->value->post->cardNumber["3"];
        $cardExpDate = $this->fw->value->post->expMonth . "|" . $this->fw->value->post->expYear;

        $query = "
            UPDATE
                USER_TEMP
            SET
                 CREDITCARD_NUMBER = '" . $cardNumber . "'
                ,CREDITCARD_OWNER = '" . $this->fw->value->post->cardOwnerName . "'
                ,CREDITCARD_EXPIREDATE = '" . $cardExpDate . "'
                ,CREDITCARD_TYPE = '" . $this->fw->value->post->userCreditCard . "'
                ,REGISTER_DATE = NOW()
            WHERE
                USER_EMAIL = '" . $userEmail . "'
        ";
        $this->db->update($query);

        return true;
    }
}
