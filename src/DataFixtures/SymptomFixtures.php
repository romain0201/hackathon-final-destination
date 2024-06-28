<?php

namespace App\DataFixtures;

use app\Entity\Symptom;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SymptomFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $symptom1 = new Symptom();
        $symptom1->setName('Covid-19');
        $symptom1->setCode($this->generateHexaColorCode());
        $symptom1->setActive(false);
        $symptom1->addPatient($this->getReference('user1'));
        $symptom1->addPatient($this->getReference('user2'));
        $manager->persist($symptom1);

        $symptom2 = new Symptom();
        $symptom2->setName('Mal de dos');
        $symptom2->setCode($this->generateHexaColorCode());
        $symptom2->setActive(false);
        $symptom2->addPatient($this->getReference('user2'));
        $manager->persist($symptom2);

        $symptom3 = new Symptom();
        $symptom3->setName('Grippe');
        $symptom3->setCode($this->generateHexaColorCode());
        $symptom3->setActive(false);
        $symptom3->addPatient($this->getReference('user3'));
        $manager->persist($symptom3);

        $symptom4 = new Symptom();
        $symptom4->setName('Ouverture de la main');
        $symptom4->setCode($this->generateHexaColorCode());
        $symptom4->setActive(true);
        $symptom4->addPatient($this->getReference('user1'));
        $manager->persist($symptom4);

        $symptom5 = new Symptom();
        $symptom5->setName('Sang');
        $symptom5->setCode($this->generateHexaColorCode());
        $symptom5->setActive(true);
        $symptom5->addPatient($this->getReference('user2'));
        $manager->persist($symptom5);

        $symptom6 = new Symptom();
        $symptom6->setName('Mal de tête');
        $symptom6->setCode($this->generateHexaColorCode());
        $symptom6->setActive(false);
        $symptom6->addPatient($this->getReference('user3'));
        $manager->persist($symptom6);

        $symptom7 = new Symptom();
        $symptom7->setName('Anxiété');
        $symptom7->setCode($this->generateHexaColorCode());
        $symptom7->setActive(true);
        $symptom7->addPatient($this->getReference('user1'));
        $manager->persist($symptom7);

        $symptom8 = new Symptom();
        $symptom8->setName('Fièvre');
        $symptom8->setCode($this->generateHexaColorCode());
        $symptom8->setActive(false);
        $symptom8->addPatient($this->getReference('user2'));
        $manager->persist($symptom8);

        $symptom9 = new Symptom();
        $symptom9->setName('Douleur abdominale');
        $symptom9->setCode($this->generateHexaColorCode());
        $symptom9->setActive(false);
        $symptom9->addPatient($this->getReference('user3'));
        $manager->persist($symptom9);

        $symptom10 = new Symptom();
        $symptom10->setName('Toux');
        $symptom10->setCode($this->generateHexaColorCode());
        $symptom10->setActive(false);
        $symptom10->addPatient($this->getReference('user1'));
        $manager->persist($symptom10);

        $symptom11 = new Symptom();
        $symptom11->setName('Nausée');
        $symptom11->setCode($this->generateHexaColorCode());
        $symptom11->setActive(false);
        $symptom11->addPatient($this->getReference('user2'));
        $manager->persist($symptom11);

        $symptom12 = new Symptom();
        $symptom12->setName('Fatigue');
        $symptom12->setCode($this->generateHexaColorCode());
        $symptom12->setActive(false);
        $symptom12->addPatient($this->getReference('user3'));
        $manager->persist($symptom12);

        $symptom13 = new Symptom();
        $symptom13->setName('Essoufflement');
        $symptom13->setCode($this->generateHexaColorCode());
        $symptom13->setActive(false);
        $symptom13->addPatient($this->getReference('user1'));
        $manager->persist($symptom13);

        $symptom14 = new Symptom();
        $symptom14->setName('Éruption cutanée');
        $symptom14->setCode($this->generateHexaColorCode());
        $symptom14->setActive(false);
        $symptom14->addPatient($this->getReference('user2'));
        $manager->persist($symptom14);

        $symptom15 = new Symptom();
        $symptom15->setName('Inquiétude');
        $symptom15->setCode($this->generateHexaColorCode());
        $symptom15->setActive(false);
        $symptom15->addPatient($this->getReference('user3'));
        $manager->persist($symptom15);

        $symptom16 = new Symptom();
        $symptom16->setName('Stress');
        $symptom16->setCode($this->generateHexaColorCode());
        $symptom16->setActive(false);
        $symptom16->addPatient($this->getReference('user1'));
        $manager->persist($symptom16);

        $symptom17 = new Symptom();
        $symptom17->setName('Anxiété');
        $symptom17->setCode($this->generateHexaColorCode());
        $symptom17->setActive(false);
        $symptom17->addPatient($this->getReference('user2'));
        $manager->persist($symptom17);

        $symptom18 = new Symptom();
        $symptom18->setName('Tristesse');
        $symptom18->setCode($this->generateHexaColorCode());
        $symptom18->setActive(false);
        $symptom18->addPatient($this->getReference('user3'));
        $manager->persist($symptom18);

        $symptom19 = new Symptom();
        $symptom19->setName('Suicidaire');
        $symptom19->setCode($this->generateHexaColorCode());
        $symptom19->setActive(true);
        $symptom19->addPatient($this->getReference('user1'));
        $manager->persist($symptom19);

        $symptom20 = new Symptom();
        $symptom20->setName('Malheureux');
        $symptom20->setCode($this->generateHexaColorCode());
        $symptom20->setActive(false);
        $symptom20->addPatient($this->getReference('user2'));
        $manager->persist($symptom20);

        $symptom21 = new Symptom();
        $symptom21->setName('Solitude');
        $symptom21->setCode($this->generateHexaColorCode());
        $symptom21->setActive(true);
        $symptom21->addPatient($this->getReference('user3'));
        $manager->persist($symptom21);

        $symptom22 = new Symptom();
        $symptom22->setName('Déprimé');
        $symptom22->setCode($this->generateHexaColorCode());
        $symptom22->setActive(false);
        $symptom22->addPatient($this->getReference('user1'));
        $manager->persist($symptom22);

        $symptom23 = new Symptom();
        $symptom23->setName('Fatigue');
        $symptom23->setCode($this->generateHexaColorCode());
        $symptom23->setActive(false);
        $symptom23->addPatient($this->getReference('user2'));
        $manager->persist($symptom23);

        $symptom24 = new Symptom();
        $symptom24->setName('Insomnie');
        $symptom24->setCode($this->generateHexaColorCode());
        $symptom24->setActive(false);
        $symptom24->addPatient($this->getReference('user3'));
        $manager->persist($symptom24);

        $manager->flush();
    }

   private function generateHexaColorCode(): string
    {
        return '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
