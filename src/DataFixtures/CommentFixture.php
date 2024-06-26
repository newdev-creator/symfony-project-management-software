<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Comment;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixture extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create('fr_FR');

    for ($i = 0; $i < 50; $i++) {
      $comment = new Comment();
      $comment
        ->setType($faker->word)
        ->setContent($faker->paragraph)
        ->setRead($faker->boolean)
        ->setUserId($this->getReference('user_' . $faker->numberBetween(0, 9)))
        ->setTaskId($this->getReference('task_' . $faker->numberBetween(0, 19)))
        ->setCreatedAt(new DateTimeImmutable('now'));
      $manager->persist($comment);
    }

    $manager->flush();
  }

  public function getDependencies()
  {
    return [
      TasksFixture::class,
      UsersFixture::class
    ];
  }
}
