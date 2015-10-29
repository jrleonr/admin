<?php

namespace Pingpong\Admin\Repositories\Images;

use Pingpong\Admin\Entities\Image;

class EloquentImageRepository implements ImageRepository
{
    public function perPage()
    {
        return config('admin.image.perpage');
    }

    public function getModel()
    {
        $model = config('admin.image.model');

        return new $model();
    }

    public function getImage()
    {
        return $this->getModel()->onlyPost();
    }

    public function allOrSearch($searchQuery = null)
    {
        if (is_null($searchQuery)) {
            return $this->getAll();
        }

        return $this->search($searchQuery);
    }

    public function getAll()
    {
        return $this->getImage()->latest()->paginate($this->perPage());
    }

    public function search($searchQuery)
    {
        $search = "%{$searchQuery}%";

        return $this->getImage()->where('title', 'like', $search)
            ->orWhere('body', 'like', $search)
            ->orWhere('id', '=', $searchQuery)
            ->paginate($this->perPage())
        ;
    }

    public function findById($id)
    {
        return $this->getImage()->find($id);
    }

    public function findBy($key, $value, $operator = '=')
    {
        return $this->getImage()->where($key, $operator, $value)->paginate($this->perPage());
    }

    public function delete($id)
    {
        $Image = $this->findById($id);

        if (!is_null($Image)) {
            $Image->delete();

            return true;
        }

        return false;
    }

    public function create(array $data)
    {
        return $this->getModel()->create($data);
    }
}
