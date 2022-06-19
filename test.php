<?php

use IconicCodes\LightDB\LightDB;


include 'vendor/autoload.php';
LightDB::setConfig('testdb', 'root', '', 'localhost:3306');

// drop table if exists
$query = "drop table if exists south_asian_countries";
$res = LightDB::execute($query);

// create table
$query = "CREATE TABLE IF NOT EXISTS `south_asian_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(255) NOT NULL,
  `capital` varchar(255) NOT NULL,
  `population` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
$res = LightDB::query($query);


// Insert 1 row
$country1 = [
    'country' => 'Pakistan',
    'capital' => 'Islamabad',
    'population' => 20000000
];
$id = LightDB::insert('south_asian_countries', $country1);
test($id, 1, 'Inserting One Row');


// rest South Asian Countries
$data = [
    [
        'country' => 'Bangladesh',
        'capital' => 'Dhaka',
        'population' => 20000000
    ],
    [
        'country' => 'India',
        'capital' => 'New Delhi',
        'population' => 20000000
    ],
    [
        'country' => 'Nepal',
        'capital' => 'Kathmandu',
        'population' => 20000000
    ],
    [
        'country' => 'Bhutan',
        'capital' => 'Thimphu',
        'population' => 20000000
    ],
    [
        'country' => 'Maldives',
        'capital' => 'Male',
        'population' => 20000000
    ],
    [
        'country' => 'Sri Lanka',
        'capital' => 'Colombo',
        'population' => 20000000
    ]
];

$ids = LightDB::insertAll('south_asian_countries', $data);
test(json_encode($ids), json_encode(["2","3","4","5","6","7"]) , 'Inserting Multiple Rows');

$result = LightDB::findById('south_asian_countries', 1, ['country']);
test($result['country'], $country1['country'], 'Find First Country');


$result = LightDB::find('south_asian_countries', 'country like ?', ['b%'], ['country', 'capital', 'population']);
$data = [$data[0], $data[3] ];
test(json_encode($result), json_encode($data), 'Find Countries Country');


$count = LightDB::count('south_asian_countries', 'country like ?', ['b%']);
test($count, 2, 'Counting Rows');

$item = LightDB::update('south_asian_countries', 'id = ? ', [1] , ['population' => '259998', 'capital' => 'Kot Sultan']);
test($item, 1, 'Updating Row');

$item = LightDB::update('south_asian_countries', 'country like ?',['b%'], ['population' => 199999]);
test($item, 2, 'Updating Row By Conditions');

$item = LightDB::update('south_asian_countries', 'id = ? ', [1] , ['population..' => 'population + 1', 'capital' => 'Kot Sultan']);
test($item, 1, 'Updating Row');

$item = LightDB::updateOrInsert('south_asian_countries', 'country like ?', ['Pakistan'], ['population..' => 'population + 1']);


function test($value, $expected, $message) {

    echo "\nTest: " . $message . "\n";
    echo "Expected: " . $expected . "\n";
    echo "Actual: " . $value . "\n";

    if ($value == $expected) {
        // green background white text tick icon in start
        echo "\033[42m\033[37m\033[1m✓ Passed \033[0m\n";
        
    } else {
        // red background white text cross icon in start
        echo "\033[41m\033[37m\033[1m✗ Failed \033[0m\n";
    }

    echo "\n";
}