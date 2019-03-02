<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="yandex-verification" content="9930c9dd27d53c07" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="logo-wrapper">
                    <div class="logo">
                        <?= Html::a('FollowSale', '/', [
                            'class' => 'logo-link'
                        ]) ?>
                        <span class="logo-sign">
                            Если покупать, то со скидкой!
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <?php
                    $navItems = [];
                    $navItems[] = ['label' => 'Обратная связь', 'url' => ['/site/feedback']];
                    $navItems[] = ['label' => 'О проекте', 'url' => ['/site/about']];

                    if (Yii::$app->user->isGuest) {

                        $navItems[] = ['label' => 'Войти', 'url' => ['/site/login']];
                        $navItems[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];

                    } else {

                        $navItems[] = '<li>'
                            . Html::beginForm(['/site/logout'], 'post')
                            . Html::submitButton(
                                'Выйти (' . Yii::$app->user->identity->email . ')',
                                ['class' => 'btn btn-link logout']
                            )
                            . Html::endForm()
                            . '</li>';
                    }

                    echo Nav::widget([
                        'options' => ['class' => 'navbar-nav navbar-right'],
                        'items' => $navItems,
                    ]);
                ?>
            </div>
        </div>
    </div>
</header>

<div class="wrap">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(52197286, "init", {
        id:52197286,
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
    });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/52197286" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
