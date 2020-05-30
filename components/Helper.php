<?php
namespace app\components;

use app\components\SxGeo;

/**
 * Class Helper
 * @package app\components
 */
class Helper
{
    public const GEO_DATA = __DIR__ .'/../data/SxGeoCity.dat';
    public const DEFAULT_IP = '46.187.14.14';

    /**
     * @param string $ip
     * @return array|bool
     */
    public function getGeoData(string $ip)
    {
        if ($ip === '127.0.0.1') {
            $ip = self::DEFAULT_IP;
        }
        // todo: класс выше импортирован, но не используется. Выяснить почему
        $geo = new SxGeo(self::GEO_DATA);

        return $geo->getCityFull($ip);
    }
}