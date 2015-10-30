<?PHP

	/*define('QA_MYSQL_HOSTNAME', '127.0.0.1');
	define('QA_MYSQL_USERNAME', 'qea');
	define('QA_MYSQL_PASSWORD', 'bafio8989_');
	define('QA_MYSQL_DATABASE', 'qea');*/

$servername = "www.universitree.com";
$username = "qea";
$password = "bafio8989_";

try {
    $conn = new PDO("mysql:host=$servername;dbname=qea", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; 
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }

?>