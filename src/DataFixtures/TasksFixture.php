<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TasksFixture extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create('fr_FR');

    for ($i = 0; $i < 20; $i++) {
      $task = new Task();
      $task
        ->setName($faker->sentence)
        ->setDescription($faker->paragraph)
        ->setDueDate($faker->dateTimeBetween('now', '+1 month'))
        ->setPriority($faker->randomElement(['low', 'medium', 'high']))
        ->setStatus($faker->randomElement(['not_started', 'in_progress', 'completed', 'postponed']))
        ->addAssignedUserId($this->getReference('user_' . $faker->numberBetween(0, 9)))
        ->setProjectId($this->getReference('project_' . $faker->numberBetween(0, 4)))
        ->setCreatedAt(new DateTimeImmutable('now'));
      $manager->persist($task);

      $this->addReference('task_' . $i, $task);
    }

    $manager->flush();
  }

  public function getDependencies()
  {
    return [
      ProjectsFixture::class,
    ];
  }
}
