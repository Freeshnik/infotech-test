<?php

namespace Api\Controllers;

use App\Controller\ApiController;
use App\Models\Book;

class BookController extends ApiController
{
    public $modelClass = Book::class;
}