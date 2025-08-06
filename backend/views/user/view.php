<?php

use backend\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->username;
\yii\web\YiiAsset::register($this);
?>


<p style="padding-top: 15px"/>
<center>
    <h3>
        <i class="fa fa-user text-default">
            <strong> USER VIEW</strong>
        </i>
    </h3>
</center>

<div class="user-view">
    <div class="row">
        <div class="col-md-8">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    // 'id',
                    'full_name',
                    'username',
                    'mobile',
                    'email:email',
                    'role',
                    [
                        'attribute' => 'user_type',
                        'value' => function ($model) {

                            if ($model->user_type == User::ADMIN) {
                                return 'ADMINISTRATOR';
                            } elseif ($model->user_type == User::OFFICE_STAFF) {
                                return 'OFFICE STAFF';
                            }elseif ($model->user_type == User::PORT_STAFF) {
                                return 'PORT STAFF';
                            }elseif ($model->user_type == User::BORDER_STAFF) {
                                return 'BORDER STAFF';
                            }
                        }

                    ],
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {

                            if ($model->status == User::STATUS_ACTIVE) {
                                return 'Active';
                            } elseif ($model->status == User::STATUS_DELETED) {
                                return 'Disabled';
                            }elseif ($model->status == User::STATUS_INACTIVE) {
                                return 'Disabled';
                            }
                        }

                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>

        <div class="col-md-4">
            <?php if(Yii::$app->user->can('createUser')) { ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php } ?>
            <?= Html::a(Yii::t('app', 'Cancel'), ['index',], ['class' => 'btn btn-warning']) ?>

            <?php if(Yii::$app->user->can('admin')) { ?>
            <?php } ?>
        </div>
    </div>
</div>
