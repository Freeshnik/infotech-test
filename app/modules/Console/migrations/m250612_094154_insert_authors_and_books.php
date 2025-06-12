<?php

use App\Models\Author;
use yii\db\Migration;

class m250612_094154_insert_authors_and_books extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try {
            $faker = \Faker\Factory::create('ru_RU');
        } catch (\Exception $e) {
            echo "Error: The Faker library is not installed. Please run 'composer require fzaninotto/faker' in your project root.\n";
            return false;
        }

        $authorsData = [];
        $authorCount = 20;
        echo "Generating {$authorCount} authors...\n";

        for ($i = 0; $i < $authorCount; $i++) {
            $authorsData[] = [
                'fio' => $faker->name,
                'description' => $faker->optional(0.7)->sentence(15, true),
                'date_created' => $faker->dateTimeThisDecade->format('Y-m-d H:i:s'),
                'date_updated' => null,
            ];
        }

        $this->batchInsert('{{%author}}', ['fio', 'description', 'date_created', 'date_updated'], $authorsData);


        // --- 2. Get Author IDs for linking books ---
        $authorIds = Author::find()->select('id')->column();
        if (empty($authorIds)) {
            echo "Warning: No author IDs found. Cannot insert books.\n";
            return false;
        }

        // --- 3. Insert Books ---
        $booksData = [];
        $bookCount = 500;

        $years = [2010, 2011, 2012, 2013, 2014, 2015];

        for ($i = 0; $i < $bookCount; $i++) {
            $randomAuthorId = $authorIds[array_rand($authorIds)];

            $booksData[] = [
                'title' => rtrim($faker->sentence(rand(2, 5)), '.'),
                'year' => $years[array_rand($years)],
                'author_id' => $randomAuthorId,
                'description' => $faker->optional(0.8)->paragraph(3, true),
                'isbn' => $faker->isbn13,
                'photo_path' => '/img/book_image.jpg',
                'date_created' => $faker->dateTimeThisDecade->format('Y-m-d H:i:s'),
                'date_updated' => null,
            ];
        }

        $this->batchInsert('{{%book}}', ['title', 'year', 'author_id', 'description', 'isbn', 'photo_path', 'date_created', 'date_updated'], $booksData);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%book}}');
        $this->delete('{{%author}}');

    }
}
