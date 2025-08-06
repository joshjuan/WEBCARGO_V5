<?php

use backend\models\Devices;
use backend\models\InTransit;
use backend\models\StockDevices;
use rmrevin\yii\fontawesome\component\Icon;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>ECTS</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg margin:200px">
            ECTS Portal - MIS
            </span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>

            <?php
            /*            if (!Yii::$app->user->isGuest) {
                            echo 'Cheo: ';
                            echo Yii::$app->user->identity->username;
                            echo ' @ ';
                            echo Yii::$app->user->identity->role;
                        }*/ ?>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o" title="Long Trip more than 7 days"></i>
                        <span class="label label-warning"><?php // Devices::LongTrips() ?></span>
                    </a>
                </li>
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o" title="In Transit (less than 3 days)"></i>
                        <span class="label label-primary"><?php // Devices::InTransit() ?></span>
                    </a>
                </li>
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o" title="In transit more than 3 days (Not Attended)"></i>
                        <span class="label label-danger"><?php // Devices::notAttended() ?></span>
                    </a>
                </li>
         
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                        <span class="hidden-xs">
                           Hello, <?php if (!Yii::$app->user->isGuest) {
                                //  echo " " . Yii::$app->user->identity->username;

                                echo Yii::$app->user->identity->username;
                                //   $user_id = Yii::$app->user->identity->id

                            } else if (Yii::$app->user->isGuest) {

                                return Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl());


                            }
                            ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?php if (!Yii::$app->user->isGuest) {
                                    //  echo " " . Yii::$app->user->identity->username;

                                    echo Yii::$app->user->identity->username;
                                    //   $user_id = Yii::$app->user->identity->id

                                } else if (Yii::$app->user->isGuest) {

                                    return Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl());


                                }
                                ?>
                                <!-- <small>Member since Nov. 2012</small>-->
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(
                                    Yii::t('yii','Sign out'),
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            <div class="pull-right">

                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
<!--                    <a href="#" "><i class="fa fa-gears"></i></a>-->
                </li>
            </ul>
        </div>
    </nav>
</header>
