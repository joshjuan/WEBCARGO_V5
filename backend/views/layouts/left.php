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
//                    [
//                        'label' => 'ECTS Setups',
//                        'visible' => Yii::$app->user->can('viewSettingModule'),
//                        'icon' => 'building-o',
//                        'items' => [
//                            [
//                                'label' => 'Location',
//                                'icon' => 'circle text-blue',
//                                'visible' => Yii::$app->user->can('viewSetting'),
//                                'url' => ['/location'],
//                            ],
//                            [
//                                'label' => 'Borders &  Port',
//                                'icon' => 'circle text-blue',
//                                'items' => [
//                                    [
//                                        'label' => 'Border',
//                                        'icon' => 'circle text-green',
//                                        //   'visible' => Yii::$app->user->can('createTrips') || Yii::$app->user->can('admin'),
//                                        'url' => ['/border-port/border'],
//                                    ],
//                                    [
//                                        'label' => 'Port',
//                                        'icon' => 'circle text-green',
//                                        //   'visible' => Yii::$app->user->can('createTrips') || Yii::$app->user->can('admin'),
//                                        'url' => ['/border-port/port'],
//                                    ],
//                                ]
//                            ],
//                            [
//                                'label' => 'User Allocated',
//                                'icon' => 'circle text-blue',
//                                //   'visible' => Yii::$app->user->can('createTrips') || Yii::$app->user->can('admin'),
//                                'url' => ['/border-port-user'],
//                            ],
//                            [
//                                'label' => 'Device Category',
//                                'icon' => 'circle text-blue',
//                                //   'visible' => Yii::$app->user->can('createTrips') || Yii::$app->user->can('admin'),
//                                'url' => ['/device-category'],
//                            ],
//                            [
//                                'label' => 'Device Types',
//                                'icon' => 'circle text-blue',
//                                //   'visible' => Yii::$app->user->can('createTrips') || Yii::$app->user->can('admin'),
//                                'url' => ['/device-types'],
//                            ],
//
//                        ],
//                    ],

