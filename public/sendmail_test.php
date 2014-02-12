<?php
define('CRLF', "\r\n");
	$to      = 'dkseo@pentasecurity.com';	// 보낼 주소
	$subject = '=?UTF-8?B?' . base64_encode('Gmail 을 이용한 Sendmail') . '?=';	// 제목
	$strMessge = '이 메일은 Gmail 을 이용하여 보낸 메일입니다. ';	// 내용
	$message = chunk_split(base64_encode($strMessge));	// 메세지
	// 헤더 설정
	$headers = 'From: dkseo@pentasecurity.com' . CRLF .
			'Reply-To: dkseo@pentasecurity.com' . CRLF .
			'MIME-Version: 1.0' . CRLF .
			'Content-type: text/html; charset=utf/8' . CRLF .
			'Content-Transfer-Encoding: base64' . CRLF;
	// 메일 보내기
	mail($to, $subject, $message, $headers);
