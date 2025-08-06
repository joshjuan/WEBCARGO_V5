<?php

use yii\helpers\Html;

?>

<aside class="main-sidebar">
    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                  <span class="logo-lg margin:200px">



            </span>
            </div>
            <div class="pull-left info">
                <p>


                </p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>


        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [

                    [
                        'label' => 'Dashboard',
                        'icon' => 'home',
                        'url' => ['/']
                    ],
                    [
                        'label' => 'ECTS Setting',
                        'visible' => Yii::$app->user->can('viewECTSSettingModule') ,
                        'icon' => 'building-o',
                        'items' => [
                            [
                                'label' => 'Location',
                                'icon' => 'circle text-blue',
                                //   'visible' => Yii::$app->user->can('createTrips') || Yii::$app->user->can('admin'),
                                'url' => ['/location'],
                            ],
                            [
                                'label' => 'Borders &  Port',
                                'icon' => 'circle text-blue',
                                'items'=>[
                                    [
                                        'label' => 'Border',
                                        'icon' => 'circle text-green',
                                        //   'visible' => Yii::$app->user->can('createTrips') || Yii::$app->user->can('admin'),
                                        'url' => ['/border-port/border'],
                                    ],
                                    [
                                        'label' => 'Port',
                                        'icon' => 'circle text-green',
                                        //   'visible' => Yii::$app->user->can('createTrips') || Yii::$app->user->can('admin'),
                                        'url' => ['/border-port/port'],
                                    ],
                                ]
                            ],
                            [
                                'label' => 'User Allocated',
                                'icon' => 'circle text-blue',
                                //   'visible' => Yii::$app->user->can('createTrips') || Yii::$app->user->can('admin'),
                                'url' => ['/border-port-user'],
                            ],

                        ],
                    ],

                    [
                        'label' => 'Bills',
                        'icon' => 'folder-open-o',
                        'visible' => Yii::$app->user->can('viewBillsModule'),
                        'items' => [
                            [
                                'label' => 'View Bills',
                                'icon' => 'money',
                                // 'visible' => Yii::$app->user->can('viewStoreInvestoryModule') || Yii::$app->user->can('admin'),
                                'url' => ['/'],
                            ],
                            [
                                'label' => 'View Order',
                                'icon' => 'circle',
                                'url' => ['/'],
                            ],

                        ],
                    ],

                    [
                        'label' => 'Devices Management',
                        'icon' => 'bookmark',
                        'visible' => Yii::$app->user->can('viewDevicesManagementModule') ,
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Devices Register',
                                'icon' => 'database text-green',
                                'visible' => Yii::$app->user->can('viewDeviceList'),
                                'url' => ['/devices'],
                            ],
                            [
                                'label' => 'Devices Current Status',
                                'icon' => 'database text-green',
                                'visible' => Yii::$app->user->can('viewDeviceList'),
                                'url' => ['/devices/current-status'],
                            ],
                            [
                                'label' => 'Awaiting Receive',
                                'icon' => 'external-link-square text-green',
                                'visible' => Yii::$app->user->can('viewAwaitingReceivedDevice'),
                                'url' => ['/devices/awaiting-receive'],
                            ],
                            [
                                'label' => 'Received',
                                'icon' => 'external-link-square text-green',
                                'visible' => Yii::$app->user->can('viewReceivedDevice'),
                                'url' => ['/devices/received'],
                            ],
                            [
                                'label' => 'In Transit',
                                'icon' => 'recycle text-green',
                                'visible' => Yii::$app->user->can('viewDeviceInTransit'),
                                'url' => ['/devices/intransit'],
                            ],
                            [
                                'label' => 'Available',
                                'icon' => 'recycle text-green',
                                'visible' => Yii::$app->user->can('viewAvailbleDevices'),
                                'url' => ['/devices/available'],
                            ],
                            [
                                'label' => 'Released',
                                'icon' => 'external-link-square text-green',
                                'visible' => Yii::$app->user->can('viewReleasedDevices'),
                                'url' => ['/devices/released'],
                            ],

                            [
                                'label' => 'On Road',
                                'icon' => 'location-arrow text-green',
                                'visible' => Yii::$app->user->can('viewOnroadDevices'),
                                'url' => ['/devices/on-road'],
                            ],
                            [
                                'label' => 'Fault',
                                'icon' => 'check-square text-green',
                                'visible' => Yii::$app->user->can('viewFaultDevices'),
                                'url' => ['/devices/fault'],
                            ],

                        ],
                    ],

                    [
                        'label' => 'Reports',
                        'icon' => 'folder-open',
                        'visible' => Yii::$app->user->can('viewReportModule') ,
                        'items' => [
                            [
                                'label' => 'Awaiting Receive',
                                'icon' => 'database text-green',
                                'visible' => Yii::$app->user->can('viewAwaitingReceivedDeviceReport'),
                                'url' => ['/awaiting-receive-report'],
                            ],
                            [
                                'label' => 'Received',
                                'icon' => 'database text-green',
                                'visible' => Yii::$app->user->can('viewReceivedDeviceReport'),
                                'url' => ['/received-devices-report'],
                            ],
                            [
                                'label' => 'Released ',
                                'icon' => 'recycle text-green',
                                'visible' => Yii::$app->user->can('viewReleasedDeviceReport'),
                                'url' => ['/released-devices-report'],
                            ],
                            [
                                'label' => 'Transfer ',
                                'icon' => 'recycle text-green',
                                'visible' => Yii::$app->user->can('viewReleasedDeviceReport'),
                                'url' => ['/transfer-devices'],
                            ],

                            [
                                'label' => 'Available',
                                'icon' => 'recycle text-green',
                                'visible' => Yii::$app->user->can('viewAvailableDeviceReport'),
                                'url' => ['/stock-devices-report'],
                            ],
                            [
                                'label' => 'Sub Sale',
                                'icon' => 'external-link-square text-green',
                                'visible' => Yii::$app->user->can('viewDevicesManagementModule'),
                                'url' => ['/sales-trips/create'],
                            ],
                            [
                                'label' => 'Sales Trip',
                                'icon' => 'external-link-square text-green',
                                'visible' => Yii::$app->user->can('viewSalesTripReport'),
                                'url' => ['/sales-trips'],
                            ],
                            [
                                'label' => 'Two Days Sales Trip',
                                'icon' => 'external-link-square text-green',
                                'visible' => Yii::$app->user->can('viewTwoDaysSalesTripReport'),
                                'url' => ['/sales-trips/two-days'],
                            ],
                            [
                                'label' => 'On Road',
                                'icon' => 'location-arrow text-green',
                                'visible' => Yii::$app->user->can('viewOnRoadReport'),
                                'url' => ['/in-transit-report'],
                            ],
                            [
                                'label' => 'All On Road',
                                'icon' => 'location-arrow text-green',
                                'visible' => Yii::$app->user->can('viewOnRoadReport'),
                                'url' => ['/in-transit-report/all'],
                            ],
                            [
                                'label' => 'Trip per Device',
                                'icon' => 'external-link-square text-green',
                                'visible' => Yii::$app->user->can('viewTripPerDevicesReport'),
                                'url' => ['/sales-trips/device'],
                            ],
                            [
                                'label' => 'Fault',
                                'icon' => 'check-square text-green',
                                'visible' => Yii::$app->user->can('viewFaultDevicesReport'),
                                'url' => ['/fault-devices-report'],
                            ],

                        ],
                    ],

                    [
                        'label' => 'Users Management',
                        'icon' => 'user',
                        'visible' => Yii::$app->user->can('viewSystemUsersModule') ,
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Office Staff Users',
                                'icon' => 'user text-green',
                                'url' => ['/user'],
                            ],

                            [
                                'label' => 'Bill Customer Users',
                                'icon' => 'user text-green',
                                'url' => ['/user/index-bill-customer'],
                            ],


                        ],
                    ],

                    [
                        'label' => 'Settings',
                        'icon' => 'cogs',
                        'visible' => Yii::$app->user->can('viewSettingModule') ,
                        'url' => '#',
                        'items' => [
//                            [
//                                'label' => 'Branches',
//                                'icon' => 'certificate text-green',
//                                'url' => ['/branches/index'],
//                            ],
//                            [
//                                'label' => 'Audit Trail',
//                                'icon' => 'certificate text-green',
//                                'url' => ['/audit/index'],
//                            ],
                            [
                                'label' => 'Manage Permissions',
                                'icon' => 'certificate text-green',
                                'visible' => Yii::$app->user->can('viewManagePermissions'),
                                'url' => ['/auth-item/index'],
                            ],
                            [
                                'label' => 'Manage User Roles',
                                'visible' => Yii::$app->user->can('viewManageUserRoles'),
                                'icon' => 'certificate text-green',
                                'url' => ['/role/index'],
                            ],

                            /*   [
                                   'label' => 'Assign Permissions ',
                                   'icon' => 'certificate', 'url' => ['/auth-assignment'],
                               ],*/
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
