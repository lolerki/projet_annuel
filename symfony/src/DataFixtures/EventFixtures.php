<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\Event;
use Faker;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public const EVENT_REFERENCE = 'eventFixture';

    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        $user = $this->getReference(UserFixtures::USER_REFERENCE);

        $time= new \DateTime();
        $time->setTime(7, 30);

        for ($i = 0; $i < 10; $i++) {

            $event = (new Event())
                ->setTitle($faker->text($maxNbChars = 50)  )
                ->setDescription($faker->text)
                ->setDateEvent($faker->dateTimeInInterval($startDate = '-5 days', $interval = '+ 7 days', $timezone = null) )
                ->setPrice($faker->numberBetween($min = 0, $max = 200))
                ->setIdUser($user)
                ->setImage('iStock-667709450.jpg')
                ->setAddress($faker->streetAddress)
                ->setLat($faker->latitude)
                ->setLng($faker->longitude)
                ->setNbPlace(15)
                ->setType('Dessin')
                ->setTransport('Métro 9, arrêt Républic')
                ->setTime($time)
                ->setTimeEnd($time);


            $manager->persist($event);

        }

        $manager->flush();

        $this->addReference(self::EVENT_REFERENCE, $event);

    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}