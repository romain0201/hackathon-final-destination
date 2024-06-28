<?php

namespace App\DataFixtures;

use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $message1 = new Message();
        $message1->setContent('Bonjour, je suis malade');
        $message1->setAuthor($this->getReference('user1'));
        $manager->persist($message1);

        $message2 = new Message();
        $message2->setContent('Bonjour, ou avez-vous mal ?');
        $message2->setAuthor($this->getReference('ia'));
        $manager->persist($message2);

        $message3 = new Message();
        $message3->setContent('J\'ai mal à la tête');
        $message3->setAuthor($this->getReference('user1'));
        $manager->persist($message3);

        $message4 = new Message();
        $message4->setContent('Avez-vous de la fièvre ?');
        $message4->setAuthor($this->getReference('ia'));
        $manager->persist($message4);

        $message5 = new Message();
        $message5->setContent('Oui, j\'ai de la fièvre');
        $message5->setAuthor($this->getReference('user1'));
        $manager->persist($message5);

        $message6 = new Message();
        $message6->setContent('Avez-vous d\'autres symptômes ?');
        $message6->setAuthor($this->getReference('ia'));
        $manager->persist($message6);

        $message7 = new Message();
        $message7->setContent('J\'ai mal au ventre');
        $message7->setAuthor($this->getReference('user1'));
        $manager->persist($message7);

        $message8 = new Message();
        $message8->setContent('Avez-vous des nausées ?');
        $message8->setAuthor($this->getReference('ia'));
        $manager->persist($message8);

        $message9 = new Message();
        $message9->setContent('Oui, j\'ai des nausées');
        $message9->setAuthor($this->getReference('user1'));
        $manager->persist($message9);

        $message10 = new Message();
        $message10->setContent('Avez-vous des douleurs abdominales ?');
        $message10->setAuthor($this->getReference('ia'));
        $manager->persist($message10);

        $message11 = new Message();
        $message11->setContent('Oui, j\'ai des douleurs abdominales. Que dois-je faire ?');
        $message11->setAuthor($this->getReference('user1'));
        $manager->persist($message11);

        $message12 = new Message();
        $message12->setContent('Je vous conseille de prendre un médicament contre la douleur');
        $message12->setAuthor($this->getReference('ia'));
        $manager->persist($message12);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}