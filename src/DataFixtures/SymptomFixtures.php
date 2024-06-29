<?php

namespace App\DataFixtures;

use app\Entity\Symptom;
use App\Entity\SymptomUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SymptomFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $symptom1 = new Symptom();
        $symptomUser1 = new SymptomUser();
        $symptomUser1->setSymptom($symptom1);
        $symptomUser1->setPatient($this->getReference('user1'));
        $symptom1->setName('Covid-19');
        $symptom1->setCode($this->generateHexaColorCode());
        $symptom1->setActive(false);
        $manager->persist($symptom1);
        $manager->persist($symptomUser1);

        $symptom2 = new Symptom();
        $symptomUser2 = new SymptomUser();
        $symptomUser2->setSymptom($symptom2);
        $symptomUser2->setPatient($this->getReference('user2'));
        $symptom2->setName('Mal de dos');
        $symptom2->setCode($this->generateHexaColorCode());
        $symptom2->setActive(false);
        $manager->persist($symptom2);
        $manager->persist($symptomUser2);

        $symptom3 = new Symptom();
        $symptomUser3 = new SymptomUser();
        $symptomUser3->setSymptom($symptom3);
        $symptomUser3->setPatient($this->getReference('user2'));
        $symptom3->setName('Grippe');
        $symptom3->setCode($this->generateHexaColorCode());
        $symptom3->setActive(false);
        $manager->persist($symptom3);
        $manager->persist($symptomUser3);

        $symptom4 = new Symptom();
        $symptomUser4 = new SymptomUser();
        $symptomUser4->setSymptom($symptom4);
        $symptomUser4->setPatient($this->getReference('user3'));
        $symptom4->setName('Ouverture de la main');
        $symptom4->setCode($this->generateHexaColorCode());
        $symptom4->setActive(true);
        $manager->persist($symptom4);
        $manager->persist($symptomUser4);

        $symptom5 = new Symptom();
        $symptomUser5 = new SymptomUser();
        $symptomUser5->setSymptom($symptom5);
        $symptomUser5->setPatient($this->getReference('user1'));
        $symptom5->setName('Sang');
        $symptom5->setCode($this->generateHexaColorCode());
        $symptom5->setActive(true);
        $manager->persist($symptom5);
        $manager->persist($symptomUser5);

        $symptom6 = new Symptom();
        $symptomUser6 = new SymptomUser();
        $symptomUser6->setSymptom($symptom6);
        $symptomUser6->setPatient($this->getReference('user2'));
        $symptom6->setName('Mal de tête');
        $symptom6->setCode($this->generateHexaColorCode());
        $symptom6->setActive(false);
        $manager->persist($symptom6);
        $manager->persist($symptomUser6);

        $symptom7 = new Symptom();
        $symptomUser7 = new SymptomUser();
        $symptomUser7->setSymptom($symptom7);
        $symptomUser7->setPatient($this->getReference('user3'));
        $symptom7->setName('Anxiété');
        $symptom7->setCode($this->generateHexaColorCode());
        $symptom7->setActive(true);
        $manager->persist($symptom7);
        $manager->persist($symptomUser7);

        $symptom8 = new Symptom();
        $symptomUser8 = new SymptomUser();
        $symptomUser8->setSymptom($symptom8);
        $symptomUser8->setPatient($this->getReference('user1'));
        $symptom8->setName('Fièvre');
        $symptom8->setCode($this->generateHexaColorCode());
        $symptom8->setActive(false);
        $manager->persist($symptom8);
        $manager->persist($symptomUser8);

        $symptom9 = new Symptom();
        $symptomUser9 = new SymptomUser();
        $symptomUser9->setSymptom($symptom9);
        $symptomUser9->setPatient($this->getReference('user2'));
        $symptom9->setName('Douleur abdominale');
        $symptom9->setCode($this->generateHexaColorCode());
        $symptom9->setActive(false);
        $manager->persist($symptom9);
        $manager->persist($symptomUser9);

        $symptom10 = new Symptom();
        $symptomUser10 = new SymptomUser();
        $symptomUser10->setSymptom($symptom10);
        $symptomUser10->setPatient($this->getReference('user3'));
        $symptom10->setName('Toux');
        $symptom10->setCode($this->generateHexaColorCode());
        $symptom10->setActive(false);
        $manager->persist($symptom10);
        $manager->persist($symptomUser10);

        $symptom11 = new Symptom();
        $symptomUser11 = new SymptomUser();
        $symptomUser11->setSymptom($symptom11);
        $symptomUser11->setPatient($this->getReference('user1'));
        $symptom11->setName('Nausée');
        $symptom11->setCode($this->generateHexaColorCode());
        $symptom11->setActive(false);
        $manager->persist($symptom11);
        $manager->persist($symptomUser11);

        $symptom12 = new Symptom();
        $symptomUser12 = new SymptomUser();
        $symptomUser12->setSymptom($symptom12);
        $symptomUser12->setPatient($this->getReference('user2'));
        $symptom12->setName('Fatigue');
        $symptom12->setCode($this->generateHexaColorCode());
        $symptom12->setActive(false);
        $manager->persist($symptom12);
        $manager->persist($symptomUser12);

        $symptom13 = new Symptom();
        $symptomUser13 = new SymptomUser();
        $symptomUser13->setSymptom($symptom13);
        $symptomUser13->setPatient($this->getReference('user3'));
        $symptom13->setName('Essoufflement');
        $symptom13->setCode($this->generateHexaColorCode());
        $symptom13->setActive(false);
        $manager->persist($symptom13);
        $manager->persist($symptomUser13);

        $symptom14 = new Symptom();
        $symptomUser14 = new SymptomUser();
        $symptomUser14->setSymptom($symptom14);
        $symptomUser14->setPatient($this->getReference('user1'));
        $symptom14->setName('Éruption cutanée');
        $symptom14->setCode($this->generateHexaColorCode());
        $symptom14->setActive(false);
        $manager->persist($symptom14);
        $manager->persist($symptomUser14);

        $symptom15 = new Symptom();
        $symptomUser15 = new SymptomUser();
        $symptomUser15->setSymptom($symptom15);
        $symptomUser15->setPatient($this->getReference('user2'));
        $symptom15->setName('Inquiétude');
        $symptom15->setCode($this->generateHexaColorCode());
        $symptom15->setActive(false);
        $manager->persist($symptom15);
        $manager->persist($symptomUser15);

        $symptom16 = new Symptom();
        $symptomUser16 = new SymptomUser();
        $symptomUser16->setSymptom($symptom16);
        $symptomUser16->setPatient($this->getReference('user3'));
        $symptom16->setName('Stress');
        $symptom16->setCode($this->generateHexaColorCode());
        $symptom16->setActive(false);
        $manager->persist($symptom16);
        $manager->persist($symptomUser16);

        $symptom17 = new Symptom();
        $symptomUser17 = new SymptomUser();
        $symptomUser17->setSymptom($symptom17);
        $symptomUser17->setPatient($this->getReference('user1'));
        $symptom17->setName('Anxiété');
        $symptom17->setCode($this->generateHexaColorCode());
        $symptom17->setActive(false);
        $manager->persist($symptom17);
        $manager->persist($symptomUser17);

        $symptom18 = new Symptom();
        $symptomUser18 = new SymptomUser();
        $symptomUser18->setSymptom($symptom18);
        $symptomUser18->setPatient($this->getReference('user2'));
        $symptom18->setName('Tristesse');
        $symptom18->setCode($this->generateHexaColorCode());
        $symptom18->setActive(false);
        $manager->persist($symptom18);
        $manager->persist($symptomUser18);

        $symptom19 = new Symptom();
        $symptomUser19 = new SymptomUser();
        $symptomUser19->setSymptom($symptom19);
        $symptomUser19->setPatient($this->getReference('user3'));
        $symptom19->setName('Suicidaire');
        $symptom19->setCode($this->generateHexaColorCode());
        $symptom19->setActive(true);
        $manager->persist($symptom19);
        $manager->persist($symptomUser19);

        $symptom20 = new Symptom();
        $symptomUser20 = new SymptomUser();
        $symptomUser20->setSymptom($symptom20);
        $symptomUser20->setPatient($this->getReference('user1'));
        $symptom20->setName('Malheureux');
        $symptom20->setCode($this->generateHexaColorCode());
        $symptom20->setActive(false);
        $manager->persist($symptom20);
        $manager->persist($symptomUser20);

        $symptom21 = new Symptom();
        $symptomUser21 = new SymptomUser();
        $symptomUser21->setSymptom($symptom21);
        $symptomUser21->setPatient($this->getReference('user2'));
        $symptom21->setName('Solitude');
        $symptom21->setCode($this->generateHexaColorCode());
        $symptom21->setActive(true);
        $manager->persist($symptom21);
        $manager->persist($symptomUser21);

        $symptom22 = new Symptom();
        $symptomUser22 = new SymptomUser();
        $symptomUser22->setSymptom($symptom22);
        $symptomUser22->setPatient($this->getReference('user3'));
        $symptom22->setName('Déprimé');
        $symptom22->setCode($this->generateHexaColorCode());
        $symptom22->setActive(false);
        $manager->persist($symptom22);
        $manager->persist($symptomUser22);

        $symptom23 = new Symptom();
        $symptomUser23 = new SymptomUser();
        $symptomUser23->setSymptom($symptom23);
        $symptomUser23->setPatient($this->getReference('user1'));
        $symptom23->setName('Fatigue');
        $symptom23->setCode($this->generateHexaColorCode());
        $symptom23->setActive(false);
        $manager->persist($symptom23);
        $manager->persist($symptomUser23);

        $symptom24 = new Symptom();
        $symptomUser24 = new SymptomUser();
        $symptomUser24->setSymptom($symptom24);
        $symptomUser24->setPatient($this->getReference('user2'));
        $symptom24->setName('Insomnie');
        $symptom24->setCode($this->generateHexaColorCode());
        $symptom24->setActive(false);
        $manager->persist($symptom24);
        $manager->persist($symptomUser24);

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
