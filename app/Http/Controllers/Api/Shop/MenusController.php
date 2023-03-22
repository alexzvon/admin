<?php namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Menus\Menu;
use Illuminate\Http\Request;

class MenusController extends Controller
{

    public function index()
    {
        return Menu::get();
    }


    public function create()
    {
        Menu::create([
          'name' => request()->input('name'),
          'url' => request()->input('url'),
          'parent_menu_id' => request()->input('parent_menu_id'),
        ]);

        return Menu::get();
    }


    public function update($id)
    {
        Menu::where('id', $id)->update([
          'enabled' => request()->input('enabled'),
          'name' => request()->input('name'),
          'parent_menu_id' => request()->input('parent_menu_id'),
          'position' => request()->input('position'),
          'url' => request()->input('url'),
        ]);

        return $this->index();
    }


    public function delete($id)
    {
        Menu::destroy($id);
        return $this->index();
    }


    public function sorting(Request $request)
    {
        $links = Menu::get();

        foreach($request->all() as $link)
        {
            $model = $links->where('id', $link['id'])->first();
            if ($model->position !== $link['position']) {
                $model->update(['position' => $link['position']]);
            }
        }
    }



}