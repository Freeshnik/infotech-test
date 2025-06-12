<?php

namespace App\Services;

use App\Models\Book;
use App\Repositories\Author\AuthorRepo;
use yii\data\ArrayDataProvider;

readonly class ReportService
{
    public function __construct(
        private AuthorRepo $authorRepo,
        private int $year,
    ) {
    }

    /**
     * @return ArrayDataProvider
     */
    public function getTopTenAuthorsData(): ArrayDataProvider
    {
        $topAuthors = $this->authorRepo->findTopTenAuthorsByYear($this->year);

        return new ArrayDataProvider([
            'allModels' => $topAuthors,
            'key' => 'author_id',
            'pagination' => false,
            'sort' => false,
        ]);
    }

    /** Возвращает все годы, в которых были изданы книги
     * @return array
     */
    public function getAllYears(): array
    {
        return Book::find()->select('year')->orderBy(['year' => SORT_ASC])->distinct()->column();
    }
}