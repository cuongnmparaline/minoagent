<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create($attributes = [])
    {
        try {
            $attributes['ins_datetime'] = date('Y-m-d H:i:s');
            $attributes['ins_id'] = !empty($attributes['ins_id']) ? $attributes['ins_id'] : Auth::guard('admin')->id();
            foreach ($attributes as $key => $value) {
                if ($key != 'id' && in_array($key, $this->getFillable(), true)) {
                    $this->model->$key = $value;
                }
            }
            $this->model->save();
        } catch (\Exception $e){
            Log::error('Create Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
        }

        return $this->model;
    }

    public function update($id, $attributes = [])
    {
        $result = $this->find($id);
        if ($result) {
            try {
                $attributes['upd_datetime'] = date('Y-m-d H:i:s');
                $attributes['upd_id'] = !empty($attributes['ins_id']) ? $attributes['ins_id'] : Auth::guard('admin')->id();
                foreach ($attributes as $key => $value) {
                    if ($key != 'id' && in_array($key, $this->getFillable(), true)) {
                        $result->$key = $value;
                    }
                }
                $result->save();
            } catch (\Exception $e){
                Log::error('Update Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            }
            return $result;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        return $this->update($id, ['del_flag' => config('const.deleted')]);
    }

    public function getFillable()
    {
        return $this->model->getFillable();
    }

}
