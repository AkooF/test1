<?php

require('connect.php');
require('Tree.php');
$table = $pdo->query('SELECT * FROM menu')->fetchAll();
$tree = new Tree($table);
print('Build Tree');
$tree->showAll();
print('Add 3 Node:');
$tree->addNode(5, 'test');
$tree->addNode(5, 'test2');
$tree->addNode(6, 'test3');
$tree->showAll();
print('Del one Node with id: 5');
$tree->delNode(5);
$tree->showAll();
print('Show one Node');
$tree->showNode(6);
print('Search parents name of element with id: 8');
print_r($tree->searchParent(8, 'name'));


