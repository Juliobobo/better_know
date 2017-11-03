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
        $userAdmin->setFirstName("Julien");
        $userAdmin->setLastName("bonnardel");
        $userAdmin->setAge(15);
        $userAdmin->setGender(true);
        $userAdmin->setPlainPassword('bkAdmin');
        $userAdmin->setEmail('julien.bonnardel94@gmail.com');
        $userAdmin->setEnabled(true);
        $userAdmin->setRoles(array('ROLE_ADMIN'));
        
        $manager->persist($userAdmin);
        
        /*
         * Pack
         */
        for($i = 0; $i < 10; $i++){
            $pack[$i] = new \BetterknowBundle\Entity\Pack();
            $manager->persist($pack[$i]);
        }
        
        // Liste de users
        $name = array('coucou', 'bonjour', 'alain', 'marcel', 'juliette',
                'charlene', 'zizito', 'lebg', 'charlotte', 'prunelle', 'florence',
                'mathilde', 'bastien', 'chloe', 'nicolas', 'lefoufou', 'bk', 'team',
                'oulaoup', 'plaza', 'turin', 'milan');
        
        for($i = 0; $i < 10; $i++){
            $user[$i] = new User();
            
            $user[$i]->setUsername($name[$i])
                    ->setFirstName($name[rand(0, 21)])
                    ->setLastName($name[rand(0, 21)])
                    ->setAge(rand(15, 100))
                    ->setGender(true)
                    ->setEmail('testouille'.$i.'@gmail.com')
                    ->setEnabled(true)
                    ->setPlainPassword('test')
                    ->addPack($pack[rand(0, 9)]);
            
            $manager->persist($user[$i]);
        }
        
        // Friends
        for($i = 0; $i < 10; $i++){
            $friend[$i] = new \BetterknowBundle\Entity\Friend();
            
            if($i%2){
                $friend[$i]->setState(true);
            }else {
                $friend[$i]->setState(false);
            }
            if($i == 9 || $i == 8){
                $friend[$i]->setUser($user[$i])
                        ->setFriend($user[rand(0, 7)]);
            }else {
                $friend[$i]->setUser($user[$i])
                        ->setFriend($user[rand($i+1, 9)]);
            }
            
            $manager->persist($friend[$i]);
        }
        
        
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
        $i = 0;
        foreach ($question as $q){
            $quizz = new \BetterknowBundle\Entity\Quizz();
            $quizz->setQuestion($q);
            
            $quizz->addCategory($cat[rand(0, 12)])
                    ->addResponse($user[$i]);
            $pack[$i]->addQuizz($quizz);
            
            $manager->persist($quizz);
            
            $i++;
        }
        /* ------------------------------ */        
        
        /*
         * Gems
         */
        for($i = 0; $i < 10; $i++){
            $gem[$i] = new \BetterknowBundle\Entity\Gem();
            $gem[$i]->setGender(true)
                        ->setTimeReceive(new \DateTime())
                        ->setUser($userAdmin);
            $manager->persist($gem[$i]);
        }
        
        $manager->flush();
    }
}