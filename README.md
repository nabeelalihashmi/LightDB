![LightDB](./docs/header.png)

# LightDB

A Fast, Easy, Secure and LightWeight Wrapper for PDO. Supports for easy operation and unique functions like `SET COLUMN = COLUMN + 1`

## About Author
[Nabeel Ali](https://iconiccodes.com)

Website: [https://iconiccodes.com](https://iconiccodes.com)

Email: [mail2nabeelali@gmail.com](mailto:mail2nabeelali@gmail.com)

## Features

    * Fast
    * Easy
    * Lightweight


## Installtion
```
composer require nabeelalihashmi/LightDB
```

## Basic Usage
```
LightDB::setConfig('testdb', 'root', '', 'localhost:3306');

// Insert 1 row
$country1 = [
    'country' => 'Pakistan',
    'capital' => 'Islamabad',
    'population' => 20000000
];
$id = LightDB::insert('south_asian_countries', $country1);

$result = LightDB::findById('south_asian_countries', 1);
$result = LightDB::find('south_asian_countries', 'country like ?', ['b%'], ['country', 'capital', 'population']);

```

### Note

LightDB allows generation of query like
```

with following example

Update table set column = column + 1 where id = 1
```

to have such type of query, put `..` and end of column name in key.

```
$item = LightDB::update('south_asian_countries', 'id = ? ', [1] , ['population..' => 'population + 1']);
```

## Methods
### getInstance

Returns the PDO Object
```
public static function getInstance()
```
### setConfig

* Sets the config for the database
* Args
    * $dbname: Database Name
    * $username: Database Username
    * $password: Database Password
    * $host: Database Host


```
public static function setConfig($db_name, $db_user, $db_pass, $db_host)
```
### query

* This is internally used by functions to run generated query. Can be used by user apps.
* Returns the result of the query in associative array
* Args
    * $query: Query to be executed
    * $params: Parameters to be passed to the query


```
public static function query($query, $args = [])
```
### execute
*This is internally used by functions to run generated query. Can be used by user apps.
*Returns PDO Statement
* Args
    * $query: Query to be executed
    * $params: Parameters to be passed to the query

```
public static function execute($query, $args = [])
```

### queryOne
*This is internally used by functions to run generated query. Can be used by user apps.
*Returns the result of the query in associative array
* Args
    * $query: Query to be executed
    * $params: Parameters to be passed to the query

```
public static function queryOne($query, $args = [])
```
### findById
* Returns row matching id
* Args
    * $table: Table Name
    * $id: ID of the row to be found
    * cols = Array of columns to be returned

```
public static function findById($table, $id, $cols = ['*'])
```

### find

* Returns all records matching condition(s)
* Args
    * $table: Table Name
    * $extra_condition: conditions in form of x = ?
    * $args: Parameters to be passed to the query
    * $cols = Array of columns to be returned

```
public static function find($table, $extra_condition = ' AND 1 == ?', $args = [1], $cols = ['*'])
```

### findOne

Same as find, but returns one record

```
public static function findOne($table, $extra_condition = ' AND 1 == ?', $args = [1], $cols = ['*'])
```

### insert
* Requeire the data in assoc array. The keys should be the column names and the values should be the values to be inserted
* Returns the ID of the inserted row
* Args
    * $table: Table Name
    * $data: Data to be inserted in associative array


```
public static function insert($table, $data)
```

### insertAll
* Add Multiple rows in one go
*Returns the IDs of the inserted rows
* Args
    * $table: Table Name
    * $data: Data to be inserted in associative array

```
public static function insertAll($table, $data)
```

### update
* Updates the record matching condition.
* Args
    * $table: Table Name
    * $condition: conditions in form of x = ?
    * $condition_args: Parameters to be passed to the query
    * $data: Data to be updated in associative array

```
public static function update($table, $conditions = "", $condition_args = [], $data = [])
```

### deleteAll

* Deletes all records matching condition(s)
* Reeturns the number of rows deleted
* Args
    * $table: Table Name
    * $condition: conditions in form of x = ?
    * $condition_args: Parameters to be passed to the query

```
public static function deleteAll($table, $condition = '1 == 1', $args = []) {
``` 

### deleteById
* Deletes the record matching id
* Returns the number of rows deleted
* Args
    * $table: Table Name
    * $id: ID of the row to be deleted


```
public static function deleteById($table, $id)
```
### count
* Returns the number of rows matching condition(s)
* Args
    * $table: Table Name
    * $extra_condition: conditions in form of x = ?
    * $args: Parameters to be passed to the query

```
public static function count($table, $extra_condition = ' AND 1 == ?', $args = [1])
```
### updateOrInsert
* If Record exits, updates, else creates a new record.
* Returns number of updated rows
* Args
    * $table: Table Name
    * $condition: conditions in form of x = ?
    * $condition_args: Parameters to be passed to the query
    * $data: Data to be inserted in associative array

```
public static function updateOrInsert($table, $condition, $condition_args, $data)
```




-------------------------

## License

LightDB is released under permissive licese with following conditions:

* It cannot be used to create adult apps.
* It cannot be used to gambling apps.
* It cannot be used to create apps having hate speech.

### MIT License

Copyright 2022 Nabeel Ali | IconicCodes.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

