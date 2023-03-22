<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Database\Eloquent\Model;
use \App\Contracts\Models\HasPermissions;

abstract class Request extends FormRequest
{
    /**
     * Guard он и есть guard.
     *
     * @var array
     */
    protected $guard;

    /**
     * Класс модели запроса.
     *
     * @var string
     */
    protected $model;

    /**
     * Id текущей сущности (если есть).
     *
     * @var string
     */
    protected $id;

    /**
     * Экземпляр сущности текущего запроса.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $entity;

    /**
     * Пользователь, выполняющий запрос.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $requestUser;

    /**
     * Пути, для которых не нужна валидация.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $withoutRulesPathes = [];


    /**
     * Namespace прав запроса.
     *
     * @var string
     */
    protected $requestNamespace;

    /**
     * @return void
     * @author
     **/
    public function __construct()
    {
        // todo: разобраться с guard
        $this->guard = config('admin.api');
    }

    /**
     * Проверка прав пользователя на совершение текущего действия.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->authorizeAction($this->getAction());
    }

    /**
     * Получение текущего действия.
     *
     * @return string
     */
    protected function getAction(): string
    {
        $pathArray = $this->getPathArray();

        $lastSlug = $pathArray[count($pathArray) - 1];

        try {
            $id = $this->getId();
        }
        catch(\Exception $e) {
            $id = 0;
        }

        if ($lastSlug != $id) {
            return $lastSlug;
        }

        if ($this->isCreate()) {
            return 'create';
        }

        if ($this->isStore()) {
            return 'store';
        }

        if ($this->isEdit()) {
            return 'edit';
        }

        if ($this->isUpdate()) {
            return 'update';
        }

        if ($this->isDelete()) {
            return 'delete';
        }

        return 'edit';
    }

    /**
     * Проверка прав пользователя на действие.
     *
     * @param string $action
     * @return bool
     */
    protected function authorizeAction(string $action): bool
    {
        $methodName = '_authorize' . ucfirst($action);

        if (method_exists($this, $methodName)) {
            return call_user_func([$this, $methodName]);
        }

        return $this->can($action);
    }

    /**
     * Возвращает полное название права.
     *
     * @param $action
     * @return string
     */
    protected function getPermissionFullName($action): string
    {
        return $this->permissionsNamespace . '.' . $action;
    }

    /**
     * Получение пользователя из запроса.
     *
     * @return \App\Contracts\Models\HasPermissions
     */
    protected function getRequestUser(): HasPermissions
    {
        if (is_null($this->requestUser)) {
            $this->requestUser = $this->user($this->guard);
        }

        return $this->requestUser;
    }

    /**
     * Check the process is approve.
     *
     * @return bool
     **/
    protected function can($action): bool
    {
        $user = $this->getRequestUser();

        return $user->hasPermission($this->getPermissionFullName($action)) || $user->can($action, $this->model);
    }

    /**
     * @return bool
     **/
    protected function isWorkflow(): bool
    {
        if ($this->isMethod('PATCH') && $this->has('status')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isCreate(): bool
    {
        if ($this->is('*/create')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isStore(): bool
    {
        if ($this->isMethod('POST')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isEdit(): bool
    {
        if (
            $this->is('*/edit')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isUpdate(): bool
    {
        if ($this->isMethod('PUT') ||
            $this->isMethod('PATCH')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isDelete(): bool
    {
        if ($this->isMethod('DELETE')) {
            return true;
        }

        return false;
    }

    /**
     * Нужны ли правила для валидации модели.
     *
     * @return bool
     */
    protected function needsEntityRules(): bool
    {
        $action = $this->getAction();

        if (! in_array($action, ['store', 'update'])) {
            return false;
        }

        foreach ($this->withoutRulesPathes as $path) {
            if ($this->is($path)) {
                return false;
            }
        }

        return true;
    }


    /**
     * Текущий паттерн роута в виде массива.
     *
     * @return array
     */
    protected function getRoutePatternArray()
    {
        return explode('/', trim(\Route::getCurrentRoute()->uri, '/'));
    }

    /**
     * Текущий путь в виде массива.
     *
     * @return array
     */
    protected function getPathArray(): array
    {
        return explode('/', trim($this->getPathinfo(), '/'));
    }

    /**
     * Возвращает отличия тукущего пути от паттерна роутов.
     *
     * @return array
     */
    protected function getRouteDiff(): array
    {
        return array_diff_assoc($this->getPathArray(), $this->getRoutePatternArray());
    }

    /**
     * Получение id текущей сущности.
     *
     * @return mixed
     * @throws \Exception
     */
    protected function getId(): int
    {
        if (is_null($this->id)) {
            $diff = $this->getRouteDiff();

            if (count($diff) !== 1) {
                throw new \Exception('Id не найден.');
            }

            $this->id = (int) array_pop($diff);
        }

        return $this->id;
    }

    /**
     * Получение текущей сущности.
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    protected function getEntity(): Model
    {
        if (is_null($this->entity)) {
            $this->entity = $this->model::findOrFail($this->getId());
        }

        return $this->entity;
    }

    public function rules()
    {
        if ($this->needsEntityRules()) {
            return $this->getEntityRules();
        }

        return [];
    }

    protected function getEntityRules()
    {
        return [];
    }
}