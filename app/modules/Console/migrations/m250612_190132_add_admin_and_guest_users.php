<?php

use App\Models\User;
use yii\db\Migration;

class m250612_190132_add_admin_and_guest_users extends Migration
{
    public string $tableName = '{{%user}}';

    /**
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->batchInsert($this->tableName,
            [
                'username',
                'fio',
                'password_hash',
                'password_reset_token',
                'auth_key',
                'access_token',
                'email',
                'status',
                'type',
            ],
            [
                [
                    'admin', // username
                    'Админ Админович',
                    Yii::$app->security->generatePasswordHash('admin'),
                    Yii::$app->security->generateRandomString(),
                    Yii::$app->security->generateRandomString(),
                    Yii::$app->security->generateRandomString(16),
                    'admin@example.com',
                    User::STATUS_ACTIVE,
                    User::TYPE_USER,
                ],
                [
                    'guest', // username
                    'Гость Гостевич',
                    Yii::$app->security->generatePasswordHash('guest'),
                    Yii::$app->security->generateRandomString(),
                    Yii::$app->security->generateRandomString(),
                    Yii::$app->security->generateRandomString(16),
                    'guest@example.com',
                    User::STATUS_ACTIVE,
                    User::TYPE_GUEST,
                ],
            ]
        );
    }

    public function safeDown()
    {
        $this->delete($this->tableName, ['username' => ['admin', 'guest']]);
    }
}
