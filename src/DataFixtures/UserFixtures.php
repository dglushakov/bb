<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i=1; $i<10; $i++){
            $user = new User();
            $user->setUsername('user'.$i);
            $user->setRoles([
                'ROLE_USER',
            ]);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $i
            ));
            $manager->persist($user);
        }


        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setRoles(["ROLE_ADMIN"]);
        $adminUser->setPassword($this->passwordEncoder->encodePassword(
            $adminUser,
            'admin'
        ));
        $manager->persist($adminUser);
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
