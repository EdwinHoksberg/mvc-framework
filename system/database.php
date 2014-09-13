<?php

/**
 * Use this class to execute various database functions
 *
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @version 1.0
 * @date 31-08-2014
 *
 */
class Database {

    protected static $db;

    /**
     * Initialize the mysql database link
     */
    public static function initialize() {
        try {
            self::$db = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';port=' . DB_PORT . ';charset=' . str_replace('-', '', CHARSET) . ';', DB_USER, DB_PASS);
            if (self::$db->exec("USE " . DB_DBNAME) === false) {
                Log::error("Database " . DB_DBNAME . " not found!\n", "<h1>Database '" . DB_DBNAME . "' not found!</h1><br />", true);
            }
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            var_dump($e->getMessage());
            Log::error("MySQL connection failed: {$e->getMessage()}\n", "<h1>MySQL connection failed</h1>: {$e->getMessage()}<br />", true);
        }
    }

    public static function select($sql, $data = array(), $fetchMode = PDO::FETCH_OBJ) {

        if (!self::$db) {
            self::initialize();
        }

        $sql = str_replace("{DB_PREFIX}", DB_PREFIX, $sql);

        $stmt = self::$db->prepare($sql);
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

    public static function insert($table, $data) {

        if (!self::$db) {
            self::initialize();
        }

        ksort($data);

        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $stmt = self::$db->prepare("INSERT INTO ". DB_PREFIX ."{$table} ({$fieldNames}) VALUES ({$fieldValues})");
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public static function update($table, $data, $where) {

        if (!self::$db) {
            self::initialize();
        }

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

        $stmt = self::$db->prepare("UPDATE ". DB_PREFIX ."{$table} SET {$fieldDetails} WHERE {$whereDetails}");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public static function delete($table, $where, $limit = 1) {

        if (!self::$db) {
            self::initialize();
        }

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

        $stmt = self::$db->prepare("DELETE FROM ". DB_PREFIX ."$table WHERE $whereDetails LIMIT $limit");

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public static function truncate($table) {
        if (!self::$db) {
            self::initialize();
        }

        return self::$db->exec("TRUNCATE TABLE ". DB_PREFIX ."{$table}");
    }

    /**
     * Returns the last inserted id from the database
     *
     * @return int
     */
    public static function getLastInsertedId() {
        if (!self::$db) {
            self::initialize();
        }

        return self::$db->lastInsertId();
    }
}
