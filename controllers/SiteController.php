<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\SignupForm;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\DiscountSearch;
use app\models\User;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DiscountSearch();

        $dataProvider = $searchModel->search(['DiscountSearch' => Yii::$app->request->queryParams]);

        $pages = new Pagination(['totalCount' => $dataProvider->query->count()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pages' => $pages,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {

            Yii::$app->session->setFlash('confirm');

            return $this->redirect('confirm');
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionConfirm()
    {
        if (!Yii::$app->session->getFlash('confirm')) {
            return $this->redirect('index');
        }

        return $this->render('confirm');
    }

    /**
     * @param string $code
     * @return Response
     * @throws \Exception
     */
    public function actionActivation(string $code)
    {
        $user = User::findOne(['activationCode' => $code]);

        $condition = $user && $user->activationRequestAt > time() - SignupForm::TIME_FOR_ACTIVATION;

        if ($condition) {

            $user->activationCode = null;
            $user->activationRequestAt = null;
            $user->status = User::STATUS_ACTIVE;

            if ($user->save()) {
                $message = 'Ваш аккаунт успешно активирован. Вы можете войти на сайт, используя свой логин и пароль';

                Yii::$app->session->setFlash('activationSuccess', $message);

                return $this->redirect('login');
            }

            throw new \Exception('Ошибка активации');
        }

        return $this->redirect('error', [
            'message' => 'Неверный код активации',
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
