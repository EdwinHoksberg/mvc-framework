<?php

final class Session extends Router {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Start a session, returns true or false
     *
     * @param $userId
     *
     * @return bool
     */
    public function startSession($userId) {

        $session_data = array("userId"     => $userId,
                              "ip_addr"    => $_SERVER['REMOTE_ADDR'],
                              "login_time" => time(),
                              "idle_time"  => time()
        );
        $json_enc = json_encode($session_data);

        // check if user has already an session
        $query = "SELECT * FROM {db_prefix}session;";
        $result = $this->db->query($query);
        if ($result && $result->count > 0) {
            foreach($result->rows as $row) {
                $json_dec = json_decode($row['session_data']);
                if ($json_dec->ip_addr == $_SERVER['REMOTE_ADDR']) {
                    $_SESSION['session_id'] = $row['session_id'];

                    return true;
                }
            }
        }

        // insert session data in database
        $query = "INSERT INTO {db_prefix}session (`session_id`, `session_data`) VALUES (" . "LAST_INSERT_ID(), " . $this->db->escape($json_enc) . ");";
        $result = $this->db->query($query);

        // set $_SESSION id for database lookup
        $_SESSION['session_id'] = $this->db->getLastInsertedId();

        return $result;
    }

    /**
     * Checks if the user has a valid session
     *
     * @param $sessId
     *
     * @return bool
     */
    public function isValidSession($sessId) {

        $SESSION_TIMEOUT = 7200; //seconds, == 2hours

        $query = "SELECT `session_data` FROM {db_prefix}session
            WHERE `session_id` = " . $this->db->escape($sessId) . " LIMIT 1;";
        $result = $this->db->query($query);
        if ($result->count > 0) {
            $session_data = json_decode($result->row['session_data']);

            if ($session_data->idle_time + $SESSION_TIMEOUT < time()) {
                $this->closeSession($sessId);

                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * Updates the idle time for the user, returns true or false
     *
     * @param $sessId
     *
     * @return bool
     */
    public function updateIdleTime($sessId) {

        $session_data = $this->getSessionDataBySessionId($sessId);
        $session_data->idle_time = time();
        $data_enc = json_encode($session_data);

        $query = "UPDATE {db_prefix}session
            SET `session_data` = " . $this->db->escape($data_enc) . "
            WHERE `session_id` = " . $this->db->escape($sessId) . " ;";
        $result = $this->db->query($query);

        return $result;
    }

    /**
     * Closes the session(logs out the user), returns true on succes, false on failure
     *
     * @param $sessId
     *
     * @return bool
     */
    public function closeSession($sessId) {

        $query = "DELETE FROM {db_prefix}session
            WHERE `session_id`=" . $this->db->escape($sessId) . ";";
        $result = $this->db->query($query);

        if (!$result) {
            return false;
        }

        unset($_SESSION['session_id']);

        return session_destroy();
    }

    /**
     * Returns the user session data by session Id
     *
     * @param $sessId
     *
     * @return object
     */
    public function getSessionDataBySessionId($sessId) {
        $sql = "SELECT session_data FROM {db_prefix}session
            WHERE `session_id`=" . $this->db->escape($sessId) . " LIMIT 1;";
        $result = $this->db->query($sql);

        if (!$result) {
            return false;
        }

        $session_data = json_decode($result->row['session_data']);

        return $session_data;
    }

    public function getUserNameBySessionId($sessId) {
        $sql = "SELECT session_data FROM {db_prefix}session
            WHERE `session_id` = " . $this->db->escape($sessId) . " LIMIT 1;";
        $result = $this->db->query($sql);

        $session_data = json_decode($result->row['session_data']);

        $sql = "SELECT username FROM {db_prefix}users
            WHERE `user_id`='" . $session_data->userId . "' LIMIT 1;";
        $result = $this->db->query($sql);

        return $result->row['username'];

    }
}
