<?php

use frontend\models\Devices;
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
            ECTS- Branch MIS
            </span>
    </a>
    <nav class="navbar navbar-static-top">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>

            <?php
            if (!Yii::$app->user->isGuest) {
                $branch = \frontend\models\Branches::find()
                    ->where(['id' => Yii::$app->user->identity->branch])
                    ->one();

                if ($branch) {
                    $role=Yii::$app->user->identity->role;
                    echo "Branch $role : " . $branch->name;
                }

            }
            ?>


        </a>

        <div class="container">

            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->

                    <!-- /.messages-menu -->

                    <!-- Notifications Menu -->
                    <li class="dropdown notifications-menu">
                        <!-- Menu toggle button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-danger"><?php Devices::getDevicesMoreThan3DaysIntransit() ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have <?php Devices::getDevicesMoreThan3DaysIntransit() ?> intransit device (Not deactivated) with more than 3 days</li>
                            <li>
                            </li>
                            <li class="footer"> <?= Html::a(Yii::t('app', 'view all'), ['/devices/intransit-more-than-days']); ?></a></li>
                        </ul>
                    </li>
                    <li class="dropdown notifications-menu">
                        <!-- Menu toggle button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-danger"><?php Devices::getDevicesMoreThan7Days() ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have <?php Devices::getDevicesMoreThan7Days() ?> onroad devices with more than 7 days  </li>
                            <li>
                            </li>
                            <li class="footer"> <?= Html::a(Yii::t('app', 'view all'), ['/devices/on-road-more-than-days']); ?></a></li>
                        </ul>
                    </li>

                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->

                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?php
                                if (!Yii::$app->user->isGuest){
                                    echo "Hello! ". Yii::$app->user->identity->username;
                                }
                                ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                    <?= Html::a(
                                        Yii::t('yii', 'Sign out'),
                                        ['/site/logout'],
                                        ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                    ) ?>
                                </div>
                                <div class="pull-left">

                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-custom-menu -->
        </div>
        <!-- /.container-fluid -->
    </nav>
</header>
