<?php

require 'Classes/Database.php';
require 'Classes/XMLProcessor.php';

$db = new Database("localhost", 'postgres', 'postgres', '2001');
$xmlProcessor = new XMLProcessor($db);
$xmlProcessor->processDirectory('XML1');
$db->fetchAllBooks();