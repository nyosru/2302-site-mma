<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Lauthz\Facades\Enforcer;
use Throwable;

class UserService
{
    /**
     * Upload profile image
     *
     * @param  mixed $image
     * @return string
     */
    public function uploadProfileImage($image)
    {
        try {
            $image = Image::make($image);

            $filename = time() . '.' . explode('/', $image->mime)[1];

            $path = config('admin.storage.paths.profile_images');

            if (!Storage::exists($path)) {
                Storage::makeDirectory($path);
            }

            $image->fit(400, 400)->save(storage_path("app/$path/$filename"));
        } catch (Throwable $e) {
            return null;
        }
        return $filename;
    }

    /**
     * @param  int|null $userId
     * @param  string   $email
     * @param  string   $name
     * @param  string   $password
     * @param  string   $role
     * @return User
     */
    public function save(?int $userId, string $email, string $name, ?string $password, string $role): User
    {
        $user = User::whereId($userId)->first();

        if (null !== $user) {
            $user->name = $name;
            $user->email = $email;
            if ($password) {
                $user->password = Hash::make($password);
            }
            $user->save();
            $user = $user->refresh();
        } else {
            $user = User::create(
                [
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password)
                ]
            )->refresh();
        }
        Enforcer::addRoleForUser(user: $userId, role: $role);
        return $user;
    }

    /**
     * @throws \Exception
     */
    public function changeRole($visitorId, $userId, $newRole): void
    {
        if (!in_array($newRole, PermissionService::getRoles())) {
            throw new \Exception('Invalid role "' . $newRole . '"');
        }
        $roles = Enforcer::getRolesForUser((string)$userId);
        if ($roles) {
            foreach ($roles as $role) {
                Enforcer::deleteRoleForUser((string)$userId, $role);
            }
        }
        Enforcer::addRoleForUser(user: (string)$userId, role: $newRole);
    }
}
