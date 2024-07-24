<?php

require_once(__DIR__ . '/../models/UserModel.php');

use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase {
    public function testVerifyCredentialsWithCorrectCredentials() {
        $userModel = new UserModel();
        $this->assertTrue($userModel->verifyCredentials('rakoto', '123456',true));
        // Ajoutez ici des assertions supplémentaires pour vérifier la session
    }

    public function testVerifyCredentialsWithIncorrectCredentials() {
        $userModel = new UserModel();
        $this->assertFalse($userModel->verifyCredentials('invalid_username', 'invalid_password',true));
    }

    // Ajoutez d'autres méthodes de test selon les besoins, par exemple pour tester la session
}
?>
