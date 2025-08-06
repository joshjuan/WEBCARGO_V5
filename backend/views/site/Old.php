<?php

/* @var $this yii\web\View */

use backend\models\User;
use frontend\models\Devices;
use frontend\models\SalesTripsSearch;
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = '';
?>

<div class="row">
    <div class="col-md-7 col-lg-7 col-xs-12 col-sm-12">
        <strong class="lead">SUMMARY REPORT OF TRUCK SEALED</strong>
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
                 // Devices::getBranchAvailableTotal()
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
                 //  Devices::getBranchAvailableTunduma()
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
                 //  Devices::getBranchAvailableKabanga()
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
                     // Devices::getBranchAvailableRusumo()
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
                     //  Devices::getBranchAvailableMtukula()
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
                     // Devices::getBranchAvailableKigoma()
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
                 // Devices::getBranchAvailableKasumulu()
                 ?>

                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>
<hr>




