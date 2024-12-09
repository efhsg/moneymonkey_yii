<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['dsn'] = 'sqlite:' . __DIR__ . '/../tests/_data/test.db';

return $db;
