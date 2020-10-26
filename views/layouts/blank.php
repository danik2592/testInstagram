<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Insta">
    <meta name="description" content="Test Insta">
    <?= Html::csrfMetaTags() ?>
    <title>Тестовое задание про Инстаграм</title>
    <?php $this->head() ?>
    <!-- HTML5 IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/js/html5shiv.min.js"></script>
    <script src="/js/respond.min.js"></script>
    <![endif]-->
<?= $content ?>
</html>
<?php $this->endPage() ?>