<?php
declare(strict_types=1);

namespace App\Repositories\Franchise\ShowRooms;

use App\Http\Resources\MediaResource;
use App\Repositories\CoreRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use \Exception;
use Spatie\MediaLibrary\Models\Media;

use App\Models\Franchise\ShowRooms\Room as Model;

class RoomRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModelClass(): string
    {
        return Model::class;
    }

    public function saveVideo($data, $id): bool
    {
        $result = true;
        $model = $this->startConditions()->find($id);

        if ($model instanceof Model) {
            try {
                DB::beginTransaction();

                switch ($data['provider']) {
                    case 'youtube':
                        $model->update([
                            'video_youtube' => '' == $data['url'] ? '' : $this->getYoutubeIdFromUrl($data['url'])
                        ]);
                        break;
                    case 'vimeo':
                        $model->upadte([
                            'video_vimeo' => '' == $data['url'] ? '' : $this->getVimeoIdFromUrl($data['url'])
                        ]);
                        break;
                    default:
                        $result = false;
                        break;
                }

                DB::commit();
            }
            catch(\Exception $exception) {
                DB::rollBack();
                dd($exception);
            }
        }
        else {
            $result = false;
        }

        return $result;
    }

    /**
     * @param $data
     * @return Model|null
     */
    public function createRoom($data):? Model
    {
        $model = null;

        try {
            DB::beginTransaction();

            $model = $this->startConditions()->create([
                'group_id' => $data['group_id'],
                'cities_id' => $data['cities_id'],
                'phone' => $data['phone'] ?? '',
                'email' => $data['email'] ?? '',
            ]);

            if ($model instanceof Model) {
                $model->i18n()->create([
                    'name' => $data['name'],
                    'address' => $data['address'],
                    'work_time' => $data['work_time'],
                ]);

                DB::commit();
            }
            else {
                DB::rollBack();
            }
        }
        catch(Exception $exception) {
            DB::rollBack();
            dd($exception);
        }

        return $model;
    }

    /**
     * @param $data
     * @param $id
     * @return bool
     */
    public function updateShowRoom($data, $id): bool
    {
        $result = true;

        $model = $this->startConditions()
            ->with(['i18n'])
            ->find($id);

        if ($model instanceof Model) {
            try {
                DB::beginTransaction();

                $model->update([
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'status' => $data['status'],
                    'lat' => $data['lat'],
                    'lon' => $data['lon'],
                ]);

                $model->i18n()->update([
                    'name' => $data['name'],
                    'address' => $data['address'],
                    'work_time' => $data['work_time']
                ]);

                $this->eraseMedia($model, $data);
                $this->updateMedia($model, $data);

                DB::commit();
            }
            catch (\Exception $exception) {
                $result = false;
                DB::rollBack();
                dd($exception);
            }
        }
        else {
            $result = false;
        }

        return $result;
    }

    /**
     * @return Collection
     */
    public function getListRooms($group_id): Collection
    {
        return $this->startConditions()
            ->with(['i18n', 'cities.i18n'])
            ->where('group_id', '=', $group_id)
            ->get();
    }

    public function getEditRoom($id):? Model
    {
        return $this->startConditions()->with(['i18n'])->find($id);
    }

    /**
     * @param $input
     * @param $id
     * @return Media
     */
    public function loadImageSave($input, $id): Media
    {
        try {
            $model = $this->startConditions()->find($id);

            return $model->addMedia($input['file'])
                ->toMediaCollection(
                    $model->getCollection(),
                    $model->getMediaDisk()
                );
        }
        catch (\Exception $exception) {
            dd($exception);
        }
    }

    /**
     * @param $model
     * @param $data
     */
    private function eraseMedia($model, $data)
    {
        $arrOld = [];
        $arrNew = [];

        $imagesOld =  $model->getMedia($model->getCollection());
        $imagesNew = $data['images'];

        foreach($imagesOld as $image) $arrOld[] = $image->id;
        foreach($imagesNew as $image) $arrNew[] = $image['id'];

        $arrDiff = array_diff($arrOld, $arrNew);

        foreach($imagesOld as $image) {
            if (in_array($image->id, $arrDiff)) {
                $image->delete();
            }
        }
    }

    /**
     * @param $model
     * @param $data
     */
    private function updateMedia($model, $data)
    {
        $imagesOld =  $model->getMedia($model->getCollection());

        foreach($data['images'] as $image) {
            if(isset($image['cropped'])) {
                $model->addMediaFromBase64($image['cropped'])
                    ->toMediaCollection(
                        $model->getCollection(),
                        $model->getMediaDisk()
                );

                foreach($imagesOld as $old) {
                    if($image['id'] === $old->id) {
                        $old->delete();
                    }
                }
            }
        }
    }

    /**
     * @param $url
     * @return mixed
     */
    private function getYoutubeIdFromUrl($url): string
    {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);

        return $match[1];
    }

    /**
     * @param $url
     * @return string
     */
    private function getVimeoIdFromUrl($url): string
    {
        preg_match('%(http|https)?://(www\.|player\.)?vimeo\.com/(?:channels/(?:\w+/)?|groups/([^/]*)/videos/|video/|)(\d+)(?:|/\?)%i', $url, $match);

        return $match[4];
    }
}
