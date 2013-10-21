<?php

namespace Demir\Restwell;

use Exception;
use Config;

/**
 * Class BaseService for encapsulating basic methods for all services.
 *
 * @package Ecomercy\Services
 */
class BaseService
{
    /**
     * Default model instance for service.
     *
     * @var Ecomercy\Models\BaseModel
     */
    protected $model;

    /**
     * Default model name for service.
     *
     * @var string
     */
    protected $modelName;

    /**
     * Constructor for BaseService class.
     *
     * @param string
     */
    public function __construct($modelName = '')
    {
        if (empty($modelName)) {
            list($ns1, $ns2, $serviceClassName) = explode('\\', get_called_class());
            $position  = strpos($serviceClassName, 'Service');
            $this->modelName =  substr($serviceClassName, 0, $position);
        } else {
            $this->modelName = $modelName;
        }

        $this->setModel();
    }

    /**
     * Setter for default model.
     *
     * @throws Exception
     */
    public function setModel()
    {
        $className = 'Ecomercy\\Models\\' . $this->modelName;

        if (class_exists($className)) {
            $this->model = new $className;
        } else {
            throw new Exception($this->modelName . ' model can not be found!');
        }
    }

    /**
     * Getter for default model.
     *
     * @return Ecomercy\Models\BaseModel
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
            return $this->model->forPage($page, Config::get('app.pagelimit'))->get($columns);
        }
    }

    /**
     * Return related model instance.
     *
     * @param  int   $id
     * @param  array $columns
     * @return Ecomercy\Models\BaseModel
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
