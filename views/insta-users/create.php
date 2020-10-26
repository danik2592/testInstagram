<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstaUsers */

$this->title = 'Create Insta Users';
$this->params['breadcrumbs'][] = ['label' => 'Insta Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insta-users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
