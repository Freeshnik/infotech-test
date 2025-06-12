<?php

use App\Models\Author;
use yii\db\Migration;

class m250612_094154_inser_authors_and_books extends Migration
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

        // --- 1. Insert Authors ---
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
        echo "Authors inserted successfully.\n";


        // --- 2. Get Author IDs for linking books ---
        $authorIds = Author::find()->select('id')->column();
        if (empty($authorIds)) {
            echo "Warning: No author IDs found. Cannot insert books.\n";
            return;
        }

        // --- 3. Insert Books ---
        $booksData = [];
        $bookCount = 50;
        echo "Generating {$bookCount} books...\n";

        for ($i = 0; $i < $bookCount; $i++) {
            $randomAuthorId = $authorIds[array_rand($authorIds)];

            $booksData[] = [
                'title' => rtrim($faker->sentence(rand(2, 5)), '.'),
                'year' => $faker->year,
                'author_id' => $randomAuthorId,
                'description' => $faker->optional(0.8)->paragraph(3, true),
                'isbn' => $faker->isbn13,
                'photo_path' => '/img/book_image.jpg',
                'date_created' => $faker->dateTimeThisDecade->format('Y-m-d H:i:s'),
                'date_updated' => null,
            ];
        }

        $this->batchInsert('{{%book}}', ['title', 'year', 'author_id', 'description', 'isbn', 'photo_path', 'date_created', 'date_updated'], $booksData);
        echo "Books inserted successfully.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "Reverting demo data insertion.\n";

        // WARNING: This will delete ALL data from the book and author tables.
        // It's crucial to delete from the 'book' table first due to the foreign key constraint.
        echo "Deleting all records from 'book' table...\n";
        $this->delete('{{%book}}');

        echo "Deleting all records from 'author' table...\n";
        $this->delete('{{%author}}');

        // If you are using PostgreSQL or another DB that doesn't reset sequences on DELETE,
        // you might want to reset the sequence.
        // $this->execute('ALTER SEQUENCE author_id_seq RESTART WITH 1;');
        // $this->execute('ALTER SEQUENCE book_id_seq RESTART WITH 1;');

        echo "Demo data removed.\n";

        return true;
    }
}
