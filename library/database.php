<?php

/**
 * Functions to query the database or use other database functions
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 *
 * @todo change all this to stmt prepared statements and multiple functions
 */
final class Database {

    /**
     * The variable that holds the MySQL connection
     */
    private $connection;

    /**
     * Initialize the mysql database link
     */
    public function __construct() {
        try {
            $this->connection = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_DBNAME . ';charset=utf8;', DB_USER, DB_PASS);
        } catch(PDOException $e) {
            Log::error("MySQL connection failed: {$e->getMessage()}\n", "<b>MySQL connection failed</b>: {$e->getMessage()}<br />", true);
            exit();
        }
    }

    /**
     * Execute an sql query
     *
     * @param string $sql
     * @param int    $fetch_style
     *
     * @return array or boolean
     */
    public function query($sql, $fetch_style = PDO::FETCH_ASSOC) {

        $sql = str_replace('{db_prefix}', DB_PREFIX, $sql);
        $this->connection->prepare($sql);
        $result = $this->connection->query($sql);

        if (is_object($result)) {
            if ($result->rowCount() > 0) {
                $row_count = 0;
                $rows = array();
                while($row = $result->fetch($fetch_style)) {
                    $rows[] = $row;
                    $row_count++;
                }

                $data = new stdClass();
                $data->row = $rows[0];
                $data->rows = $rows;
                $data->count = $row_count;
            } else {
                return false;
            }

            return $data;
        } else {
            return $result;
        }
    }

    /**
     * Returns a escaped mysql string
     *
     * @param string $value - the string to be escaped
     *
     * @return string
     */
    public function escape($value) {
        return $this->connection->quote($value);
    }

    /**
     * Returns the last inserted id from the database
     *
     * @return int
     */
    public function getLastInsertedId() {
        return $this->connection->lastInsertId();
    }
}
