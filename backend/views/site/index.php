<?php

/* @var $this yii\web\View */

use backend\models\User;
use backend\models\Devices;
use frontend\models\SalesTripsSearch;
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = '';
?>

<div class="row">
    <div class="col-md-7 col-lg-7 col-xs-12 col-sm-12">
        <strong class="lead">SUMMARY REPORT</strong>
    </div>
</div>
<hr>

<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow-gradient"><i class="fa fa-calculator"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">
                  TOTAL DEVICES
                </span>
                <span class="info-box-number">

                 <?php
                 Devices::getBranchRegisteredDevices()
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
              ACTIVE DEVICES
                </span>
                <span class="info-box-number">
                 <?php
                 Devices::getBranchDevicesInOperation()
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
                    FAULT DEVICES
                </span>
                <span class="info-box-number">

                     <?php
                     Devices::getBranchFaultDevices()
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
                DAMAGED  DEVICES
                </span>
                <span class="info-box-number">
                 <?php
                 Devices::getBranchDamagedDevices()
                 ?>

                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

</div>


<hr>
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow-gradient"><i class="fa fa-calculator"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">
                  ON ROAD 1-7 DAYS
                </span>
                <span class="info-box-number">

                 <?php
                 Devices::getDevices1To7Days()
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
              ON ROAD 8-14 DAYS
                </span>
                <span class="info-box-number">
                 <?php
                 Devices::getDevices8To14Days()
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
                     ONROAD ABOVE 14
                </span>
                <span class="info-box-number">

                     <?php
                     Devices::getDevices14DaysAndAbove()
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
                INTRANSIT DEVICES
                </span>
                <span class="info-box-number">
                 <?php
                 Devices::getBranchIntransitDevices()
                 ?>

                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

</div>
<hr>
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow-gradient"><i class="fa fa-calculator"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">
                  DAILY SALES
                </span>
                <span class="info-box-number">

                 <?php
                 Devices::getDevices1To7Days()
                 ?>

                </span>

                <span class="info-box-text">
                    <div class="row">
                        <div class="col-sm-3">
                             IT:
                        </div>
                        <div class="col-sm-3">
                            100,000
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                             DRY:
                        </div>
                        <div class="col-sm-3">
                            100,000
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                             WET:
                        </div>
                        <div class="col-sm-3">
                            100,000
                        </div>
                    </div>

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
              WEEKLY
                </span>
                <span class="info-box-number">
                 <?php
                 Devices::getDevices8To14Days()
                 ?>

                </span>
                <span class="info-box-text">
                    <div class="row">
                        <div class="col-sm-3">
                             IT:
                        </div>
                        <div class="col-sm-3">
                            100,000
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                             DRY:
                        </div>
                        <div class="col-sm-3">
                            100,000
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                             WET:
                        </div>
                        <div class="col-sm-3">
                            100,000
                        </div>
                    </div>

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
                     MONTHLY
                </span>
                <span class="info-box-number">

                     <?php
                     Devices::getDevices14DaysAndAbove()
                     ?>
                </span>
                <span class="info-box-text">
                    <div class="row">
                        <div class="col-sm-3">
                             IT:
                        </div>
                        <div class="col-sm-3">
                            100,000
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                             DRY:
                        </div>
                        <div class="col-sm-3">
                            100,000
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                             WET:
                        </div>
                        <div class="col-sm-3">
                            100,000
                        </div>
                    </div>

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
                YEARLY
                </span>
                <span class="info-box-number">
                 <?php
                 Devices::getBranchIntransitDevices()
                 ?>

                </span>
                <span class="info-box-text">
                    <div class="row">
                        <div class="col-sm-3">
                             IT:
                        </div>
                        <div class="col-sm-3">
                            100,000
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                             DRY:
                        </div>
                        <div class="col-sm-3">
                            100,000
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                             WET:
                        </div>
                        <div class="col-sm-3">
                            100,000
                        </div>
                    </div>

                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

</div>
<hr>


