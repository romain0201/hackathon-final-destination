<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ia = new User();
        $ia->setEmail('ia@hackathon.fr');
        $ia->setName('IA');
        $ia->setPassword('$2y$13$oza8661olib/vMDnEBIA/eMRR9HeP2UQMLK/qcb3c1vhFb56HeUau');
        $ia->setRoles(['ROLE_ADMIN']);
        $ia->setVerified(true);

        $admin = new User();
        $admin->setEmail('admin@hackathon.fr');
        $admin->setName('Admin');
        $admin->setPassword('$2y$13$oza8661olib/vMDnEBIA/eMRR9HeP2UQMLK/qcb3c1vhFb56HeUau');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setVerified(true);

        $user = new User();
        $user->setEmail('user@hackathon.fr');
        $user->setName('User');
        $user->setPassword('$2y$13$oza8661olib/vMDnEBIA/eMRR9HeP2UQMLK/qcb3c1vhFb56HeUau');
        $user->setRoles(['ROLE_PATIENT']);
        $user->setVerified(true);

        $pharmacie = new User();
        $pharmacie->setEmail('pharmacie@hackathon.fr');
        $pharmacie->setName('Pharmacie');
        $pharmacie->setPassword('$2y$13$oza8661olib/vMDnEBIA/eMRR9HeP2UQMLK/qcb3c1vhFb56HeUau');
        $pharmacie->setRoles(['ROLE_PHARMACIEN']);
        $pharmacie->setVerified(true);

        $manager->persist($ia);
        $manager->persist($admin);
        $manager->persist($user);
        $manager->persist($pharmacie);
        $manager->flush();
    }
}
        