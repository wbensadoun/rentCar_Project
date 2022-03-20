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
        $user = $manager->getRepository(User::class)->findOneBy(['email'=>'admin@hotmail.fr']);

        $car = new Car();
        $car->setDescription($faker->paragraph);
        $car->setStartDate($faker->dateTime);
        $car->setModel($faker->word);
        $car->setPicture1('car'.rand(1,3).".jpg");
        $car->setPicture2('car'.rand(1,3).".jpg");
        $car->setPicture3('car'.rand(1,3).".jpg");
        $car->setState(1);
        $car->setName($faker->word);
        $car->setNumberPlate($faker->numberBetween(22222,56445));
        $car->setPicture1OrigFileName('car'.rand(1,3).".jpg");
        $car->setPicture2OrigFileName('car'.rand(1,3).".jpg");
        $car->setPicture3OrigFileName('car'.rand(1,3).".jpg");
        $car->setCustomer($user->getCustomer());
        $manager->persist($car);

        for($i=0; $i <50; $i ++){
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
