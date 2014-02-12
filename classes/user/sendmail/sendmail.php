<?php

/**
 * 메일 발송기
 *
 * 메일 발송 클래스
 *
 * @author  daekyu.seo dkseo@pentasecurity.com
 * @copyright   2014 Penta Security
*/

namespace classes\user\sendmail;


class sendmail {
	protected $smtp_server;
	protected $smtp_port;
	protected $smtp_user;
	protected $smtp_passwd;
	protected $smtp_sock;

	public $name;
	public $from;
	public $to;
    public $Cc;
	public $subject;
	public $body;
	public $html;
	public $charset;

	// server setting
	public function __construct(){
		$this->smtp_server = "14.63.215.59";   // 메일서버아이피 또는 도메인을 입력하세요
		$this->smtp_port = "25";				//smtp port
		$this->smtp_user = "webadmin";		// 메일을 보낼수 있는 계정(아이디)를 입력하세요
		$this->smtp_passwd = "webadmin!234";		//smtp 비밀번호 입력
        $this->charset = "UTF-8";		//smtp 비밀번호 입력

		if (!$this->smtp_sock = fsockopen($this->smtp_server, $this->smtp_port)) {
			die ("ERROR !!! \n");
		}
	}

	// encoding
	public function encoding(){
		$this->name = iconv($this->charset, "EUC-KR", $this->name);
		$this->subject = "=?".$this->charset."?B?".base64_encode($this->subject)."?=";
		//$this->body = iconv($this->charset, "EUC-KR", $this->body);
	}

	// send start
	public function sending(){
        /*
         * 자체 sendmail을 구축하여 서버에서 발송 가능할 경우 사용
         *

        $this->encoding();

        $headers  = "From: ".$this->name." <".$this->from.">\n";
        $headers .= "X-Mailer: miplus\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Subject: test mail-".date('YmdHis')."\n";
        $headers .= "To: " . $this->to . "\n";
        if( isset($this->Cc) )
            $headers .= "CC: " . $this->Cc . "\n";

        $result=mail($this->to, $this->subject, $this->body, $headers);
        */


        /**
         * cuo.co.kr SendMail Server를 이용하여 메일 발송
         *
         */
		fputs($this->smtp_sock, "EHLO ".$this->smtp_server."\n");
		fputs($this->smtp_sock, "AUTH LOGIN\n");
		fputs($this->smtp_sock, base64_encode($this->smtp_user)."\n");
		fputs($this->smtp_sock, base64_encode($this->smtp_passwd)."\n");

		fputs($this->smtp_sock, "HELO ".$this->smtp_server."\n");
		fputs($this->smtp_sock, "VRFY ".$this->smtp_user."\n");
		fputs($this->smtp_sock, "MAIL FROM:".$this->from."\n");
		fputs($this->smtp_sock, "RCPT TO:".$this->to."\n");
		fputs($this->smtp_sock, "DATA\n");
		fputs($this->smtp_sock, "From: ".$this->name."<".$this->from.">\n");
		fputs($this->smtp_sock, "X-Mailer: miplus\n");
		if ($this->html) fputs($this->smtp_sock, "Content-Type: text/html;");
		else fputs($this->smtp_sock, "Content-Type: text/plain;");
		fputs($this->smtp_sock, "charset=".$this->charset."\n");
		fputs($this->smtp_sock, "MIME-Version: 1.0\n");
		fputs($this->smtp_sock, "Subject: ".$this->subject."\n");
		fputs($this->smtp_sock, "To: ".$this->to."\n");
		fputs($this->smtp_sock, $this->body);
		fputs($this->smtp_sock, "\n.\nQUIT\n");

	}

	// close
	public function close(){
		fclose($this->smtp_sock);
	}
}
?>