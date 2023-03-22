<?php
namespace App\Models\Franchise\ShowRooms;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

use App\Models\Franchise\Territory\Cities;

class Room extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'franchise_showroom_room';

    protected $fillable = [
        'id', 'group_id', 'cities_id', 'phone', 'email', 'lat', 'lon', 'video_youtube', 'video_vimeo', 'status'
    ];

    protected $mediaCollectionName = 'contact';
    protected $mediaDisk = 's3';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function group()
    {
        return $this->hasOne(Group::class, 'id', 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cities()
    {
        return $this->hasOne(Cities::class, 'id', 'cities_id');
    }

    public function i18n()
    {
        return $this->hasMany(RoomI18n::class, 'room_id', 'id');
    }

//    public function images()
//    {
//        return $this->getMedia();
//    }

//    public function images()
//    {
//        return $this->morphMany(config('medialibrary.media_model'), 'model')
//            ->where('collection_name', $this->mediaCollectionName)
//            ->orderBy('order_column', 'asc');
//    }

    public function getCollection()
    {
        return $this->mediaCollectionName;
    }

    public function getMediaDisk()
    {
        return $this->mediaDisk;
    }
}
