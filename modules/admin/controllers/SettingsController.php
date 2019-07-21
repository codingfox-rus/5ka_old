<?php
namespace app\modules\admin\controllers;

use Yii;
use app\models\Settings;

class SettingsController extends MainController
{
    /**
     * @return string|\yii\web\Response
     */
    public function actionUpdate()
    {
        $model = Settings::find()->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->refresh();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
}