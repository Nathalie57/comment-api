<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * Password encoder
     * 
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($u = 0; $u < 20; $u++) {
            $user = new User();

            $hash = $this->encoder->encodePassword($user, "password");

            $user->setUsername($faker->firstName())
                 ->setPassword($hash);

            $manager->persist($user);

            for ($s = 0; $s < mt_rand(0, 5); $s++) {
                $comment = new Comment();
                $comment->setMessage($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                        ->setStatus($faker->randomElement(['DISPLAYED', 'REPORTED', 'NOTDISPLAYED']))
                        ->setSentAt($faker->dateTimeBetween('-6 months'))
                        ->setUser($user);
                
                $manager->persist($comment);
    
                for ($a = 0; $a < mt_rand(0, 5); $a++) {
                    $answer = new Answer();
                    $answer->setMessage($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                           ->setStatus($faker->randomElement(['DISPLAYED', 'REPORTED', 'NOTDISPLAYED']))
                           ->setSentAt($faker->dateTimeBetween('-6 months'))
                           ->setComment($comment)
                           ->setUser($user);
    
                    $manager->persist($answer);
                }
            }
        }

        

        $manager->flush();
    }
}
