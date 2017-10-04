<?php

namespace BetterknowBundle\DataFixtures\ORM;

use BetterknowBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Admin
        $userAdmin = new User();
        $userAdmin->setUsername('Julien');
        $userAdmin->setPlainPassword('bkAdmin');
        $userAdmin->setEmail('julien.bonnardel94@gmail.com');
        $userAdmin->setEnabled(true);
        $userAdmin->setRoles(array('ROLE_ADMIN'));
        
        $manager->persist($userAdmin);
        $manager->flush();
    }
}