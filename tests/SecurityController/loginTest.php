<?php
namespace App\Tests\Controller;

use App\Entity\Article;
use App\Repository\JournalistRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase {
    
    public function testLoginPage() {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testLoginWithTestJournalist() {
        $client = static::createClient();

        $journalistRepository = static::$container->get(JournalistRepository::class);
        // on récupère le user test créé par la fixtures
        $testUser = $journalistRepository->findOneByEmail('test@gmail.com');
        // on se connecte avec le login du user test
        $client->loginUser($testUser);
        // on tente d'accéder à une page réservée aux users connectés
        $client->request('GET', '/add/article');

        $this->assertResponseIsSuccessful();
    }

    public function testLoginAndGetRedirected() {
        $client = static::createClient();

        $journalistRepository = static::$container->get(JournalistRepository::class);
        // on récupère le user test créé par la fixtures
        $testUser = $journalistRepository->findOneByEmail('test@gmail.com');
        // on se connecte avec le login du user test
        $client->loginUser($testUser);
        // on tente d'accéder à une page réservée aux users connectés
        $client->request('GET', '/login');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

//      public function testSubmitValidData() {
//         $formData = ['title' => 'Le titre', 'text' => 'Le texte', 'picture' => 'article.png'];
//         $model = new Article();

//         $form = $this->factory->create(TestedType::class, $model);
//         $expected = new Article();
//         $form->submit($formData);
//         $this->assertTrue($form->isSynchronized());
//         $this->assertEquals($expected, $model);

//     }
}
?>
