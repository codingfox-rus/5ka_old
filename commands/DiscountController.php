<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\components\parsers\FiveShop;
use app\components\DiscountHelper;
use app\models\DiscountFiveShop;

class DiscountController extends Controller
{
    /** @var FiveShop */
    public $fiveShop;

    /** @var DiscountHelper */
    public $discountHelper;

    public function init()
    {
        parent::init();

        $this->fiveShop = Yii::$app->get('fiveShop');

        $this->discountHelper = Yii::$app->get('discountHelper');
    }

    /**
     * @return string
     */
    public function getDataPath() : string
    {
        return __DIR__ .'/../data/discounts.json';
    }

    /**
     * @return bool
     */
    public function actionGetData() : bool
    {
        $data = $this->fiveShop->getData();

        $dataPath = $this->getDataPath();

        if (file_put_contents($dataPath, $data)) {

            echo 'DiscountFiveShop data saved successfully'. PHP_EOL;

            return true;
        }

        echo 'Error on save discount data'. PHP_EOL;

        return false;
    }

    public function actionHandleData()
    {
        $dataPath = $this->getDataPath();

        $data = json_decode(file_get_contents($dataPath), true);

        if (!empty($data['results'])) {

            $results = $data['results'];

            foreach ($results as $result) {

                $itemId = $result['id'];

                $model = DiscountFiveShop::find()
                    ->where(['itemId' => $itemId])
                    ->one();

                if (!$model) {

                    $model = new DiscountFiveShop();
                }

                $itemData = $this->discountHelper->getItemData($result);

                $model->load(['DiscountFiveShop' => $itemData]);

                if (!$model->save()) {

                    Yii::error(print_r($model->errors, true));
                }
            }
        }
    }
}
