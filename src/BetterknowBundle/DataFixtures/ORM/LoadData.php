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
        
        /*
         * There are nine category = personality
         */
        
        $category = array('shy', 'confident', 'caring', 'outgoing',
                            'organized', 'brainy', 'thoughtful', 'aggressive',
                            'random', 'studious', 'patient', 'serious', 'none');
        
        $j = 0;
        
        foreach ($category as $c){
            $cat[$j] = new \BetterknowBundle\Entity\Category();
            $cat[$j]->setName($c);
            $manager->persist($cat[$j]);
            
            $j++;
        }
        
        /* ------------------------------ */
        
        /*
         * Pack
         */
        for($i = 0; $i < 10; $i++){
            $pack[$i] = new \BetterknowBundle\Entity\Pack();
            $pack[$i]->setAuthorization(rand(0, 1));
            $manager->persist($pack[$i]);
        }
        
        /*
         * Quizz
         */
        $question = array('Qui est le plus sérieux ?',
                            'Qui est le plus déconneur ?',
                            'Qui peut te séduire ?',
                            'Qui serait le meilleur trader ?',
                            'Qui serait le meilleur caissier ?',
                            'Qui serait le meilleur ganster ?',
                            'Avec qui pourrais-tu avoir un enfant ?',
                            'Avec qui aimerais-tu partager les transports en commun ?',
                            'Qui sera le plus beau dans 10 ans ?');
        
        foreach ($question as $q){
            $quizz = new \BetterknowBundle\Entity\Quizz();
            $quizz->setQuestion($q);
            
            $quizz->setPack($pack[rand(0, 9)]);
            $quizz->addCategory($cat[rand(0, 12)]);
            
            $manager->persist($quizz);
        }
        /* ------------------------------ */
        
        $manager->flush();
    }
}