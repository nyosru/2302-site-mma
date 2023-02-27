<?php

namespace App\Services;

use Lauthz\Facades\Enforcer;
use SoftInvest\Helpers\DBWrap;

class PermissionService
{
    // -- Subject
    public const R_ADMIN = 'admin';
    public const R_MANAGER = 'manager';
    public const R_DEVELOPER = 'developer';
    public const R_DEVOPS = 'devops';

    // -- Object
    public const O_APPLICATION = 'app';
    public const O_DASHBOARD = 'dashboard';
    public const O_CLIENT = 'client';
    public const O_LOG = 'log';
    public const O_NOTIFICATION = 'notification';
    public const O_BACKUP = 'backup';
    public const O_USER_LOG = 'users_log';
    public const O_USER = 'user';
    public const O_PERMISSION = 'permission';
    public const O_SETTING = 'setting';
    public const O_TOKEN = 'token';

    // -- Action
    public const A_CREATE = 'create';
    public const A_CHANGE = 'change';
    public const A_VIEW = 'view';
    public const A_DELETE = 'delete';

    /**
     * @return void
     */
    public static function install(): void
    {
        DBWrap::getDB()::table('rules')->delete();
        $roles = self::getRoles();
        Enforcer::addRoleForUser(user: 1, role: self::R_ADMIN);

        foreach ($roles as $role) {
            $policies = self::getPolicies($role);
            foreach ($policies as $object => $actions) {
                foreach ($actions as $action) {
                    Enforcer::addPolicy($role, $object, $action);
                }
            }
        }
    }

    /**
     * @return string[]
     */
    public static function getRoles(): array
    {
        return [
            self::R_ADMIN => self::R_ADMIN,
            self::R_MANAGER => self::R_MANAGER,
            self::R_DEVELOPER => self::R_DEVELOPER,
            self::R_DEVOPS => self::R_DEVOPS,
        ];
    }

    /**
     * @param  string $role
     * @return string[][]
     */
    public static function getPolicies(string $role): array
    {
        return match ($role) {
            self::R_ADMIN => self::getAdminPolicy(),
            self::R_MANAGER => self::getManagerPolicy(),
            self::R_DEVELOPER => self::getDeveloperPolicy(),
            self::R_DEVOPS => self::getDevOpsPolicy(),
        };
    }

    /**
     * @return string[][]
     */
    public static function getAdminPolicy(): array
    {
        // приложения, дашборд, клиенты, нотификейшены
        return [
            self::O_APPLICATION => [self::A_VIEW, self::A_CREATE, self::A_CHANGE, self::A_DELETE],
            self::O_DASHBOARD => [self::A_VIEW, self::A_CHANGE],
            self::O_CLIENT => [self::A_VIEW, self::A_CREATE,self::A_CHANGE, self::A_DELETE],
            self::O_NOTIFICATION => [self::A_CHANGE, self::A_VIEW, self::A_CREATE, self::A_DELETE],
            self::O_PERMISSION => [self::A_VIEW, self::A_CREATE,self::A_CHANGE,  self::A_DELETE],
            self::O_BACKUP => [ self::A_VIEW, self::A_CREATE, self::A_CHANGE,self::A_DELETE],
            self::O_USER_LOG => [ self::A_VIEW, self::A_CREATE, self::A_CHANGE,self::A_DELETE],
            self::O_LOG => [self::A_VIEW, self::A_CREATE,self::A_CHANGE,  self::A_DELETE],
            self::O_USER => [ self::A_VIEW, self::A_CREATE, self::A_CHANGE,self::A_DELETE],
            self::O_SETTING => [ self::A_VIEW, self::A_CREATE,self::A_CHANGE, self::A_DELETE],
            self::O_TOKEN => [self::A_VIEW, self::A_CREATE, self::A_CHANGE, self::A_DELETE],
        ];
    }

    /**
     * @return string[][]
     */
    public static function getManagerPolicy(): array
    {
        // приложения, дашборд, клиенты, нотификейшены
        return [
            self::O_APPLICATION => [ self::A_VIEW, self::A_CREATE,self::A_CHANGE, self::A_DELETE],
            self::O_DASHBOARD => [self::A_VIEW,self::A_CHANGE],
            self::O_CLIENT => [self::A_VIEW, self::A_CHANGE],
            self::O_NOTIFICATION => [self::A_VIEW,self::A_CHANGE],
        ];
    }

    /**
     * @return string[][]
     */
    public static function getDeveloperPolicy(): array
    {
        //  приложения (просмотр/редактирование, но не создание и не удаление), дашборд, клиенты, сеттинги
        return [
            self::O_APPLICATION => [self::A_VIEW,self::A_CHANGE],
            self::O_DASHBOARD => [self::A_VIEW,self::A_CHANGE ],
            self::O_CLIENT => [self::A_VIEW,self::A_CHANGE, ],
            self::O_NOTIFICATION => [ self::A_VIEW,self::A_CHANGE,],
            self::O_SETTING => [ self::A_VIEW,self::A_CHANGE],
        ];
    }

    /**
     * @return string[][]
     */
    public static function getDevOpsPolicy(): array
    {
        // бакапы, логи юзеров, логи клиентов, юзеры (создание/удаление/просмотр), сеттинги, нотификейшены, приложения на просмотр
        return [
            self::O_APPLICATION => [self::A_VIEW],
            self::O_BACKUP => [self::A_VIEW, self::A_CREATE,self::A_CHANGE, self::A_DELETE],
            self::O_USER_LOG => [self::A_VIEW, self::A_CREATE,self::A_CHANGE, self::A_DELETE],
            self::O_LOG => [self::A_VIEW, self::A_CREATE,self::A_CHANGE, self::A_DELETE],
            self::O_USER => [self::A_VIEW, self::A_CREATE,self::A_CHANGE, self::A_DELETE],
            self::O_NOTIFICATION => [self::A_VIEW,self::A_CHANGE],
            self::O_SETTING => [self::A_VIEW,self::A_CHANGE],
            self::O_TOKEN => [self::A_VIEW, self::A_CREATE, self::A_CHANGE, self::A_DELETE],
        ];
    }

    /**
     * @return array
     */
    public static function getUserPolicy(): array
    {
        return [];
    }

    /**
     * @param  ?int   $userId
     * @param  string $permission
     * @param  string $action
     * @return bool
     */
    public static function allowed(?int $userId, string $permission, string $action): bool
    {
        if ($userId === null) {
            return false;
        }
        return Enforcer::enforce((string)$userId, $permission, $action);
    }

}
