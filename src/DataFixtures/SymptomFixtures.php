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
        $symptomNames = [
            'Sang',
            'Mal de tête',
            'Anxiété',
            'Fièvre',
            'Douleur abdominale',
            'Toux',
            'Nausée',
            'Fatigue',
            'Essoufflement',
            'Éruption cutanée'
        ];

        foreach ($symptomNames as $name) {
            $symptom = new Symptom();
            $symptom->setName($name);
            $symptom->setCode($this->generateHexaColorCode());
            $symptom->setActive((bool)rand(0, 1));

            $manager->persist($symptom);
        }

        $symptom1 = new Symptom();
        $symptom1->setName('Covid-19');
        $symptom1->setCode($this->generateHexaColorCode());
        $symptom1->setActive(true);
        $symptom1->addPatient($this->getReference('user1'));
        $symptom1->addPatient($this->getReference('user2'));
        $manager->persist($symptom1);

        $symptom2 = new Symptom();
        $symptom2->setName('Mal de dos');
        $symptom2->setCode($this->generateHexaColorCode());
        $symptom2->setActive(true);
        $symptom2->addPatient($this->getReference('user2'));
        $manager->persist($symptom2);

        $symptom3 = new Symptom();
        $symptom3->setName('Grippe');
        $symptom3->setCode($this->generateHexaColorCode());
        $symptom3->setActive(true);
        $symptom3->addPatient($this->getReference('user3'));
        $manager->persist($symptom3);

        $symptom4 = new Symptom();
        $symptom4->setName('Ouverture de la main');
        $symptom4->setCode($this->generateHexaColorCode());
        $symptom4->setActive(true);
        $symptom4->addPatient($this->getReference('user1'));
        $manager->persist($symptom4);

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
