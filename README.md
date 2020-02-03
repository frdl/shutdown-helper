# shutdown-helper
Reverse order register_shutdown manager

## Installation
````
$ composer install frdl/shutdown-helper
````

## Usage
````php
$ShutdownTasks = \frdlweb\Thread\ShutdownTasks::mutex();
$ShutdownTasks(function($start){
	print_r( date('c', time()).' execution microtime >= '.microtime() - $start );
}, microtime());

````
