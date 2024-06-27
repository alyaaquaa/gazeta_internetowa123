<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Enum\ArticleStatus;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class ArticleFixtures.
 */
class ArticleFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * This method generates and persists 100 article entities with random data.
     * Each article is associated with a random category, a random set of tags,
     * and a random author. The creation and update dates are set to random dates
     * within the last 100 days. The status of each article is randomly set.
     *
     * @return void
     */
    public function loadData(): void
    {
        if (!$this->manager instanceof \Doctrine\Persistence\ObjectManager || !$this->faker instanceof \Faker\Generator) {
            return;
        }

        $this->createMany(100, 'articles', function (int $i) {
            $article = new Article();
            $article->setTitle($this->faker->sentence);
            $article->setContent($this->faker->sentence);
            $article->setComment($this->faker->sentence);
            $article->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $article->setUpdatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $article->setCategory($category);

            /** @var array<array-key, Tag> $tags */
            $tags = $this->getRandomReferences(
                'tags',
                $this->faker->numberBetween(0, 5)
            );
            foreach ($tags as $tag) {
                $article->addTag($tag);
            }

            // Przekształć losową liczbę na stan artykułu
            $article->setStatus(ArticleStatus::from($this->faker->numberBetween(1, 2)));

            $author = $this->getRandomReference('users');
            $article->setAuthor($author);

            return $article;
        });

        $this->manager->flush();
    }

    /**
     * Get the dependencies of this fixture.
     *
     * This method returns an array of classes that this fixture depends on.
     * It ensures that the specified fixtures are loaded before this fixture.
     *
     * @return array The array of dependent fixture classes
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, TagFixtures::class, UserFixtures::class];
    }
}
