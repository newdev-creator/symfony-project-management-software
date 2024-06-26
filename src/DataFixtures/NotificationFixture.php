<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Notification;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class NotificationFixture extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create('fr_FR');

    for ($i = 0; $i < 30; $i++) {
      $notification = new Notification();
      $notification
        ->setType($faker->word)
        ->setContent($faker->sentence)
        ->setRead($faker->boolean)
        ->setUserId($this->getReference('user_' . $faker->numberBetween(0, 9)))
        ->setCreatedAt(new DateTimeImmutable('now'));
      $manager->persist($notification);
    }

    $manager->flush();
  }

  public function getDependencies()
  {
    return [
      UsersFixture::class,
    ];
  }
}
