<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Faker;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'userFixture';

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $test_password = 'Azerty@1';

        $faker = Faker\Factory::create('fr_FR');

        $user = (new User())
            ->setEmail('jury@esgi.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword(password_hash($test_password, PASSWORD_BCRYPT));
        $manager->persist($user);

        for ($i = 0; $i < 10; $i++)
        {
            $user = (new User())
                ->setEmail($faker->email)
                ->setRoles([])
                ->setPassword(password_hash($test_password, PASSWORD_BCRYPT));
            $manager->persist($user);
        }

        $manager->flush();

        $this->addReference(self::USER_REFERENCE, $user);

    }
}