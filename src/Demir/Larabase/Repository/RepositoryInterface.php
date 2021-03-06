<?php

namespace Demir\Larabase\Repository;

interface RepositoryInterface
{
    public function count();

    public function all(array $columns = array('*'));

    public function paginate(array $columns = array('*'));

    public function find($id, array $columns = array('*'));

    public function findByField($field, $value, $columns = array('*'));

    public function save($id, array $formData);

    public function delete($id);
}
