<?php
use yii\grid\GridView;
use yii\helpers\Html;
use lengnuan\logReader\Log;

$this->title = '日志';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-reader-index">
    <div class="card card-shadow mb-2">
        <div class="card-body">
            <div class="custom-title-wrap bar-primary mb-4">
                <div class="custom-title">
                    <span class="float-left f13 weight400"><i class="fa fa-bar-chart"></i> 共计 <?= count($dataProvider->allModels)?>  个模块</span>
                </div>
            </div>
            <?= GridView::widget([
            'layout'       => '{items}',
            'tableOptions' => ['class' => 'table'],
            'options'      => ['class' => 'grid-view table-responsive'],
            'dataProvider' => $dataProvider,
            'columns'      => [
                [
                    'label' => '日志',
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function (Log $log) {
                        return Html::tag('h5', join("\n", [
                            Html::encode($log->name), ':', Html::tag('small', Html::encode($log->fileName)),
                        ]));
                    },
                ],
                [
                    'label'         => '数量',
                    'attribute'     => 'counts',
                    'format'        => 'raw',
                    'headerOptions' => ['class' => 'sort-ordinal text-center'],
                    'contentOptions' => ['class'=>'text-center'],
                    'value' => function (Log $log) {
                        return $this->render('_counts', ['log' => $log]);
                    },
                ],
                [
                    'label'          => '大小',
                    'attribute'      => 'size',
                    'format'         => 'shortSize',
                    'headerOptions'  => ['class' => 'sort-ordinal text-center'],
                    'contentOptions' => ['class'=>'text-center'],
                ],
                [
                    'label' => '时间',
                    'attribute' => 'updatedAt',
                    'format' => 'relativeTime',
                    'headerOptions' => ['class' => 'sort-numerical text-center'],
                    'contentOptions' => ['class'=>'text-center'],
                ],
                [
                    'header'        => '操作',
                    'class'         => '\yii\grid\ActionColumn',
                    'headerOptions' => ['class' => 'sort-numerical text-center'],
                    'template'      => '{history} {view} {tail} {archive} {delete} {download}',
                    'urlCreator' => function ($action, Log $log) {
                        return [$action, 'slug' => $log->slug];
                    },
                    'buttons' => [
                        'history' => function ($url) {
                            return Html::a('历史', $url, [
                                'class' => 'badge badge-info form-pill px-3 py-1 weight400',
                            ]);
                        },
                        'view' => function ($url, Log $log) {
                            return !$log->isExist ? '' : Html::a('查看', $url, [
                                'class' => 'badge badge-primary form-pill px-3 py-1 weight400',
                                'target' => '_blank',
                            ]);
                        },
                        'archive' => function ($url, Log $log) {
                            return !$log->isExist ? '' : Html::a('存档', $url, [
                                'class' => 'badge badge-success form-pill px-3 py-1 weight400',
                                'data' => ['method' => 'post', 'confirm' => '确定存档吗?'],
                            ]);
                        },
                        'delete' => function ($url, Log $log) {
                            return !$log->isExist ? '' : Html::a('清空', array_merge($url, ['since' => $log->updatedAt]), [
                                'class' => 'badge badge-danger form-pill px-3 py-1 weight400',
                                'data' => ['method' => 'post', 'data-a' => 'aa', 'confirm' => '确定清空吗?'],
                            ]);
                        },
                        'download' => function ($url, Log $log) {
                            return !$log->isExist ? '' : Html::a('下载', $url, [
                                'class' => 'badge badge-purple-light form-pill px-3 py-1 weight400',
                            ]);
                        },
                    ],
                ],
            ],
        ]) ?>
        </div>
    </div>
</div>
<?php
$this->registerCss(<<<CSS
.log-reader-index .table tbody td {
   vertical-align: middle;
}
CSS
);
