<?php

namespace App\Models;

use App\Behaviors\Timestamp;
use App\ActiveRecord;
use Yii;
use yii\web\IdentityInterface;

/**
 * Class User
 * @property int         $id                - ID
 * @property string      $username          - username
 * @property string      $fio               - ФИО
 * @property string      $phone             - номер телефона в формате +79001005050
 * @property string      $auth_key          - ключ для автологина
 * @property string      $access_token      - ключ для доступа по Api
 * @property string      $password_hash     - зашифрованый пароль
 * @property string      $password_reset_token
 * @property string      $email
 * @property int         $type              - тип юзера
 * @property int         $status            - статус юзера
 * @property \DateTime   $date_created      - дата создания
 * @property \DateTime   $date_updated      - дата изменения
 * @property \DateTime   $date_last_visit   - дата последнего визита
 * @property int         $create_user_id
 * @package common\models
 * @package App\Models
 */
class User extends ActiveRecord implements IdentityInterface
{

    public const TYPE_GUEST = 1;

    public const TYPE_USER  = 2;
    /**
     * @see OutContainer::USER_STATUS_ACTIVE
     */
    public const STATUS_ACTIVE = 1;

    /** Забанен/выключен */
    public const STATUS_BANNED = 2;

    public const PASSWORD_RESET_TOKEN_TTL = 3600;

    /**
     * Массив урлов главной страницы приложений каждого типа пользователя
     * @var array
     */
    protected $base_url
        = [
            self::TYPE_GUEST => '/publisher/',
            self::TYPE_USER     => '/admin/',
        ];

    /**
     * Валидация модели
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Уникальные поля
            [['username'], 'unique', 'message' => 'Такой логин уже используется, попробуйте подобрать другой.'],
            [['email'], 'unique', 'message' => 'Эту почту уже использует другой пользователь :('],
            [['access_token'], 'unique'],
            // Обязательные поля
            [['username', 'email'], 'required'],
            // Integer
            [['type', 'status'], 'integer'],
            // String
            [
                [
                    'auth_key',
                    'access_token',
                    'password_hash',
                    'password_reset_token',
                    'email',
                ],
                'string'
            ],
            // Правила для логина
            ['username', 'string', 'min' => 4, 'max' => 255],
            // Валидатор почты
            ['email', 'email'],
            // Тримим
            [
                [
                    'username',
                    'email',
                ],
                'filter',
                'filter' => 'trim'
            ],
        ];
    }

    /**
     * Поведения для дат
     * @return array
     */
    public function behaviors(): array
    {
        return [
            Timestamp::class,
        ];
    }

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * @param $id
     * @return void
     */
    public static function findIdentity($id): ?ActiveRecord
    {
        return self::findOne($id);
    }

    /** Типы юзеров
     * @return string[]
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_GUEST => 'Гость',
            self::TYPE_USER  => 'Пользователь',
        ];
    }

    /**
     * Пока так, когда будем делать api - это заработает
     * @param mixed $token
     * @param null  $type
     * @return bool
     */
    public static function findIdentityByAccessToken($token, $type = null): bool
    {
        return false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Метод для
     * @return string
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * Валидация по кукам
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Генерируем и установаливаем хеш пароля
     *
     * @param string $password
     */
    public function setPassword($password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Генерируем и устанавдиваем auth_key
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Генерируем и устанавдиваем auth_key
     */
    public function generateAccessToken(): void
    {
        $this->access_token = Yii::$app->security->generateRandomString(16);
    }

    /**
     * Валидация пароля через заебизь
     * @param $password
     * @return bool
     */
    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Возвращает ссылку на главное приложение
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->base_url[$this->type];
    }

    /**
     * Проверяем действует ли токен сброса пароля
     * @param string $token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);

        return $timestamp + self::PASSWORD_RESET_TOKEN_TTL >= time();
    }

    /**
     * Генерируем и устанавливаем токен для сброса пароля
     */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Ищем по токену сброса пароля
     *
     * @param $token
     * @return null|self()
     */
    public static function findByResetToken($token): ?User
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return self::findOne([
            'password_reset_token' => $token,
            'status'               => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @param $username
     * @return User|null
     */
    public static function findByUsername($username): ?User
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
}