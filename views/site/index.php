<?php

use yii\helpers\Html;

\app\assets\InstaListAsset::register($this);
?>
<div class="row">
    <div class="col-md-3">
        <?= Html::a('Список пользователей инстаграмм', ['/insta-users/index'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="col-md-3">
        <?= Html::a('Список постов пользователей', ['/insta-posts/index'], ['class' => 'btn btn-success']) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="margin-top: 10pt;">
        <h1>Список постов</h1>
    </div>
</div>
<div>
    <div id="react-app" />
</div>
