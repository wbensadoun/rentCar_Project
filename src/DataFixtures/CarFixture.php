<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CarFixture extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

      for($i = 0; $i < 21; $i++)
      {
          $customer = new Customer();
          $customer->setName($faker->lastName);
          $customer->setFirstName($faker->firstName);
          $customer->setAddress($faker->address);
          $customer->setPhone($faker->phoneNumber);
          $customer->setCity($faker->city);
          $customer->setPostalCode($faker->postcode);
          $customer->setPhotoProfile('photoProfile.jpg');

          $user = new User();
          $user->setCustomer($customer);
          $user->setEmail($faker->email);
          $user->setUserName($faker->userName);
          $user->setEnabled(true);
          $user->setPassword("azerty");
          $user->addRole('ROLE_CUSTOMER');

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
          $car->setCustomer($customer);

          $manager->persist($user);
          $manager->persist($customer);
          $manager->persist($car);
      }

        $manager->flush();

    }

    public function getOrder()
    {
        return 1;
    }
}
