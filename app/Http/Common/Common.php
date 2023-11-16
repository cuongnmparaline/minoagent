<?php
use App\Models\Customer;
use App\Models\Admin;

if (!function_exists('oldData')) {
    function oldData($data, $field, $default= "")
    {
        return old($field, isset($data[$field]) ? $data[$field] : $default);
    }
}

if (!function_exists('getDataCreateForm')) {
    function getDataCreateForm($sessionName, $textBoxName)
    {
        return session()->has($sessionName) ? data_get(session($sessionName), $textBoxName) : old($textBoxName);
    }
}


if (!function_exists('getDataEditForm')) {
    function getDataEditForm($data, $sessionName, $field)
    {
        return session()->has($sessionName) ? data_get(session($sessionName), $field) : oldData($data, $field);
    }
}

if (!function_exists('validateImg')) {
    function validateImg($fileName)
    {
        session()->put('tmp_url', request()->file($fileName)->getClientOriginalName());
        $name = request()->file('avatar')->getClientOriginalName();
        Storage::putFileAs(config('const.TEMP_DIR'), request()->file($fileName), $name);
        return ['tmp_src_avatar' => "storage/tmp/$name", 'avatar' => $name];
    }
}

if (!function_exists('getCustomerName')) {
    function getCustomerName($leader_id)
    {
        $customer = Customer::find($leader_id);
        if(!empty($customer)){
            return $customer->name;
        }
        return "";
    }
}

if (!function_exists('getAdminName')) {
    function getAdminName($id)
    {
        $admin = Admin::find($id);
        if(!empty($admin)){
            return $admin->name;
        }
        return "";
    }
}

if (!function_exists('getDepartmentName')) {
    function getDepartmentName($department)
    {
        foreach(config('const.department') as $key => $value){
            if($key == $department){
                return $value;
            }
        }
        return "";
    }
}

if (!function_exists('getPositionName')) {
    function getPositionName($position)
    {
        foreach(config('const.position') as $key => $value){
            if($key == $position){
                return $value;
            }
        }
        return "";
    }
}

if (!function_exists('getRoleName')) {
    function getRoleName($role_type)
    {
        foreach(config('const.role_type') as $key => $value){
            if($key == $role_type){
                return $value;
            }
        }
        return "";
    }
}

if (!function_exists('convertToSlug')) {
    function convertToSlug($string) {
        $string = strtolower($string);
        $string = str_replace(' ', '-', $string);
        $string = preg_replace('/[^a-z0-9-]/', '', $string);
        $string = preg_replace('/-+/', '-', $string);

        return $string;
    }
}

