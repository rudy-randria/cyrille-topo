<?php
use PHPUnit\Framework\TestCase;

class MapFunctionsTest extends TestCase {

    private $pdo;
    
    protected function setUp(): void {

        $config = require __DIR__ . '/../database/config_db.php';

        $db_host = $config['db_host'];
        $db_name = $config['db_name'];
        $db_user = $config['db_user'];
        $db_pass = $config['db_pass'];

        // Configure the database connection
        $this->pdo = new PDO("pgsql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Clean up the table before each test
        $this->pdo->exec("DELETE FROM public.certificats");

        // Simulate POST data
        $_POST['numcf'] = '123';
        $_POST['geom'] = 'POINT(1 1)';
        $_POST['numdemande'] = '456';
        $_POST['surface'] = 100;
        $_POST['observation'] = 'Test observation';

        // Simulate SESSION data
        $_SESSION['id_user'] = 1;

        // Simulate HTTP request method
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Include the functions file
        require_once(__DIR__ . '/../maps/functions.php');
    }

    public function testCertificatsDemande() {
        // Capture output to avoid header issues
        ob_start();

        // Call the function with the mock PDO
        certificatsDemande($this->pdo);

        // Check database for inserted record
        $stmt = $this->pdo->prepare("SELECT numcf, ST_AsText(geom) AS geom, numdemande, surface, observation, id_utilisateur, updated_or_new FROM public.certificats WHERE numcf = :numcf");
        $stmt->execute(['numcf' => $_POST['numcf']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Clean the output buffer
        ob_end_clean();

        // Assertions to verify the result
        $this->assertNotFalse($result, 'Aucune ligne trouvée dans la base de données.');
        $this->assertEquals('123', $result['numcf']);
        $this->assertEquals('MULTIPOINT((1 1))', $result['geom']);  // Compare with MULTIPOINT
        $this->assertEquals('456', $result['numdemande']);
        $this->assertEquals(100, $result['surface']);
        $this->assertEquals('Test observation', $result['observation']);
        $this->assertEquals(1, $result['id_utilisateur']);
        $this->assertTrue($result['updated_or_new']);
    }

    protected function tearDown(): void {
        // Clean up the table after each test
        $this->pdo->exec("DELETE FROM public.certificats");
    }
}


?>