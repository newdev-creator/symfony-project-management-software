<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\SubTask;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SubTaskFixture extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create('fr_FR');

    for ($i = 0; $i < 50; $i++) {
      $subTask = new SubTask();
      $subTask
        ->setName($faker->name)
        ->setDescription($faker->paragraph)
        ->setTaskId($this->getReference('task_' . $faker->numberBetween(0, 19)))
        ->setCreatedAt(new DateTimeImmutable('now'));
      $manager->persist($subTask);
    }

    $manager->flush();
  }

  public function getDependencies()
  {
    return [
      TasksFixture::class
    ];
  }
}
