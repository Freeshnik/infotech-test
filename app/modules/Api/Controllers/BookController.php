<?php

namespace Api\Controllers;

use App\Controller\ApiController;
use App\Models\Book;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Book",
    title: "Book",
    description: "Book model",
    type: "object"
)]
class BookController extends ApiController
{
    public $modelClass = Book::class;

    #[OA\Get(
        path: "/book",
        summary: "Get list of books",
        tags: ["Users"]
    )]
    #[OA\Response(
        response: 200,
        description: "Successful response",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(ref: "#/components/schemas/Book")
        )
    )]
    public function actionIndex()
    {
        return parent::actionIndex();
    }
}