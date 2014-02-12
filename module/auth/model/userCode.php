<?php

/**
 * 회원코드 생성기
 *
 * 정식회원코드 생성하는 클래스
 *
 * @author  daekyu.seo dkseo@pentasecurity.com
 * @copyright   2013 Penta Security
 * @category    Code
 * @package     Pcloud
*/

namespace module\auth\model;
use classes\user\db\aegisDB;


class userCode
{

    private $db;

    /**
     * init
    */
    public function __construct()
    {
        // DB connection
        $this->db = new aegisDB;
    }

    /**
     * 각종 회원코드 생성
     *
     * 회원코드 생성하여 중복검사 실행함.
     *
     * $type
     *   - tmpUserCode : 임시 회원코드
     *   - defUserCode : 정식 회원코드
    */
    public function getUserCode( $userEmail )
    {

        // 앞 코드 생성
        unset($tmpCode);
        $tmpCode[] = "P";         # Pcloud web을 통한 가입
        $tmpCode[] = date("y");   # 가입년도
        $tmpCode[] = date("m");   # 가입월
        $tmpCode[] = "M";         # 권한 (M:master)
        $tmpCode[] = "01";        # 가입국가 (임시로 01)

        $tmpCode = join("", $tmpCode);

        // 기존코드 가져오기
        $query = "
            SELECT
                USER_CODE
            FROM
                USER
            WHERE
                USER_EMAIL = '" . $userEmail . "'
        ";
        $resultCode = $this->db->simple_query($query);

        // 뒤 코드 생성
        $query = "
            SELECT
                SUBSTRING( MAX(USER_CODE), -4 )
            FROM
                USER
            WHERE
                SUBSTRING(USER_CODE, 1, 8) = '" . $tmpCode . "'
        ";
        $tmpIdx = $this->db->simple_query($query);
        $tmpIdx++;
        $tmpIdx = str_pad($tmpIdx, 4, "0", STR_PAD_LEFT);

        // 코드 합치기
        $resultCode = $tmpCode . $tmpIdx;

        // 코드 중복 검사 실행
        $resultCode = $this->dupCheckCode( $resultCode, $userEmail );

        if ( !$resultCode ) $this->getUserCode( $userEmail );
        else return $resultCode;
    }


    /**
     * 생성된 각종 회원코드 중복 검사
     *
     * 생성된 코드가 중복일때 회원코드 생성부터 다시 실행함.
     *
     * $type
     *   - tmpUserCode : 임시 회원코드
     *   - defUserCode : 정식 회원코드
     * $userCode
     *   - 검사할 코드번호
    */
    private function dupCheckCode( $userCode, $userEmail )
    {

        $query = "
            SELECT
                COUNT(USER_CODE)
            FROM
                USER
            WHERE
                USER_CODE = '" . $userCode . "'
        ";



        // 사용 가능
        if ( !$this->db->simple_query($query) ) {
            return $userCode;

        // 중복 (재귀 호출)
        } else {
            $this->getUserCode( $userEmail );
            return false;
        }
    }
}
