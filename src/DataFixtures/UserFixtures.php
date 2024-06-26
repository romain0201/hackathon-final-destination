<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private SymptomFixtures $symptomFixtures;

    public function __construct(UserPasswordHasherInterface $passwordHasher, SymptomFixtures $symptomFixtures)
    {
        $this->passwordHasher = $passwordHasher;
        $this->symptomFixtures = $symptomFixtures;
    }

    public function load(ObjectManager $manager): void
    {
        $ia = new User();
        $ia->setEmail('ia@hackathon.fr');
        $ia->setName('IA');
        $ia->setPassword($this->passwordHasher->hashPassword($ia, 'test'));
        $ia->setRoles(['ROLE_ADMIN']);
        $ia->setVerified(true);
        $manager->persist($ia);
        $this->addReference('ia', $ia);

        $admin = new User();
        $admin->setEmail('admin@hackathon.fr');
        $admin->setName('Admin');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'test'));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setVerified(true);
        $manager->persist($admin);

        $user1 = new User();
        $user1->setEmail('user@hackathon.fr');
        $user1->setName('User');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'test'));
        $user1->setRoles(['ROLE_PATIENT']);
        $user1->setVerified(true);
        $manager->persist($user1);
        $this->addReference('user1', $user1);

        $user2 = new User();
        $user2->setEmail('user2@hackathon.fr');
        $user2->setName('User2');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'test'));
        $user2->setRoles(['ROLE_PATIENT']);
        $user2->setVerified(true);
        $manager->persist($user2);
        $this->addReference('user2', $user2);

        $user3 = new User();
        $user3->setEmail('user3@hackathon.fr');
        $user3->setName('User3');
        $user3->setPassword($this->passwordHasher->hashPassword($user3, 'test'));
        $user3->setRoles(['ROLE_PATIENT']);
        $user3->setVerified(true);
        $manager->persist($user3);
        $this->addReference('user3', $user3);

        $pharmacie = new User();
        $pharmacie->setEmail('pharmacie@hackathon.fr');
        $pharmacie->setName('Pharmacie');
        $pharmacie->setPassword($this->passwordHasher->hashPassword($pharmacie, 'test'));
        $pharmacie->setRoles(['ROLE_PHARMACIEN']);
        $pharmacie->setVerified(true);
        $manager->persist($pharmacie);
        
        $manager->flush();
    }
}
        