<?php

/**
 * This class will provide session functions
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 * @last-modified 10-09-2014
 *
 */
final class Session {

    private $_sessionid;
    private $_ipaddress;
    private $_useragent;
    private $_lastactivty;

    public function __construct() {

        $this->_ipaddress = $_SERVER['REMOTE_ADDR'];
        $this->_useragent = $_SERVER['HTTP_USER_AGENT'];
        $this->_lastactivty = time();

        if (!empty($_SESSION['session_id'])) { // update session
            $this->_sessionid = $_SESSION['session_id'];
        } else { // start new session
            $this->_sessionid = $this->generateUniqueId();
            $_SESSION['session_id'] = $this->_sessionid;
        }
    }

    public function updateUserData($userData) {

        $tmp_userData = array_merge(
            $this->getUserData(true),
            $userData
        );

        Database::update("session",
            array(
                'user_data' => json_encode($tmp_userData)
            ),
            array(
                'session_id' => $_SESSION['session_id']
            )
        );
    }

    public function insertUserData($userData) {

        return Database::insert("session",
            array(
                'user_data' => $userData
            )
        );
    }

    public function deleteUserData($key = false) {

        if (!empty($key)) {

            // delete by key
        } else {

            // delete all
        }
    }

    public function getUserData($array = false, $session_id = null) {

        $tmp_session_id = ($session_id) ?: $_SESSION['session_id'];
        $result = Database::select("SELECT user_data FROM {DB_PREFIX}session WHERE `session_id` = :session_id LIMIT 1",
            array(
                'session_id' => $tmp_session_id //$_SESSION['session_id']
            )
        );

        if (count($result) > 0) {
            return json_decode($result[0]->user_data, $array);
        } else {
            return false;
        }
    }

    private function generateUniqueId() {

        return md5(
            $this->_ipaddress .
            $this->_useragent
        );
    }
}
