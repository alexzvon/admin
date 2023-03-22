<?php namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop\ExpandBanner\ExpandBanner;
use App\Models\Shop\ExpandBanner\ExpandBannerI18n;
use Illuminate\Support\Facades\Storage;

class ExpandBannersController extends Controller
{


    public function index($id = false)
    {
        return $id
          ? ExpandBanner::where('id', $id)->first()
          : ExpandBanner::get();
    }


    public function create()
    {
        $expandBanner = ExpandBanner::create([
          'name' => request()->get('name'),
        ]);

        $i18n = ExpandBannerI18n::create([
          'expand_banner_id' => $expandBanner->id,
        ]);

        return ExpandBanner::where('id', $expandBanner->id)
          ->first();
    }


    public function update($id)
    {
        $expandBanner = $this->index($id);

        if (request()->input('enabled')) {
            ExpandBanner::where('enabled', true)->update([
                'enabled' => false
            ]);
        }

        $expandBanner->update([
          'delay_seconds' => request()->input('delay_seconds'),
          'enabled' => request()->input('enabled'),
          'image' => request()->input('image'),
          'name' => request()->input('name'),
        ]);

        ExpandBannerI18n::where('id', request()->input('i18n.id'))->update([
              'bottom_small_text' => request()->input('i18n.bottom_small_text'),
              'button_text' => request()->input('i18n.button_text'),
              'input_placeholder' => request()->input('i18n.input_placeholder'),
              'offer' => request()->input('i18n.offer'),
              'title' => request()->input('i18n.title')
            ]);

        return $this->index($id);
    }


    public function delete($id)
    {
        ExpandBanner::destroy($id);
        return $this->index();
    }


    public function upload($id, Request $request)
    {
        $expandBanner = ExpandBanner::where('id', $id)->first();

        return $expandBanner->uploadImage($request->file('file'));
    }


}