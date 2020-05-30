<?php
namespace app\controllers;

use app\components\markets\FiveShop;
use app\models\Location;
use Exception;
use RuntimeException;
use Yii;
use yii\captcha\CaptchaAction;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\SignupForm;
use app\models\LoginForm;
use app\models\DiscountSearch;
use app\models\User;
use app\models\Page;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
                'actions' => [
                    'set-location' => ['post'],
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $this->addPageInfo(Page::INDEX);

        $searchModel = new DiscountSearch();

        $params = Yii::$app->request->queryParams;
        $params['locationId'] = $_COOKIE['locationId'] ?? $this->getFirstEnabledLocationId() ?? FiveShop::DEFAULT_LOCATION_ID;

        $dataProvider = $searchModel->search($params);

        $pages = new Pagination(['totalCount' => $dataProvider->query->count()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pages' => $pages,
        ]);
    }

    /**
     * @return int|null
     */
    public function getFirstEnabledLocationId():? int
    {
        $location = Location::find()->enabled()->one();

        return $location->id ?? null;
    }

    /**
     * @return Response
     */
    public function actionSetLocation(): Response
    {
        $locationId = Yii::$app->request->post('locationId');

        setcookie('locationId', $locationId, time() + 60 * 60 * 24 * 7, '/');

        return $this->redirect(['index']);
    }

    /**
     * @param null $locationId
     * @return Response
     */
    public function actionSelectCity($locationId = null): Response
    {
        if ($locationId) {

            $locationTime = 60 * 60 * 24 * 30; // todo: найти лучший способ

            setcookie('userLocationId', $locationId, time() + $locationTime, '/');
        }

        return $this->redirect(['index']);
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
     * @throws Exception
     */
    public function actionActivation(string $code): Response
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

            throw new RuntimeException('Ошибка активации');
        }

        return $this->redirect('error', [
            'message' => 'Неверный код активации',
        ]);
    }

    /**
     * todo spam
     * @return string|Response
     */
    public function actionFeedback()
    {
        return $this->redirect('index');
    }

    /**
     * @return string
     */
    public function actionAbout(): string
    {
        $this->addPageInfo(Page::ABOUT);
        $page = $this->getPage(Page::ABOUT);

        if ($page) {
            $content = $page->content;
        } else {
            $content = 'Проект по мониторингу скидок в продовольственных магазинах';
        }

        return $this->render('about', [
            'content' => $content,
        ]);
    }

    /**
     * @param string $pageName
     */
    protected function addPageInfo(string $pageName): void
    {
        $page = $this->getPage($pageName);

        if ($page) {

            Yii::$app->view->title = $page->title;

            Yii::$app->view->registerMetaTag([
                'name' => 'keywords',
                'content' => $page->keywords,
            ]);

            Yii::$app->view->registerMetaTag([
                'name' => 'description',
                'content' => $page->description,
            ]);
        }
    }

    /**
     * @param string $name
     * @return Page|null
     */
    protected function getPage(string $name): ?Page
    {
        return Page::findOne(['name' => $name]);
    }
}
