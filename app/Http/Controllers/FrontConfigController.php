<?php

namespace App\Http\Controllers;

class FrontConfigController extends Controller
{
    public static function getConfig()
    {
        $config = [
            'user' => self::getUserConifg(),
            'interactionKey' => DataHandler::getRelevantKey(),
            'shop' => config('shop'),
        ];

        static::__connectTranslates($config);

        return json_encode($config, JSON_UNESCAPED_UNICODE);
    }

    protected static function __connectTranslates(& $config)
    {
        $config['translates'] = trans('js');
    }

    protected static function getUserConifg()
    {
        $user = \Auth::guard('admin.web')->user();
        $permissions = $user->getPermissions();
        sort($permissions);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'token' => $user->api_token,
            'isSuper' => $user->isSuperAdmin(),
            'permissions' => $permissions
        ];
    }
}
