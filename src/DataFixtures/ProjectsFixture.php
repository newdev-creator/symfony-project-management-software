<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\Project;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProjectsFixture extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create('fr_FR');

    for ($i = 0; $i < 5; $i++) {
      $project = new Project();
      $project
        ->setName($faker->sentence)
        ->setDescription($faker->paragraph)
        ->setOwnerId($this->getReference('user_' . $faker->numberBetween(0, 9)))
        ->setCreatedAt(new DateTimeImmutable('now'));
      $manager->persist($project);

      $this->addReference('project_' . $i, $project);
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
