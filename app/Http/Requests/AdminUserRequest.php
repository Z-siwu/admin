<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;

class AdminUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->route('admin_user');
        $id = (int) optional($user)->id;
        $rules = [
            'username' => 'required|max:100|unique:admin_users,username,'.$id,
            'name' => 'required|max:100',
            'password' => 'required|between:6,20|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:admin_roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:admin_permissions,id',
        ];
        if ($this->isMethod('put')) {
            $rules = Arr::only($rules, $this->keys());
            // 如果更新时, 没填密码, 则不用验证
            if (!$this->post('password')) {
                unset($rules['password']);
            }
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'username' => '账号',
            'name' => '姓名',
            'password' => '密码',
            'roles' => '角色',
            'roles.*' => '角色',
            'permissions' => '权限',
            'permissions.*' => '权限',
        ];
    }
}
