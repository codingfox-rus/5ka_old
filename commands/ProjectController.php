<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Feedback;

/**
 * Различные команды для поддержания работоспособности проекта
 * Class ProjectController
 * @package app\commands
 */
class ProjectController extends Controller
{
    /**
     * Отправляем сообщения с обратной связи админу
     */
    public function actionSendFeedbackMessages(): void
    {
        $feedbacks = Feedback::find()
            ->where([
                'sentAt' => null,
            ])
            ->all();

        $from = Yii::$app->params['smtp']['username'];
        $to = Yii::$app->params['adminEmail'];

        foreach ($feedbacks as $feedback) {
            /** @var Feedback $feedback */

            $mail = Yii::$app->mailer
                ->compose('feedback', ['model' => $feedback])
                ->setFrom($from)
                ->setTo($to)
                ->setSubject('Новое сообщение по обратной связи');

            if ($mail->send()) {

                $feedback->sentAt = time();
                $feedback->save(false);

                echo "Message from {$feedback->email} sent to admin successfully". PHP_EOL;
            }
        }
    }
}