<?php

namespace Api\Controllers;

use App\Controller\ApiController;
use App\Models\Author;

class AuthorController extends ApiController
{
    public $modelClass = Author::class;
}