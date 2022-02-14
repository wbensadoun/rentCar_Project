<?php

namespace App\DataFixtures;

use App\Entity\Advert;
use App\Entity\Car;
use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AdvertFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        $cars = $manager->getRepository(Car::class)->findAll();
        foreach ($cars as $car){
            $advert = new Advert();
            $advert->setTitre($faker->words(5, true));
            $advert->setDescription($faker->paragraph(2));
            $advert->setPrix($faker->numberBetween(0,100));
            $advert->setCar($car);
            $manager->persist($advert);
        }

        $manager->flush();

    }

    public function getOrder()
    {
        return 2;
    }
}
