<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasherPassword;
    public function __construct( UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->hasherPassword = $userPasswordHasherInterface;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

            $customer = new Customer();
            $customer->setName($faker->lastName);
            $customer->setFirstName($faker->firstName);
            $customer->setAddress($faker->address);
            $customer->setPhone($faker->phoneNumber);
            $customer->setCity($faker->city);
            $customer->setPostalCode($faker->postcode);

            $user = new User();
            $user->setCustomer($customer);
            $user->setEmail('admin@hotmail.fr');
            $user->setUserName('admin');
            $user->setEnabled(true);
            $user->setPassword($this->hasherPassword->hashPassword($user,"admin"));
            $user->addRole('ROLE_CUSTOMER');
            $manager->persist($customer);
            $manager->persist($user);
            $manager->flush();
    }
}
