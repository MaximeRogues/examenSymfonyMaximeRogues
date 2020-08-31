<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Journalist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;

    }

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 20; $i++) {
            $journalist = new Journalist();
            $journalist->setEmail(rand(1,99999) . '@gmail.com');
            $journalist->setRoles(['ROLE_USER', 'IS_ANONYMOUS']);

            $password = $this->encoder->encodePassword($journalist, 'Severus');
            $journalist->setPassword($password);    
            
            $manager->persist($journalist);
        }

        // journaliste pour tests fonctionnels
        $testJournalist = new Journalist();
        $testJournalist->setEmail('test@gmail.com');
        $testJournalist->setRoles(['ROLE_USER', 'IS_ANONYMOUS']);

        $password = $this->encoder->encodePassword($testJournalist, 'test123@');
        $testJournalist->setPassword($password);    
            
        $manager->persist($testJournalist);

        $titres = ['Les castors lapons sont-ils hermaphrodites ?', 'Il tuait ses victimes à la saucisse et au marteau', 'Il tuait ses victimes à la fauçille et au marteau', 'Il tuait ses victimes à la saucisse de Morteaux', 'Accident de mimes: 0 morts 0 blessés', 'La carioca: encore un franc succès'];
        for($i = 0; $i < 30; $i++) {
            $article = new Article();
            $article->setTitle($titres[rand(0, 5)]);
            $article->setText('N\'importe quoi, de toute façon c\'est pour remplir, un peu comme un lorem au final. C\'est sûr que ça aurait été plus simple avec une commande qui s\'appelerait genre \'Lorem\', mais si elle existe je l\'ai pas trouvé, en même temps j\'ai pas hyper hyper cherché non plus donc bon...');
            $article->setPicture('article.png');

            $manager->persist($article);
        }

        $manager->flush();
    }
}
