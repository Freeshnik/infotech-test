<?php

namespace App\Repositories\Author;

use App\Models\Author;
use App\Models\Book;
use App\Repositories\Repository;


class AuthorRepo extends Repository
{
    /** @var int */
    private const TOP_COUNT = 10;

    /** Возвращает топ-10 авторов, выпустивших наибольшее количество книг в указанном году
     * @param int $year
     * @return array
     */
    public function findTopTenAuthorsByYear(int $year): array
    {
        return Author::find()
            ->select([
                'book_count' => 'COUNT(book.id)',
                'book.year',
                'author_id' => 'author.id',
                'author.fio',
            ])
            ->leftJoin(Book::tableName(), 'author.id = book.author_id')
            ->where(['book.year' => $year])
            ->groupBy('author.id')
            ->orderBy(['book_count' => SORT_DESC])
            ->limit(self::TOP_COUNT)
            ->asArray()
            ->all();
    }

    /** Возвращает все годы, в которых были изданы книги
     * @return array
     */
    public function getAllYears(): array
    {
        return Book::find()->select('year')->orderBy(['year' => SORT_ASC])->distinct()->column();
    }
}