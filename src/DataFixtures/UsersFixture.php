<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixture extends Fixture
{
  public function __construct(
    private readonly UserPasswordHasherInterface $passwordHasherm
  ) {
  }

  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create('fr_FR');

    for ($i = 0; $i < 10; $i++) {
      $user = new User();
      $user
        ->setFirstName($faker->firstName)
        ->setLastName($faker->lastName)
        ->setEmail($faker->email)
        ->setPhone($faker->phoneNumber)
        ->setPassword($this->passwordHasherm->hashPassword($user, 'password'))
        ->setCreatedAt(new DateTimeImmutable('now'));
      $manager->persist($user);

      $this->addReference('user_' . $i, $user);
    }

    $manager->flush();
  }
}
