<?php

use PHPUnit\Framework\TestCase;

class MapFunctionsTest extends TestCase {

    private $pdo;

    protected function setUp(): void {
        // Charger la configuration de la base de données
        $config = require __DIR__ . '/../database/config_db.php';

        $db_host = $config['db_host'];
        $db_name = $config['db_name'];
        $db_user = $config['db_user'];
        $db_pass = $config['db_pass'];

        // Configurer la connexion à la base de données
        $this->pdo = new PDO("pgsql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Nettoyer la table avant chaque test
        $this->pdo->exec("DELETE FROM public.certificats");

        // Simuler les données POST
        $_POST['numcf'] = '123';
        $_POST['geom'] = 'POINT(1 1)';
        $_POST['numdemande'] = '456';
        $_POST['surface'] = 100;
        $_POST['observation'] = 'Test observation';
        $_POST['remarque'] = 'Test remarque';

        // Simuler les données SESSION
        $_SESSION['id_user'] = 1;

        // Simuler la méthode de requête HTTP
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Inclure le fichier de fonctions
        require_once(__DIR__ . '/../maps/functions.php');
    }

    public function testCertificatsDemande() {
        ob_start();

        $_POST['numcf'] = '123';
        $_POST['geom'] = 'POINT(1 1)';
        $_POST['numdemande'] = '456';
        $_POST['surface'] = 100;
        $_POST['observation'] = 'Test observation';

        certificatsDemande($this->pdo);

        $stmt = $this->pdo->prepare("
            SELECT numcf, ST_AsText(geom) AS geom, numdemande, surface, observation, id_utilisateur, updated_or_new 
            FROM public.certificats 
            WHERE numcf = :numcf
        ");
        $stmt->execute(['numcf' => '123']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        ob_end_clean();

        $this->assertNotFalse($result, 'Aucune ligne trouvée dans la base de données.');
        $this->assertEquals('123', $result['numcf']);
        $this->assertEquals('MULTIPOINT((1 1))', $result['geom']);
        $this->assertEquals('456', $result['numdemande']);
        $this->assertEquals(100, $result['surface']);
        $this->assertEquals('Test observation', $result['observation']);
        $this->assertEquals(1, $result['id_utilisateur']);
        $this->assertTrue((bool)$result['updated_or_new']);
    }

    public function testPermisDemande() {
        ob_start();

        $_POST['numcf'] = '123';
        $_POST['geom'] = 'POINT(1 1)';
        $_POST['numdemande'] = '456';
        $_POST['surface'] = 100;
        $_POST['observation'] = 'Test observation';

        permisDemande($this->pdo);

        $stmt = $this->pdo->prepare("
            SELECT numcf, ST_AsText(geom) AS geom, numdemande, surface, observation, id_utilisateur, updated_or_new 
            FROM public.certificats 
            WHERE numcf = :numcf
        ");
        $stmt->execute(['numcf' => '123']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        ob_end_clean();

        $this->assertNotFalse($result, 'Aucune ligne trouvée dans la base de données.');
        $this->assertEquals('123', $result['numcf']);
        $this->assertEquals('MULTIPOINT((1 1))', $result['geom']);
        $this->assertEquals('456', $result['numdemande']);
        $this->assertEquals(100, $result['surface']);
        $this->assertEquals('Test observation', $result['observation']);
        $this->assertEquals(1, $result['id_utilisateur']);
        $this->assertFalse((bool)$result['updated_or_new']);
    }

    public function testDemandArectifier() {
        // Insérer une ligne de test dans la table certificats et récupérer l'ID généré
        $this->pdo->exec("INSERT INTO public.certificats (numcf, geom, numdemande, surface, observation, id_utilisateur, updated_or_new) VALUES ('123', ST_Multi(ST_GeomFromText('POINT(1 1)', 29702)), '456', 100, 'Test observation', 1, true)");
        $gid = $this->pdo->lastInsertId('public.certificats_gid_seq');

        ob_start();

        $_POST['remarque'] = 'Test remarque'; // Définir une valeur pour $_POST['remarque']

        DemandArectifier($gid, $this->pdo); // Appeler la fonction avec le PDO mock

        $stmt = $this->pdo->prepare("
            SELECT gid, observation, a_rectifier, updated_or_new 
            FROM public.certificats 
            WHERE gid = :gid
        ");
        $stmt->execute(['gid' => $gid]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        ob_end_clean();

        $this->assertNotFalse($result, 'Aucune ligne trouvée dans la base de données.');
        $this->assertEquals('Test remarque', $result['observation']);
        $this->assertTrue((bool)$result['a_rectifier']);
        $this->assertNull($result['updated_or_new']);
    }

    public function testDemandValider() {
        // Insérer une ligne de test dans la table certificats et récupérer l'ID généré
        $this->pdo->exec("INSERT INTO public.certificats (numcf, geom, numdemande, surface, observation, id_utilisateur, updated_or_new) VALUES ('123', ST_Multi(ST_GeomFromText('POINT(1 1)', 29702)), '456', 100, 'Test observation', 1, true)");
        $gid = $this->pdo->lastInsertId('public.certificats_gid_seq');

        ob_start();

        $_POST['remarque'] = 'Test remarque'; // Définir une valeur pour $_POST['remarque']

        DemandValider($gid, $this->pdo); // Appeler la fonction avec le PDO mock

        $stmt = $this->pdo->prepare("
            SELECT gid, observation, validee_publiee, a_rectifier, updated_or_new 
            FROM public.certificats 
            WHERE gid = :gid
        ");
        $stmt->execute(['gid' => $gid]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        ob_end_clean();

        $this->assertNotFalse($result, 'Aucune ligne trouvée dans la base de données.');
        $this->assertEquals('Test remarque', $result['observation']);
        $this->assertTrue((bool)$result['validee_publiee']);
        $this->assertNull($result['a_rectifier']);
        $this->assertNull($result['updated_or_new']);
    }

    public function testDemandRefuser() {
        // Insérer une ligne de test dans la table certificats et récupérer l'ID généré
        $this->pdo->exec("INSERT INTO public.certificats (numcf, geom, numdemande, surface, observation, id_utilisateur, updated_or_new) VALUES ('123', ST_Multi(ST_GeomFromText('POINT(1 1)', 29702)), '456', 100, 'Test observation', 1, true)");
        $gid = $this->pdo->lastInsertId('public.certificats_gid_seq');

        ob_start();

        $_POST['remarque'] = 'Test remarque'; // Définir une valeur pour $_POST['remarque']

        DemandRefuser($gid, $this->pdo); // Appeler la fonction avec le PDO mock

        $stmt = $this->pdo->prepare("
            SELECT gid, observation, validee_publiee, a_rectifier, updated_or_new 
            FROM public.certificats 
            WHERE gid = :gid
        ");
        $stmt->execute(['gid' => $gid]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        ob_end_clean();

        $this->assertNotFalse($result, 'Aucune ligne trouvée dans la base de données.');
        $this->assertEquals('Test remarque', $result['observation']);
        $this->assertFalse((bool)$result['validee_publiee']);
        $this->assertNull($result['a_rectifier']);
        $this->assertNull($result['updated_or_new']);
    }

    

    protected function tearDown(): void {
        // Nettoyer la table après chaque test
        $this->pdo->exec("DELETE FROM public.certificats");
    }
}
?>
