<?php namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Contents\Content;
use App\Models\Shop\Contents\ContentHumanName;

class ContentsController extends Controller
{

    protected $humanNames;


    public function index()
    {
        $this->humanNames = ContentHumanName::get();

        return Content::get()
          ->map(function($content) {
              $content->scopeHumanName = $this->getScopeHumanName($content->scope);
              $content->blockHumanName = $this->getBlockHumanName($content->block);
              return $content;
          });
    }


    public function getScopeHumanName($scope)
    {
        $model = collect($this->humanNames)
          ->where('type', 'scope')
          ->where('name', $scope)->first();

        return $model ? $model->human_name : $scope;
    }


    public function getBlockHumanName($block)
    {
        $model = collect($this->humanNames)
          ->where('type', 'block')
          ->filter(function ($el) use ($block) {
              if (count(explode( '.', $el->name)) === 2) {
                  return explode( '.', $el->name)[1] === $block;
              }
          })
          ->first();

        return $model ? $model->human_name : $block;
    }


    public function single($id)
    {
        return Content::where('id', $id)->first();
    }


    public function update($id)
    {
        Content::where('id', $id)->update([
          'content' => request()->input('content'),
        ]);

        return $this->single($id);
    }

}