<?php

namespace App\DataFixtures;

use app\Entity\Symptom;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SymptomFixtures extends Fixture
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

        $manager->flush();
    }

   private function generateHexaColorCode(): string
    {
        return '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
    }
}
