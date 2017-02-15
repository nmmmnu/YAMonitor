<?php
namespace monitor;

require_once __DIR__ . "/classes/monitoring.php";

$SENSORS = __DIR__ . "/sensors/";

require_once $SENSORS . "diskfree.php";
require_once $SENSORS . "la.php";
require_once $SENSORS . "memory.php";
require_once $SENSORS . "apachescoreboard.php";
require_once $SENSORS . "nginxstatus.php";
require_once $SENSORS . "mysqlstatus.php";
require_once $SENSORS . "sphinxsearch.php";
require_once $SENSORS . "redissensor.php";

$STORAGE = __DIR__ . "/storage/";

require_once $STORAGE . "federated.php";
require_once $STORAGE . "console.php";
require_once $STORAGE . "pdostorage.php";
require_once $STORAGE . "redisstorage.php";

$istorage = new storage\Federated(
//	new storage\PDOStorage("myhost")	,
//	new storage\RedisStorage("MY_MONITOR")	,
	new storage\Console
);

$monitors = new Monitoring( $istorage );

$monitors->add("df1",		new sensors\DiskFree("/")						);
$monitors->add("df2",		new sensors\DiskFree("/DATA")						);
$monitors->add("la",		new sensors\LA								);
$monitors->add("mem",		new sensors\Memory							);
/*
$monitors->add("httpd",		new sensors\ApacheScoreboard("http://127.0.0.1/server-status/?auto")	);
$monitors->add("nginx",		new sensors\NginxStatus("http://127.0.0.1/nginx-status/")		);
$monitors->add("mysql",		new sensors\MySQLStatus("127.0.0.1", "root", "secret")			);
$monitors->add("sphinx",	new sensors\SphinxSearch("myindex", "127.0.0.1:9305", "root")		);
$monitors->add("redis",		new sensors\Redis("127.0.0.1")						);
*/
// here I use some stuff with passwords
include "factory_monitors_hidden.php";

return $monitors;
