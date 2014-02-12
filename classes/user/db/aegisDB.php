<?PHP
/**
 * DB connection information
 *
 * DB 커넥션 위한 정보 입력
 *
 * @author  daekyu.seo dkseo@pentasecurity.com
 * @copyright   2014 Penta Security
*/
namespace classes\user\db;

use classes\user\db\dbQueryClass;

class aegisDB extends dbQueryClass {
    public $db_host = "localhost";
    public $db_name = "aegis";
    public $db_user = "aegis";
    public $db_pass = "aegis";

    public function __construct(){
		$this->PcloudDB( $this->db_host, $this->db_user, $this->db_pass, $this->db_name );
	}
}