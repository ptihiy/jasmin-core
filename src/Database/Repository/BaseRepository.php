<?php

namespace Jasmin\Core\Database\Repository;

use Jasmin\Core\Routing\Redirect;
use Jasmin\Core\Database\ActiveRecord;
use Jasmin\Core\Database\ActiveRecordInterface;

class BaseRepository
{
    protected $model;

    public function all(): array
    {
        return $this->model::get();
    }

    public function findOrFail(mixed $expr): ActiveRecordInterface
    {
        $instance = $this->model->find($expr);

        if (is_null($instance)) {
            Redirect::redirect('404', 404);
        }

        return $instance;
    }
}
