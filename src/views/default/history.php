<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\i18n\Formatter;
use lengnuan\logReader\Log;

$this->title = '历史';
$this->params['breadcrumbs'][] = ['label' => '日志', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $name, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$formatter = new Formatter();
$fullSizeFormat = $formatter->format($fullSize, 'shortSize');
$captionBtn = [];
if ($fullSize > 1) {
    $captionBtn[] = Html::a('打包', ['zip', 'slug' => Yii::$app->request->get('slug')], ['class' => 'btn btn-success btn-xs']);
    $captionBtn[] = Html::a('批量删除', ['clean', 'slug' => Yii::$app->request->get('slug')], ['class' => 'btn btn-danger btn-xs']);
}
$captionBtnStr = implode(' ', $captionBtn);
?>
<div class="log-reader-history">
    <div class="card card-shadow mb-2">
        <div class="card-body">
            <div class="custom-title-wrap bar-primary mb-4">
                <div class="custom-title">
                    <span class="float-left f13 weight400"><i class="fa fa-bar-chart"></i>
                        共计 <?= count($dataProvider->allModels)?>  个日志 (<?= $fullSizeFormat;?>)
                    </span>
                </div>
                <div class="float-right mb-3">
                    <?= $captionBtnStr;?>
                </div>
            </div>
            <?= GridView::widget([
                'tableOptions' => ['class' => 'table'],
                'options'      => ['class' => 'grid-view table-responsive'],
                'layout'       => "{items}\n{pager}",
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'label'     => '日志',
                        'attribute' => 'fileName',
                        'format'    => 'raw',
                        'value' => function (Log $log) {
                            return pathinfo($log->fileName, PATHINFO_BASENAME);
                        },
                    ],
                    [
                        'label'     => '数量',
                        'attribute' => 'counts',
                        'format'    => 'raw',
                        'headerOptions'  => ['class' => 'sort-ordinal text-center'],
                        'contentOptions' => ['class'=>'text-center'],
                        'value' => function (Log $log) {
                            return $this->render('_counts', ['log' => $log]);
                        },
                    ],
                    [
                        'label'     => '大小',
                        'attribute' => 'size',
                        'format'    => 'shortSize',
                        'headerOptions' => ['class' => 'sort-ordinal text-center'],
                        'contentOptions' => ['class'=>'text-center'],
                    ],
                    [
                        'label'     => '时间',
                        'attribute' => 'updatedAt',
                        'format'    => 'relativeTime',
                        'headerOptions' => ['class' => 'sort-numerical text-center'],
                        'contentOptions' => ['class'=>'text-center'],
                    ],
                    [
                        'header'     => '操作',
                        'class'      => '\yii\grid\ActionColumn',
                        'template'   => '{view} {tail} {delete} {download}',
                        'headerOptions' => ['class' => 'sort-numerical text-center'],
                        'contentOptions' => ['class'=>'text-center'],
                        'urlCreator' => function ($action, Log $log) {
                            return [$action, 'slug' => $log->slug, 'stamp' => $log->stamp];
                        },
                        'buttons' => [
                            'view' => function ($url, Log $log) {
                                if ($log->isZip) {
                                    return '';
                                }
                                return Html::a('查看', $url, [
                                    'class' => 'badge badge-primary form-pill px-3 py-1 weight400',
                                    'target' => '_blank',
                                ]);
                            },
                            'delete' => function ($url) {
                                return Html::a('删除', $url, [
                                    'class' => 'badge badge-danger form-pill px-3 py-1 weight400',
                                    'data' => ['method' => 'post', 'confirm' => '确定删除吗？'],
                                ]);
                            },
                            'download' => function ($url, Log $log) {
                                return !$log->isExist ? '' : Html::a('下载', $url, [
                                    'class' => 'badge badge-info form-pill px-3 py-1 weight400',
                                ]);
                            },
                        ],
                    ],
                ],
            ]) ?>
        </div>
    </div>
<?php
$this->registerCss(<<<CSS
.log-reader-history .table tbody td {
   vertical-align: middle;
}
CSS
);
