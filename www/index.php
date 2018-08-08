<?php
print '<pre>';
echo  "hello, world" . PHP_EOL;

$host = $_ENV["HOST"] ?? 'localhost';
$port = $_ENV["PORT"] ?? 3306;
$pw = $_ENV["PW"];
$user = $_ENV["USER"];
$db = $_ENV["DB"];

$memcacheD = new Memcached();
$memcachedServiceDiscovery = $_ENV["MEMCACHED"];
if (isset($memcachedServiceDiscovery)) {
    $result = dns_get_record($memcachedServiceDiscovery, DNS_SRV);
    $servers = [];

    if ($result) {
        foreach ($result as $server) {
            $servers[] = [
                $server['target'],
                $server['port'],
            ];
        }
        
        $memcacheD->addServers($servers);
    }
}

if( $memcacheD->add("mystr","this is a memcache test!",10)){
    echo  'one';
}else{
    echo 'two: '.$memcacheD->get("mystr");
}

$dsn = "mysql:dbname=$db;host=$host;port=$port";

echo $dsn . PHP_EOL;

try {
    $dbh = new PDO($dsn, $user, $pw); 
}
catch (Error $e) {
    var_dump($e->getMessage());
}

$sql = "
    CREATE TABLE IF NOT EXISTS counter (
        cnt INT(11) NOT NULL DEFAULT 0
    )
";
$dbh->query($sql);


$sql = "SELECT COUNT(cnt) as cnt FROM counter";
$q = $dbh->query($sql);
$counter = $q->fetchColumn();
echo "counter: " . $counter;


$sql = "INSERT INTO counter (cnt) VALUES (1)";
$dbh->query($sql);

echo "<hr><div style='text-align:center;'>";
echo "Powered by " . gethostname();
echo "</div>";
echo "alternative 7";