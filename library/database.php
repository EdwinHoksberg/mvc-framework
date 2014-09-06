<?php

/**
 * Use this class to execute various database functions
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 *
 */
final class Database extends PDO {

    /**
     * Initialize the mysql database link
     */
    public function __construct() {
        try {
            parent::__construct(DB_DRIVER . ':host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_DBNAME . ';charset=utf8;', DB_USER, DB_PASS);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            Log::error("MySQL connection failed: {$e->getMessage()}\n", "<b>MySQL connection failed</b>: {$e->getMessage()}<br />", true);
            exit();
        }
    }

    public function select($sql, $data = array(), $fetchMode = PDO::FETCH_OBJ) {

        $stmt = $this->prepare($sql);
        foreach ($data as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        $stmt->execute();

        return $stmt->fetchAll($fetchMode);
    }

    public function insert($table, $data) {

        ksort($data);

        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $stmt = $this->prepare("INSERT INTO ". DB_PREFIX ."{$table} ({$fieldNames}) VALUES ({$fieldValues})");
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function update($table, $data, $where) {

        ksort($data);
        ksort($where);

        $fieldDetails = NULL;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = :$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $whereDetails = NULL; $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :$key";
            } else {
                $whereDetails .= " AND $key = :$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        $stmt = $this->prepare("UPDATE ". DB_PREFIX ."{$table} SET {$fieldDetails} WHERE {$whereDetails}");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function delete($table, $where, $limit = 1) {

        ksort($where);

        $whereDetails = NULL; $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :$key";
            } else {
                $whereDetails .= " AND $key = :$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        $stmt = $this->prepare("DELETE FROM ". DB_PREFIX ."$table WHERE $whereDetails LIMIT $limit");

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function truncate($table) {
        return $this->exec("TRUNCATE TABLE ". DB_PREFIX ."{$table}");
    }

    /**
     * Returns the last inserted id from the database
     *
     * @return int
     */
    public function getLastInsertedId() {
        return $this->lastInsertId();
    }
}
