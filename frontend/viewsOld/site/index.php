<?php

/* @var $this yii\web\View */

use backend\models\User;
use frontend\models\Devices;
use frontend\models\SalesTripsSearch;
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = '';
?>
<?php if (Yii::$app->user->identity->user_type != User::BILL_STAFF) { ?>
    <ul class="nav nav-tabs">
        <div class="site-index" style="font-size: 12px; font-family: Tahoma, sans-serif">
            <div class="row">
                <div class="col-md-7 col-lg-7 col-xs-12 col-sm-12">
                    <strong class="lead"><b>SUMMARISED DASHBOARD REPORT</b></strong>
                </div>
                <div class="col-md-1 col-lg-1 col-xs-12 col-sm-12">

                </div>
                <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12 text-right">
                    <strong class="lead">
                        <small> Date: <?= date('Y-m-d'); ?></small>
                    </strong>
                </div>
            </div>
            <p style="padding-top: 1%"/>
        </div>
        <h4>
            <ul class="nav nav-tabs">
                <!--   <li><a href="#tab_general" data-toggle="tab" class="fa fa-sitemap"><b> GENERAL SALES
                               SUMMARY</b></a></li>-->

                <li class="active"><a href="#tab_tagger" data-toggle="tab" class="fa fa-th"
                                      style="color: #696969"><b>
                            TAGGERS REPORT</b></a></li>
                <li><a href="#tab_devices_status" data-toggle="tab" class="fa fa-th"
                       style="color: #696969"><b>
                            DEVICE(S) STATUS</b></a></li>
                <li><a href="#tab_border_devices" data-toggle="tab" class="fa fa-car" style="color: #696969"><b>
                            DEVICE(S)
                            IN BORDER</b></a></li>
                <?php if (Yii::$app->user->identity->branch == 1 && Yii::$app->user->identity->user_type != User::PARTNER) { ?>
                    <li><a href="#tab_port_devices" data-toggle="tab" class="fa fa-car" style="color: #696969"><b>
                                DEVICE(S)
                                IN SALES POINT</b></a></li>
                <?php } elseif (Yii::$app->user->identity->branch == 2) { ?>
                    <li><a href="#tab_port_devices_branch" data-toggle="tab" class="fa fa-car"
                           style="color: #696969"><b>
                                DEVICE(S)
                                IN SALES POINT</b></a></li>
                <?php } ?>
            </ul>
        </h4>
        <div class="tab-content">
            <div class="tab-content">
                <?php if (Yii::$app->user->identity->user_type == User::ADMIN) { ?>
                    <div class="tab-pane active" id="tab_tagger">
                        <strong class="lead">TODAY TAGGERS REPORT </strong>
                        <div>
                            <?php

                            $searchModel = new SalesTripsSearch();
                            $dataProvider = $searchModel->searchTagger(Yii::$app->request->queryParams);

                            ?>
                            <?=
                            GridView::widget([
                                'dataProvider' => $dataProvider,
                                // 'filterModel' => $searchModel,
                                'summary' => '',
                                'showPageSummary' => true,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'sale_date',
                                    [
                                        'attribute' => 'sales_person',
                                        'value' => 'salesPerson.username',
                                    ],
                                    [
                                        'attribute' => 'sold_items',
                                        'pageSummary' => true
                                    ],
                                    [
                                        'attribute' => 'amount',
                                        'format' => ['decimal', 2],
                                        'pageSummary' => true
                                    ],
                                ],
                            ]);

                            ?>

                        </div>
                    </div>
                <?php } ?>
                <div class="tab-pane" id="tab_devices_status">
                    <p style="padding-top: 2%"/>
                    <div class="col-md-7 col-lg-7 col-xs-12 col-sm-12">
                        <strong class="lead">DEVICES STATUS REPORT </strong>
                    </div>
                    <p style="padding-top: 2%"/>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-dropbox"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
             AVAILABLE
                </span>
                                    <span class="info-box-number">

                   <?php
                   Devices::getBranchAvailable()
                   ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-openid"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                    AWAITING RECEIVE
                </span>
                                    <span class="info-box-number">

                 <?php
                 Devices::getBranchActive()
                 ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <!-- fix for small devices only -->
                        <div class="clearfix visible-sm-block"></div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-stumbleupon"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                    RELEASED
                </span>
                                    <span class="info-box-number">
                 <?php
                 Devices::getBranchReleased()
                 ?>


                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-slideshare"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                  FAULT
                </span>
                                    <span class="info-box-number">

                 <?php
                 Devices::getBranchFault()
                 ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-slideshare"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                    IN TRANSIT
                </span>
                                    <span class="info-box-number">

                 <?php
                 Devices::getBranchAvailableNotDeactivated()
                 ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-slideshare"></i></span>

                                <div class="info-box-content">
                               <span class="info-box-text">
                    ON ROAD
                               </span>
                                    <span class="info-box-number">

                 <?php
                 Devices::getOnRoad()
                 ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-slideshare"></i></span>

                                <div class="info-box-content">
                               <span class="info-box-text">
                        RECEIVED
                               </span>
                                    <span class="info-box-number">

                 <?php
                 Devices::getReceived()
                 ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-slideshare"></i></span>

                                <div class="info-box-content">
                               <span class="info-box-text">
                    TOTAL STOCK
                               </span>
                                    <span class="info-box-number">

                 <?php
                 Devices::getBranchStock()
                 ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    </div>
                </div>

                <div class="tab-pane" id="tab_border_devices">
                    <p style="padding-top: 2%"/>
                    <div class="col-md-7 col-lg-7 col-xs-12 col-sm-12">
                        <strong class="lead">DAILY REPORT FOR DEVICE IN BORDER</strong>
                    </div>
                    <p style="padding-top: 2%"/>
                    <div class="row">

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-green-gradient"><i class="fa fa-chrome"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                  KASUMULU
                </span>
                                    <span class="info-box-number">

                 <?php
                 Devices::getBranchAvailableKasumulu()
                 ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                            <span class="info-box-icon bg-green-gradient"><i
                                        class="fa fa-chevron-circle-right"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
              TUNDUMA
                </span>
                                    <span class="info-box-number">
                 <?php
                 Devices::getBranchAvailableTunduma()
                 ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <!-- fix for small devices only -->
                        <div class="clearfix visible-sm-block"></div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-green-gradient"><i class="fa fa-delicious"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                   KABANGA
                </span>
                                    <span class="info-box-number">
                 <?php
                 Devices::getBranchAvailableKabanga()
                 ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-green-gradient"><i class="fa fa-deviantart"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                    RUSUMO
                </span>
                                    <span class="info-box-number">

                     <?php
                     Devices::getBranchAvailableRusumo()
                     ?>
                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-green-gradient"><i class="fa fa-deviantart"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                 MTUKULA
                </span>
                                    <span class="info-box-number">

                     <?php
                     Devices::getBranchAvailableMtukula()
                     ?>
                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    </div>
                </div>

                <div class="tab-pane" id="tab_port_devices">
                    <p style="padding-top: 2%"/>
                    <div class="col-md-7 col-lg-7 col-xs-12 col-sm-12">
                        <strong class="lead">DAILY REPORT FOR DEVICE IN SALES POINT</strong>
                    </div>
                    <p style="padding-top: 2%"/>
                    <div class="row">

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua-gradient"><i class="fa fa-gg-circle"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                    GATE NO 2
                </span>
                                    <span class="info-box-number">
                 <?php
                 Devices::getBranchAvailableGateTwo()
                 ?>
                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua-gradient"><i
                                            class="fa fa-creative-commons"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                 GATE NO 3
                </span>
                                    <span class="info-box-number">

                 <?php
                 Devices::getBranchAvailableGateThree()
                 ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua-gradient"><i class="fa fa-bullseye"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                     GATE NO 5
                </span>
                                    <span class="info-box-number">

                 <?php
                 Devices::getBranchAvailableGateFive()
                 ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua-gradient"><i class="fa fa-certificate"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                 MALAWI CARGO
                </span>
                                    <span class="info-box-number">

                 <?php
                 Devices::getBranchAvailableGateMalawi()
                 ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua-gradient"><i class="fa fa-diamond"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                 KICD
                </span>
                                    <span class="info-box-number">

                 <?php
                 Devices::getBranchAvailableGateKicd()
                 ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab_port_devices_branch">
                    <p style="padding-top: 2%"/>
                    <div class="col-md-7 col-lg-7 col-xs-12 col-sm-12">
                        <strong class="lead">DAILY REPORT FOR DEVICE IN SALES POINT</strong>
                    </div>
                    <p style="padding-top: 2%"/>
                    <div class="row">

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua-gradient"><i class="fa fa-gg-circle"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                    KIGAMBONI
                </span>
                                    <span class="info-box-number">
                 <?php
                 Devices::getBranchAvailableKigamboni()
                 ?>
                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua-gradient"><i
                                            class="fa fa-creative-commons"></i></span>

                                <div class="info-box-content">
                <span class="info-box-text">
                 KURASINI
                </span>
                                    <span class="info-box-number">

                 <?php
                 Devices::getBranchAvailableKurasini()
                 ?>

                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </ul>

