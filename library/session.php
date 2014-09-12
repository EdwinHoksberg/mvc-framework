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
    private $_lastactivity;

    /**
     * This constructor will load the session data, and if neccesery create a new session or destroy an old one
     */
    public function __construct() {

        if (!empty($_SESSION['session_id'])) { // update current session

            $this->loadSessionData();
            if (($this->_lastactivity + 7200) <= time()) {
                $this->destroySession();
                $this->newSession();
            } else {
                $this->updateLastActivity();
            }

        } else { // start new session
            $this->newSession();
        }
    }

    /**
     * This function allows you to save data for the user in a database.
     * If $keep_old is specified as false, it will delete all data and insert $userData
     *
     * @param array $userData - An array with the data
     * @param bool $keep_old
     */
    public function setUserData($userData, $keep_old = true) {

        if ($keep_old) {
            $tmp_userData = array_replace(
                $this->getUserData(true),
                $userData
            );
        } else {
            $tmp_userData = $userData;
        }

        Database::update("session",
            array(
                'user_data' => json_encode($tmp_userData)
            ),
            array(
                'session_id' => $this->_sessionid
            )
        );
    }

    /**
     * This function will return an object or an array of the user data
     *
     * @param bool $array - If true, returns an array, else an object
     *
     * @return array|bool
     */
    public function getUserData($array = false) {

        $result = Database::select("SELECT user_data FROM {DB_PREFIX}session WHERE `session_id` = :session_id LIMIT 1",
            array(
                'session_id' => $this->_sessionid
            )
        );

        if (count($result) > 0) {
            return json_decode($result[0]->user_data, $array);
        } elseif ($result == array()) {
            return array();
        } else {
            return false;
        }
    }

    /**
     * This function will delete 1 setting or the whole user data field
     *
     * @param string $key - The optional key to delete
     */
    public function deleteUserData($key = '') {

        if (!empty($key)) {

            $userData = $this->getUserData();
            unset($userData->$key);

            $this->setUserData($userData, false);
        } else {

            $this->setUserData(array(), false);
        }
    }

    /**
     * This function will update the last activity time
     *
     * @return bool
     */
    private function updateLastActivity() {

        return Database::update("session",
            array(
                'last_activity' => time()
            ),
            array(
                'session_id' => $_SESSION['session_id']
            )
        );
    }

    /**
     * This function destroys and deletes all data associated with the current session
     */
    public function destroySession() {

        Database::delete("session",
            array(
                'session_id' => $this->_sessionid
            )
        );

        $_SESSION = array();
        session_destroy();
    }

    /**
     * This function will generate a new session, complete with all the variables
     *
     * @return bool
     */
    private function newSession() {

        $this->_sessionid = $this->generateUniqueId();
        $this->_ipaddress = $_SERVER['REMOTE_ADDR'];
        $this->_useragent = $_SERVER['HTTP_USER_AGENT'];
        $this->_lastactivity = time();

        $_SESSION['session_id'] = $this->_sessionid;

        return Database::insert("session",
            array(
                'session_id' => $this->_sessionid,
                'ip_address' => $this->_ipaddress,
                'user_agent' => $this->_useragent,
                'last_activity' => $this->_lastactivity,
                'user_data' => json_encode(array())
            )
        );
    }

    /**
     * This function will set all the class variables to use in the session functions
     *
     * @return bool
     */
    private function loadSessionData() {

        $result = Database::select("SELECT * FROM {DB_PREFIX}session WHERE `session_id` = :session_id LIMIT 1",
            array(
                'session_id' => $_SESSION['session_id']
            )
        );

        if (count($result) > 0) {
            $result = $result[0];

            $this->_sessionid = $_SESSION['session_id'];
            $this->_ipaddress = $result->ip_address;
            $this->_useragent = $result->user_agent;
            $this->_lastactivity = $result->last_activity;

            return true;
        } else {
            return false;
        }
    }

    /**
     * This function will generate a unique id as session identifier.
     *
     * @return string
     */
    private function generateUniqueId() {

        return md5(
            $this->_ipaddress .
            $this->_useragent .
            microtime()
        );
    }
}
