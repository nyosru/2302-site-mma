<?php

namespace App\Providers;

use App\Services\PermissionService;
use App\View\Components\AdminPermissionPicker;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use View;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // get all data from menu.json file

        // $verticalOverlayMenu = file_get_contents(base_path('resources/data/menus/vertical-overlay-menu.json'));
        // $verticalOverlayMenuData = json_decode($verticalOverlayMenu);

        // share all menuData to all the views
        Blade::component('admin-permission-picker', AdminPermissionPicker::class);


        View::composer(
            '*', function ($view) {
            $userId = auth()->user()?->id;
            $jayParsedAry = [
                "menu" => [
                    [
                        "url" => "/",
                        "name" => "Home",
                        "icon" => "home",
                        "tag" => "",
                        "tagcustom" => "",
                        "submenu" => []
                    ]
                ]
            ];

            if (PermissionService::allowed($userId, PermissionService::O_DASHBOARD, PermissionService::A_VIEW)) {
                $jayParsedAry['menu'][0]['submenu'][] = [
                    "url" => "/admin/dashboard",
                    "name" => "Dashboard",
                    "icon" => "bx bx-world",
                    "submenu" => [
                        [
                            "url" => "/admin/dashboard",
                            "name" => "Board",
                            "icon" => "bx bx-box"
                        ],
                        [
                            "url" => "/admin/dashboard/analytics",
                            "name" => "Analytics",
                            "icon" => "bx bx-world"
                        ]
                    ]

                ];
            }

            if (PermissionService::allowed($userId, PermissionService::O_APPLICATION, PermissionService::A_VIEW)) {
                $create = [];
                if (PermissionService::allowed($userId, PermissionService::O_APPLICATION, PermissionService::A_CREATE)) {
                    $create = [
                        "url" => "/admin/apps/create",
                        "name" => "create",
                        "icon" => "bx bx-plus"
                    ];
                }
                $jayParsedAry['menu'][0]['submenu'][] = [
                    "url" => "/admin/apps/list",
                    "name" => "Apps",
                    "icon" => "bx bx-store",
                    "submenu" => [
                        $create,
                        [
                            "url" => "/admin/apps/list",
                            "name" => "list",
                            "icon" => "bx bx-list-ul"
                        ],
                        [
                            "url" => "/admin/apps/listTrashed",
                            "name" => "trash",
                            "icon" => "bx bx-trash"
                        ]
                    ]
                ];
            }

            if (PermissionService::allowed($userId, PermissionService::O_CLIENT, PermissionService::A_VIEW)) {
                $jayParsedAry['menu'][0]['submenu'][] = [
                    "url" => "/admin/clients/list",
                    "name" => "Clients",
                    "icon" => "bx bx-circle"
                ];
            }


            if (PermissionService::allowed($userId, PermissionService::O_LOG, PermissionService::A_VIEW)) {
                $jayParsedAry['menu'][0]['submenu'][] = [
                    "url" => "/admin/logs/list",
                    "name" => "Logs center",
                    "icon" => "bx bx-reset"
                ];
            }
            if (PermissionService::allowed($userId, PermissionService::O_NOTIFICATION, PermissionService::A_VIEW)) {
                $jayParsedAry['menu'][0]['submenu'][] = [
                    "url" => "/admin/notifications/list",
                    "name" => "Notification center",
                    "icon" => "bx bx-reset"
                ];
            }

            if (PermissionService::allowed($userId, PermissionService::O_BACKUP, PermissionService::A_VIEW)) {
                $jayParsedAry['menu'][0]['submenu'][] = [
                    "url" => "/admin/backups",
                    "name" => "Backups",
                    "icon" => "bx bx-time"
                ];
            }

            if (PermissionService::allowed($userId, PermissionService::O_USER_LOG, PermissionService::A_VIEW)) {
                $jayParsedAry['menu'][0]['submenu'][] = [
                    "url" => "/admin/users-logs/list",
                    "name" => "Users Logs",
                    "icon" => "bx bx-time"
                ];
            }

            if (PermissionService::allowed($userId, PermissionService::O_USER, PermissionService::A_VIEW)) {
                $jayParsedAry['menu'][0]['submenu'][] = [
                    "url" => "/admin/users/list",
                    "name" => "Users",
                    "icon" => "bx bx-user"
                ];
            }

            if (PermissionService::allowed($userId, PermissionService::O_PERMISSION, PermissionService::A_VIEW)) {
                $jayParsedAry['menu'][0]['submenu'][] = [
                    "url" => "/admin/user-roles",
                    "name" => "User roles",
                    "icon" => "bx bx-list-ul"
                ];
            }


            if (PermissionService::allowed($userId, PermissionService::O_SETTING, PermissionService::A_VIEW)) {
                $jayParsedAry['menu'][0]['submenu'][] = [
                    "url" => "/admin/settings",
                    "name" => "Settings",
                    "icon" => "bx bx-right-arrow-alt"
                ];
            }

            if (PermissionService::allowed($userId, PermissionService::O_TOKEN, PermissionService::A_VIEW)) {
                $jayParsedAry['menu'][0]['submenu'][] = [
                    "url" => "/admin/tokens",
                    "name" => "Tokens",
                    "icon" => "bx bx-right-arrow-alt"
                ];
            }

            $verticalMenuData = json_decode(json_encode($jayParsedAry));
            $horizontalMenuJson = file_get_contents(base_path('resources/data/menus/horizontal-menu.json'));
            $horizontalMenuData = json_decode($horizontalMenuJson);
            $verticalMenuBoxiconsJson = file_get_contents(base_path('resources/data/menus/vertical-menu-boxicons.json'));
            $verticalMenuBoxiconsData = json_decode($verticalMenuBoxiconsJson);

            $view->with('menuData', [$verticalMenuData, $horizontalMenuData, $verticalMenuBoxiconsData]);
        }
        );
        //        \View::share('menuData',[$verticalMenuData, $horizontalMenuData,$verticalMenuBoxiconsData]);
    }
}