<?php } else { ?>
<div class="row">
    <div class="col-md-7 col-lg-7 col-xs-12 col-sm-12">
        <strong class="lead">SUMMARY REPORT OF TRUCK SEALED PER MONTH</strong>
    </div>
</div>
<hr>
    <div class="row">

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow-gradient"><i class="fa fa-calculator"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">
                  TOTAL TRUCKS
                </span>
                    <span class="info-box-number">

                 <?php
                 Devices::getBranchAvailableTotal()
                 ?>

                </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                            <span class="info-box-icon bg-green-gradient"><i
                                    class="fa fa-chevron-circle-right"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">
              TUNDUMA
                </span>
                    <span class="info-box-number">
                 <?php
                 Devices::getBranchAvailableTunduma()
                 ?>

                </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green-gradient"><i class="fa fa-delicious"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">
                   KABANGA
                </span>
                    <span class="info-box-number">
                 <?php
                 Devices::getBranchAvailableKabanga()
                 ?>

                </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green-gradient"><i class="fa fa-deviantart"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">
                    RUSUMO
                </span>
                    <span class="info-box-number">

                     <?php
                     Devices::getBranchAvailableRusumo()
                     ?>
                </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    </div>
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green-gradient"><i class="fa fa-deviantart"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">
                 MTUKULA
                </span>
                <span class="info-box-number">

                     <?php
                     Devices::getBranchAvailableMtukula()
                     ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green-gradient"><i class="fa fa-deviantart"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">
                 KIGOMA
                </span>
                <span class="info-box-number">

                     <?php
                     Devices::getBranchAvailableKigoma()
                     ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green-gradient"><i class="fa fa-chrome"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">
                  KASUMULU
                </span>
                <span class="info-box-number">

                 <?php
                 Devices::getBranchAvailableKasumulu()
                 ?>

                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>
    <hr>
    <div class="tab-content">
        <div class="tab-content">

            <div class="tab-pane active" id="tab_tagger">
                <strong class="lead">TODAY TAGGERS REPORT </strong>
                <div>
                    <?php

                    $searchModel = new SalesTripsSearch();
                    $dataProvider = $searchModel->searchTagger(Yii::$app->request->queryParams);

                    ?>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        // 'filterModel' => $searchModel,
                        'summary' => '',
                        'showPageSummary' => true,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'sale_date',
                            [
                                'attribute' => 'sales_person',
                                'value' => 'salesPerson.username',
                            ],
                            [
                                'attribute' => 'sold_items',
                                'pageSummary' => true
                            ],
                        ],
                    ]);

                    ?>

                </div>
            </div>

        </div>
    </div>


<?php } ?>
