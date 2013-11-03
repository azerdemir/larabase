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
     * Default class path for model.
     *
     * @var string
     */
    protected $modelPath;

    /**
     * Constructor for BaseService class.
     *
     * @param string
     */
    public function __construct($modelName = '')
    {
        $this->setModelPath($modelName);
        $this->setModel();
    }

    /**
     * Set $modelPath class variable.
     *
     * @param string $modelName
     */
    public function setModelPath($modelName)
    {
        if (empty($modelName)) {
            $classFullPath = explode('\\', get_called_class());
            $lastElement   = count($classFullPath) - 1;

            $classFullPath[$lastElement-1] = str_replace('Services', 'Models', $classFullPath[$lastElement-1]);
            $classFullPath[$lastElement]   = str_replace('Service', '', $classFullPath[$lastElement]);

            $this->modelPath = implode('\\', $classFullPath);

        } else {
            $this->modelPath = $modelName;
        }
    }

    /**
     * Set new BaseModel instance for default model.
     *
     * @throws Exception
     */
    public function setModel($modelPath = '')
    {
        if (isset($modelPath) && class_exists($modelPath)) {
            $this->model = new $modelPath;

        } elseif (isset($this->modelPath) && class_exists($this->modelPath)) {
            $this->model = new $this->modelPath;

        } else {
            throw new Exception('Model can not be found!');
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
            // assigning an empty model to $model variable
            $this->setModel();

        } else {
            $this->model = $this->model->find($id, $columns);
        }

        return $this->model;
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
        $this->find($id);
        return $this->model->fill($formData)->save();
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

    public function errors()
    {
        $errors[] = array(
            'type'      => 'error',
            'placement' => 'title',
            'message'   => 'Validation failed!!'
        );

        $vAllErrors = $this->getModel()->errors()->getMessages();

        foreach ($vAllErrors as $vElmErrors) {
            foreach ($vElmErrors as $vError) {
                $errors[] = array(
                    'type'      => 'error',
                    'placement' => 'item',
                    'message'   => $vError
                );
            }
        }

        return $errors;
    }
}
