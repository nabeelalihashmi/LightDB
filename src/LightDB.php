<?php

namespace IconicCodes\LightDB;

class LightDB {
    private static $db;
    private static $db_name;
    private static $db_user;
    private static $db_pass;
    private static $db_host;

    public static function getInstance() {
        if (!isset(self::$db)) {
            self::$db = new \PDO(
                'mysql:host=' . self::$db_host . ';dbname=' . self::$db_name,
                self::$db_user,
                self::$db_pass
            );
            self::$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        }
        return self::$db;
    }

    public static function setConfig($db_name, $db_user, $db_pass, $db_host) {
        self::$db_name = $db_name;
        self::$db_user = $db_user;
        self::$db_pass = $db_pass;
        self::$db_host = $db_host;
    }

    public static function query($query, $args = []) {
        $stmt = self::execute($query, $args);
        return $stmt->fetchAll();
    }

    public static function execute($query, $args = []) {
        $stmt = self::getInstance()->prepare($query);
        $stmt->execute($args);
        return $stmt;
    }

    public static function queryOne($query, $args = []) {
        $stmt = self::execute($query, $args);
        return $stmt->fetch();
    }

    public static function findById($table, $id, $cols = ['*']) {
        $cols = implode(',', $cols);
        $query = 'SELECT ' . $cols .  ' FROM ' . $table . ' WHERE id = ? LIMIT 1';
        return self::queryOne($query, [$id]);
    }

    public static function find($table, $extra_condition = ' AND 1 == ?', $args = [1], $cols = ['*']) {
        $cols = implode(',', $cols);
        $query = 'SELECT ' . $cols .  ' FROM ' . $table . ' WHERE ' . $extra_condition;
        return self::query($query, $args);
    }

    public static function findOne($table, $extra_condition = ' AND 1 == ?', $args = [1], $cols = ['*']) {
        $cols = implode(',', $cols);
        $query = 'SELECT ' . $cols .  ' FROM ' . $table . ' WHERE ' . $extra_condition;
        return self::queryOne($query, $args);
    }

    public static function insert($table, $data) {
        $query = 'INSERT INTO ' . $table . ' (' . implode(', ', array_keys($data)) . ') VALUES (' . implode(', ', array_fill(0, count($data), '?')) . ')';
        self::execute($query, array_values($data));
        return self::getInstance()->lastInsertId();
    }

    public static function insertAll($table, $data) {
        $query = 'INSERT INTO ' . $table . ' (' . implode(', ', array_keys($data[0])) . ') VALUES (' . implode(', ', array_fill(0, count($data[0]), '?')) . ')';
        $instance = self::getInstance();
        $stmt = $instance->prepare($query);
        $insertedIds = [];
        $instance->beginTransaction();
        foreach ($data as $row) {
            $stmt->execute(array_values($row));
            $insertedIds[] = $instance->lastInsertId();
        }
        $instance->commit();
        return $insertedIds;
    }
    
    public static function update($table, $conditions = "", $condition_args = [], $data = []) {
        $args = [];
        $query = 'UPDATE ' . $table . ' SET ' . implode(', ', array_map(function ($key) use ($data, &$args) {
            if (strpos($key, '..') !== false) {
                return str_replace('..', '', $key) . ' = ' . $data[$key];
            }
            $args[] = $data[$key];
            return $key . ' = ?';
        }, array_keys($data))) . ' WHERE ' . $conditions;
        $args = array_merge($args, $condition_args);
        $stmt = self::execute($query, $args);
        return $stmt->rowCount();
    }

    public static function deleteById($table, $id) {
        $query = 'DELETE FROM ' . $table . ' WHERE id = ?';
        $args = [$id];
        $stmt = self::execute($query, $args);
        return $stmt->rowCount();
    }

    public static function delete($table, $condition = '1 == 1', $args = []) {
        $query = 'DELETE FROM ' . $table . ' WHERE ' . $condition;
        $stmt = self::execute($query, $args);
        return $stmt->rowCount();
    }


    public static function count($table, $extra_condition = ' AND 1 == ?', $args = [1]) {
        $query = 'SELECT COUNT(*) FROM ' . $table . ' WHERE ' . $extra_condition;
        $stmt = self::getInstance()->prepare($query);
        $stmt->execute($args);
        return $stmt->fetchColumn();
    }

    public static function updateOrInsert($table, $condition, $condition_args, $data) {
        $count = self::count($table, $condition, $condition_args);
        if ($count > 0) {
            return self::update($table, $condition, $condition_args, $data);
        } else {
            return self::insert($table, $data);
        }
    }
}
