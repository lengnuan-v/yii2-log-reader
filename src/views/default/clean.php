<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '删除';
$this->params['breadcrumbs'][] = ['label' => '日志', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->log->name, 'url' => ['history', 'slug' => $model->log->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .control-label {
        font-size: 13px;
    }
</style>
<div class="card card-shadow mb-2">
    <div class="card-body">

    <?php $form = ActiveForm::begin();?>

    <?= $form->field($model, 'start')->input('date');?>
    <?= $form->field($model, 'end')->input('date');?>

    <?= Html::submitInput('提交', ['class' => 'btn btn-primary', 'data-confirm' => '确定删除吗?']);?>

    <?php $form->end();?>
    </div>
</div>
