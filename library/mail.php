<?php

/**
 * This mail class is an simple extenion for PHPMailer
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 *
 * @todo add attachment parameter
 */
final class Mail {

    private $_phpmailer;

    function __construct() {
        require(DIR_LIBRARY . 'external/phpmailer/PHPMailerAutoload.php');
        $this->_phpmailer = new PHPMailer();
    }

    public function validateEmail($email) {
        return $this->_phpmailer->validateAddress($email);
    }

    public function send($email, $name, $subject, $body) {

        if (Settings::get('use_smtp')) {
            $this->_phpmailer->IsSMTP();
            $this->_phpmailer->Host = Settings::get('smtp_server');
            $this->_phpmailer->SMTPAuth = true;
            $this->_phpmailer->Username = Settings::get('smtp_username');
            $this->_phpmailer->Password = Settings::get('smtp_password');
        }

        $this->_phpmailer->AddAddress(Settings::get('smtp_username'), Settings::get('smtp_fromname'));

        $this->_phpmailer->From = $email;
        $this->_phpmailer->FromName = $name;

        $this->_phpmailer->WordWrap = 100;
        $this->_phpmailer->IsHTML(true);

        $this->_phpmailer->Subject = $subject;
        $this->_phpmailer->Body = $body;

        if (!$this->_phpmailer->Send()) {
            return $this->_phpmailer->ErrorInfo;
        } else {
            return true;
        }
    }
}
