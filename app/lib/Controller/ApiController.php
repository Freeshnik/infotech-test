<?php

namespace App\Controller;

use App\App;
use App\Models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\Response;

/** Родительский контроллер для API модуля */
class ApiController extends ActiveController
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        // Переопределяем contentNegotiator, чтобы он всегда отвечал в формате JSON
        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => Response::FORMAT_JSON,
        ];

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
            'tokenParam' => 'auth-key',
            'optional' => ['index', 'view'], // экшены, которые не требуют аутентификации
        ];

        return $behaviors;
    }
}