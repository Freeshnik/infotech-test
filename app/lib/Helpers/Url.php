<?php

namespace App\Helpers;


class Url
{
    /**
     * Валиден ли урл
     * @param $url
     * @return mixed
     */
    public static function isValid($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Подготовить URL, если у него нету схемы
     * @param $url
     * @return string
     */
    public static function makeValid($url)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }

        return $url;
    }

    public static function getHost($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        return preg_replace('/^www\./', '', $host);
    }
}