//                    [
//                        'label' => 'Bills',
//                        'icon' => 'folder-open-o',
//                        'visible' => Yii::$app->user->can('viewBillsModule'),
//                        'items' => [
//                            [
//                                'label' => 'View Bills',
//                                'icon' => 'money',
//                                // 'visible' => Yii::$app->user->can('viewStoreInvestoryModule') || Yii::$app->user->can('admin'),
//                                'url' => ['/'],
//                            ],
//                            [
//                                'label' => 'View Order',
//                                'icon' => 'circle',
//                                'url' => ['/'],
//                            ],
//
//                        ],
//                    ],
                    [
                        'label' => 'Devices Management',
                        'icon' => 'bookmark',
                        'visible' => Yii::$app->user->can('viewDevicesManagementModule'),
                        'url' => '#',
                        'items' => [
//                            [
//                                'label' => 'Devices Registration',
//                                'icon' => 'database text-green',
//                                'visible' => Yii::$app->user->can('viewDeviceList'),
//                                'url' => ['/devices-registration'],
//                            ],
                            [
                                'label' => 'New Device',
                                'icon' => 'plus text-green',
                              //  'visible' => Yii::$app->user->can('registerNewDevices'),
                                'url' => ['/devices-registration/create'],
                            ],
                            [
                                'label' => 'Devices Registration',
                                'icon' => 'database text-green',
                              //  'visible' => Yii::$app->user->can('viewDeviceList'),
                                'url' => ['/devices'],
                            ],

                            [
                                'label' => 'Accounts New Device',
                                'icon' => 'check-square text-green',
                             //   'visible' => Yii::$app->user->can('viewFaultDevices'),
                                'url' => ['/devices/account'],
                            ],
//                             [
//                                'label' => 'Accounts',
//                                'icon' => 'bookmark',
//                                'visible' => Yii::$app->user->can('viewDevicesManagementModule'),
//                                'url' => '#',
//                                'items' => [
//                                    [
//                                        'label' => 'New Device',
//                                        'icon' => 'check-square text-yellow',
//                                        'visible' => Yii::$app->user->can('viewFaultDevices'),
//                                        'url' => ['/devices/account'],
//                                    ],
//                                    [
//                                        'label' => 'Sale records',
//                                        'icon' => 'check-square text-yellow',
//                                        'visible' => Yii::$app->user->can('viewFaultDevices'),
//                                        'url' => ['/sales-trip/index'],
//                                    ],
//                                    [
//                                        'label' => 'Sale per device',
//                                        'icon' => 'check-square text-yellow',
//                                        'visible' => Yii::$app->user->can('viewFaultDevices'),
//                                        'url' => ['/sales-trip/device'],
//                                    ],
//                                    [
//                                        'label' => 'Sale summary',
//                                        'icon' => 'check-square text-yellow',
//                                        'visible' => Yii::$app->user->can('viewFaultDevices'),
//                                        'url' => ['/sales-trip/summary'],
//                                    ],
//                                ],
//                            ],

                            [
                                'label' => 'Awaiting Storage',
                                'icon' => 'external-link-square text-green',
                             //   'visible' => Yii::$app->user->can('viewAwaitingReceivedDevice'),
                                'url' => ['/devices/awaiting-storage'],
                            ],

                            [
                                'label' => 'Store',
                                'icon' => 'external-link-square text-green',
                              //  'visible' => Yii::$app->user->can('viewAwaitingReceivedDevice'),
                                'url' => ['/devices/storage'],
                            ],
                            [
                                'label' => 'Awaiting allocation',
                                'icon' => 'external-link-square text-green',
                              //  'visible' => Yii::$app->user->can('viewReceivedDevice'),
                                'url' => ['/devices/awaiting-allocation'],
                            ],
                            [
                                'label' => 'Released',
                                'icon' => 'external-link-square text-green',
                             //   'visible' => Yii::$app->user->can('viewReleasedDevices'),
                                'url' => ['/devices/released'],
                            ],
                            [
                                'label' => 'On Road',
                                'icon' => 'bookmark',
                               // 'visible' => Yii::$app->user->can('viewOnroadDevices'),
                                'url' => '#',
                                'items' => [
                                    [
                                        'label' => 'All On Road',
                                        'icon' => 'check-square text-yellow',
                                       // 'visible' => Yii::$app->user->can('viewOnroadDevices'),
                                        'url' => ['/devices/on-road'],
                                    ],
                                    [
                                        'label' => 'Onroad 1-7 days',
                                        'icon' => 'check-square text-yellow',
                                     //   'visible' => Yii::$app->user->can('viewOnroadDevices'),
                                        'url' => ['/devices/on-road1to7'],
                                    ],
                                    [
                                        'label' => 'Onroad 8-14 days',
                                        'icon' => 'check-square text-yellow',
                                     //   'visible' => Yii::$app->user->can('viewOnroadDevices'),
                                        'url' => ['/devices/on-road8to14'],
                                    ],
                                    [
                                        'label' => 'Onroad above 14',
                                        'icon' => 'check-square text-yellow',
                                    //    'visible' => Yii::$app->user->can('viewOnroadDevices'),
                                        'url' => ['/devices/on-road-above14'],
                                    ],
                                ],
                            ],

                            [
                                'label' => 'Border Received',
                                'icon' => 'location-arrow text-green',
                               // 'visible' => Yii::$app->user->can('viewOnroadDevices'),
                                'url' => ['/devices/border-received'],
                            ],
                            [
                                'label' => 'Border Return',
                                'icon' => 'location-arrow text-green',
                               // 'visible' => Yii::$app->user->can('viewOnroadDevices'),
                                'url' => ['/devices/border-return'],
                            ],
                            [
                                'label' => 'In Transit',
                                'icon' => 'check-square text-green',
                            //    'visible' => Yii::$app->user->can('viewFaultDevices'),
                                'url' => ['/devices/intransit'],
                            ],
                            [
                                'label' => 'Maintenance',
                                'icon' => 'check-square text-green',
                              //  'visible' => Yii::$app->user->can('viewFaultDevices'),
                                'url' => ['/devices/fault'],
                            ],
                            [
                                'label' => 'Damaged',
                                'icon' => 'check-square text-green',
                              //  'visible' => Yii::$app->user->can('viewFaultDevices'),
                                'url' => ['/devices/damaged'],
                            ],

                        ],
                    ],


                    [
                        'label' => 'Reports',
                        'icon' => 'folder-open',
                        'visible' => Yii::$app->user->can('viewReportModule'),
                        'items' => [
                            [
                                'label' => 'Logs Report',
                                'icon' => 'database text-green',
                               // 'visible' => Yii::$app->user->can('viewAwaitingReceivedDeviceReport'),
                                'url' => ['/devices-reports'],
                            ],
                            [
                                'label' => 'Sales Trip',
                                'icon' => 'external-link-square text-green',
                             //   'visible' => Yii::$app->user->can('viewSalesTripReport'),
                                'url' => ['/sales-trips'],
                            ],
                            [
                                'label' => 'Compare Trips',
                                'icon' => 'external-link-square text-green',
                            //    'visible' => Yii::$app->user->can('viewSalesTripReport'),
                                'url' => ['/compare-trips'],
                            ],
//                            [
//                                'label' => 'Two Days Sales Trip',
//                                'icon' => 'external-link-square text-green',
//                                'visible' => Yii::$app->user->can('viewTwoDaysSalesTripReport'),
//                                'url' => ['/sales-trips/two-days'],
//                            ],
                            [
                                'label' => 'Trip per Device',
                                'icon' => 'external-link-square text-green',
                              //  'visible' => Yii::$app->user->can('viewTripPerDevicesReport'),
                                'url' => ['/sales-trips/device'],
                            ],


                        ],
                    ],

                    [
                        'label' => 'Users Management',
                        'icon' => 'user',
                        'visible' => Yii::$app->user->can('viewUsersModule'),
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Users',
                                'icon' => 'certificate text-green',
                                'url' => ['/user'],
                            ],
                            [
                                'label' => 'Manage Permissions',
                                'icon' => 'certificate text-green',
                             //   'visible' => Yii::$app->user->can('viewManagePermissions'),
                                'url' => ['/auth-item/index'],
                            ],
                            [
                                'label' => 'Manage User Roles',
                               // 'visible' => Yii::$app->user->can('viewManageUserRoles'),
                                'icon' => 'certificate text-green',
                                'url' => ['/role/index'],
                            ],
                            [
                                'label' => 'Border User Allocation',
                                'icon' => 'certificate text-green',
                                //   'visible' => Yii::$app->user->can('createTrips') || Yii::$app->user->can('admin'),
                                'url' => ['/border-port-user'],
                            ],

                            [
                                'label' => 'Bill Customer',
                                'icon' => 'certificate text-green',
                                'url' => ['/user/index-bill-customer'],
                            ],

                        ],
                    ],

                    [
                        'label' => 'Settings',
                        'icon' => 'cogs',
                        'visible' => Yii::$app->user->can('viewSettingModule'),
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Branches',
                                'icon' => 'certificate text-green',
                                'url' => ['/branches/index'],
                            ],
//                            [
//                                'label' => 'Audit Trail',
//                                'icon' => 'certificate text-green',
//                                'url' => ['/audit/index'],
//                            ],

//
//                            [
//                                'label' => 'Location',
//                                'icon' => 'certificate text-green',
//                               // 'visible' => Yii::$app->user->can('viewSetting'),
//                                'url' => ['/location'],
//                            ],
                            [
                                'label' => 'Borders &  Port',
                                'icon' => 'certificate text-green',
                                'items' => [
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
                                'label' => 'Device Category',
                                'icon' => 'certificate text-green',
                                //   'visible' => Yii::$app->user->can('createTrips') || Yii::$app->user->can('admin'),
                                'url' => ['/device-category'],
                            ],
                            [
                                'label' => 'Device Types',
                                'icon' => 'certificate text-green',
                                //   'visible' => Yii::$app->user->can('createTrips') || Yii::$app->user->can('admin'),
                                'url' => ['/device-types'],
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
