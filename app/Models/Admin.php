<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use App\Support\Traits\Models\StatusChangeable;
use App\Support\Traits\Models\RequestSaver;
use App\Support\Traits\Models\CheckPermissionTrait;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\Models\Media;
use App\Support\Traits\Models\HasMediaTrait;
use App\Contracts\Models\HasPermissions;

use MosseboShopCore\Models\Base\Authenticatable;
use MosseboShopCore\Contracts\Models\HasMorphRelation as HasMorphRelationInterface;
use MosseboShopCore\Support\Traits\Models\HasMorphRelation;

class Admin extends Authenticatable implements HasMedia, HasPermissions, HasMorphRelationInterface
{
    use Notifiable, StatusChangeable, RequestSaver, HasMediaTrait, CheckPermissionTrait, HasMorphRelation;

    protected $tableKey = 'Admins';
    protected $relationFieldName = 'admin_id';

    protected $fillable = [
        'api_token',
        'email',
        'name',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $needsToSaveFromRequest = [
        'images',
        'new_password',
        'roles',
    ];

    protected $mediaCollectionName = 'image';

    protected $morphTypeName = 'admin';

    public function roles()
    {
        return $this->hasManyThrough(
            AdminRole::class,
            AdminRoleRelation::class,
            $this->relationFieldName,
            'id',
            'id',
            'admin_role_id'
        );
    }

    public function roleRelations()
    {
        return $this->hasMany(AdminRoleRelation::class, $this->relationFieldName);
    }

    public function adminLog()
    {
        return $this->hasMany(AdminLog::class, $this->relationFieldName);
    }

    /**
     * Задает преобразователи изображений.
     *
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('small')
            ->crop(Manipulations::CROP_CENTER, 400, 400)
            ->background('fff')
            ->withResponsiveImages()
            ->performOnCollections($this->mediaCollectionName);

        $this->addMediaConversion('thumb')
            ->crop(Manipulations::CROP_CENTER, 128, 128)
            ->background('fff')
            ->withResponsiveImages()
            ->nonQueued()
            ->performOnCollections($this->mediaCollectionName);

        $this->addMediaConversion('small')
            ->crop(Manipulations::CROP_CENTER, 400, 400)
            ->background('fff')
            ->withResponsiveImages()
            ->nonQueued()
            ->performOnCollections('temp');
    }

    public function avatar()
    {
        $image = $this->getMedia($this->mediaCollectionName)->first();

        if (! $image) {
            return '/img/placeholders/avatars/avatar.jpg';
        }

        $pathes = $image->getImagePathes();

        return $pathes['small']['srcset'];
    }

    public function _saveRoles(Array $roles = [])
    {
        $this->roleRelations()->delete();

        foreach ($roles as $roleId) {
            $this->roleRelations()->save(new AdminRoleRelation([
                'admin_id' => $this->id,
                'admin_role_id' => $roleId
            ]));
        }
    }

    public function _saveNewPassword($newPassword = null)
    {
        if (! is_null($newPassword)) {
            $this->setAttribute('password', encodePassword($newPassword));
        }
    }
}
