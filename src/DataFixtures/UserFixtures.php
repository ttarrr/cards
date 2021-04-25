<?php

namespace App\DataFixtures;

use App\Security\User\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    // php bin/console doctrine:fixtures:load

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $users_seed = [
            [
                'username' => 'player',
                'password' => 'player',
                'roles' => ['ROLE_USER']
            ],
            [
                'username' => 'admin',
                'password' => 'admin',
                'roles' => ['ROLE_USER', 'ROLE_ADMIN']
            ],
        ];

        foreach ($users_seed as $user_seed) {
            $user = new User();
            $user->setUsername($user_seed['username'])
                 ->setPassword($this->passwordEncoder->encodePassword($user, $user_seed['password']))
                 ->setRoles($user_seed['roles']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
