<?php

namespace Demir\Restwell;

use Exception;
use Config;

/**
 * Class BaseService for encapsulating basic methods for all services.
 *
 * @package Demir\Restwell
 */
class BaseService
{
    /**
     * Default model instance for service.
     *
     * @var Demir\Restwell\BaseModel
     */
    protected $model;

    /**
     * Constructor for BaseService class.
     *
     * @param string
     */
    public function __construct($modelName = '')
    {
        if (empty($modelName)) {
            $classFullPath = explode('\\', get_called_class());
            $lastElement   = count($classFullPath) - 1;

            $classFullPath[$lastElement-1] = str_replace('Services', 'Models', $classFullPath[$lastElement-1]);
            $classFullPath[$lastElement]   = str_replace('Service', '', $classFullPath[$lastElement]);

            $modelPath = implode('\\', $classFullPath);
        } else {
            $modelPath = $modelName;
        }

        $this->setModel($modelPath);
    }

    /**
     * Setter for default model.
     *
     * @throws Exception
     */
    public function setModel($modelPath)
    {
        if (class_exists($modelPath)) {
            $this->model = new $modelPath;
        } else {
            throw new Exception($modelPath . ' model can not be found!');
        }
    }

    /**
     * Getter for default model.
     *
     * @return Demir\Restwell\BaseModel
     */
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
     * @return Demir\Restwell\BaseModel
     */
    public function find($id, array $columns = array('*'))
    {
        if (empty($id)) {
            return $this->model;
        } else {
            return $this->model->find($id, $columns);
        }
    }

    /**
     * Return related models via field.
     *
     * @param  $field
     * @param  $value
     * @param  array  $columns
     * @return mixed
     */
    public function findByField($field, $value, $columns = array('*'))
    {
        return $this->model->where($field, '=', $value)->get($columns);
    }

    /**
     * Save related model with passed form data.
     *
     * @param int   $id
     * @param array $formData
     */
    public function save($id, array $formData)
    {
        if (!empty($id)) {
            $model = $this->find($id);
        } else {
            $model = $this->model;
        }

        $model->fill($formData)->save();
    }

    /**
     * Delete model with specified id.
     *
     * @param $id
     */
    public function delete($id)
    {
        $this->model->find($id)->delete();
    }
}
