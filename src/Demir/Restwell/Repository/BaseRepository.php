<?php

namespace Demir\Restwell\Repository;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Config;
use Demir\Restwell\Model\ModelInterface;

/**
 * Class BaseRepository for encapsulating basic methods for all repositories.
 *
 * @package Demir\Restwell\Repository
 */
abstract class BaseRepository implements RepositoryInterface
{
    /**
     * Default model instance for service.
     *
     * @var Demir\Restwell\Model\ModelInterface
     */
    protected $model;

    /**
     * Constructor for BaseRepository class.
     *
     * @param ModelInterface $model
     */
    public function __construct(ModelInterface $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * Returns total count of models.
     *
     * @return mixed
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Return a collection filled with instance from default model.
     *
     * @param  int   $page
     * @param  array $columns
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all($page = 0, array $columns = array('*'))
    {
        if (!$page) {
            return $this->model->get($columns);
        }
        else {
            return $this->model->forPage($page, Config::get('restwell::pagelimit'))->get($columns);
        }
    }

    /**
     * Return related model instance.
     *
     * @param  int   $id
     * @param  array $columns
     * @return Demir\Restwell\Model\ModelInterface
     */
    public function find($id, array $columns = array('*'))
    {
        return $this->model = empty($id) ? $this->model->newInstance() : $this->model->findOrFail($id, $columns);
    }

    /**
     * Return related models via field.
     *
     * @param  $field
     * @param  $value
     * @param  array $columns
     * @return mixed
     *
     * @throws ModelNotFoundException
     */
    public function findByField($field, $value, $columns = array('*'))
    {
        $model = $this->model->where($field, '=', $value)->get($columns);

        if (!is_null($model)) return $this->model = $model;

        throw new ModelNotFoundException;
    }

    /**
     * Save related model with passed form data.
     *
     * @param int   $id
     * @param array $formData
     */
    public function save($id, array $formData)
    {
        return $this->find($id)->fill($formData)->save();
    }

    /**
     * Delete model with specified id.
     *
     * @param $id
     */
    public function delete($id)
    {
        $this->find($id)->delete();
    }
}
