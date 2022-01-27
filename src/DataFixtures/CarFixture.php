<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CarFixture extends Fixture
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
          $car->setPicture1('picture.png');
          $car->setState(1);
          $car->setName($faker->word);
          $car->setNumberPlate($faker->numberBetween(22222,56445));
          $car->setPicture1OrigFileName('pictureOrig.png');
          $car->setCustomer($customer);

          $manager->persist($user);
          $manager->persist($customer);
          $manager->persist($car);
      }

        $manager->flush();

    }
}
