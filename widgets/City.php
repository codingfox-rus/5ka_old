<?php
namespace app\widgets;

use yii\base\Widget;
use app\components\Helper;
use app\models\Location;

class City extends Widget
{
    public const DEFAULT_CITY = 'Москва';

    /** @var int */
    public $userLocationId;

    /** @var string */
    public $ip;

    /** @var string */
    public $city;

    /** @var int */
    public $locationId;

    public function init()
    {
        if ($this->userLocationId) {

            $location = Location::findOne($this->userLocationId);

            if ($location) {
                $this->city = $location->name;
            }
        }

        if ($this->city === null) {
            $this->setCityAndLocationId();
        }
    }

    /**
     * Устанавливаем город и ID локации по IP клиента
     */
    public function setCityAndLocationId(): void
    {
        $helper = new Helper();

        $geoData = $helper->getGeoData($this->ip);

        $this->city = $geoData['city']['name_ru'] ?? self::DEFAULT_CITY;
        // Отладка
        //$this->city = 'Нижний Новгород';

        $similarLocations = Location::find()
            ->where(['like', 'name', $this->city])
            ->asArray()
            ->all();

        foreach ($similarLocations as $location) {

            preg_match('/([а-я]+\.\s)([А-Яа-я\s]+)/u', $location['name'], $matches);

            if ($this->city === $matches[2]) {
                $this->locationId = $location['id'];
                break;
            }
        }
    }

    /**
     * @return string
     */
    public function run(): string
    {
        return $this->render('city', [
            'ip' => $this->ip,
            'city' => $this->city,
            'locationId' => $this->locationId,
        ]);
    }
}