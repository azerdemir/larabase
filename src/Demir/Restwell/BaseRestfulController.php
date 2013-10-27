<?php

namespace Demir\Restwell;

use Config;
use Input;
use Redirect;
use URL;
use View;

/**
 * Class BaseController with RESTful features.
 *
 * @package Demir\Restwell
 */
class BaseRestfulController extends BaseAuthController
{
    /**
     * Service object.
     *
     * @var Demir\Restwell\BaseService
     */
    protected $service;

    /**
     * Route prefix for routes.
     *
     * @var string
     */
    protected $routePrefix;

    /**
     * Directory of related view.
     *
     * @var string
     */
    protected $viewDirectory;

    /**
     * Form element array for related model.
     *
     * @var string
     */
    protected $viewFormElement;

    /**
     * Data passed to views.
     *
     * @var string
     */
    protected $viewData;

    /**
     * Collection variable name for index views.
     *
     * @var string
     */
    protected $collectionKey;

    /**
     * Entity variable name for edit/show views.
     *
     * @var string
     */
    protected $entityKey;

    /**
     * Flag indicating whether paging is enabled or not.
     *
     * @var boolean
     */
    protected $pagingEnabled = true;

    /**
     * Constructor for BaseRestfulController.
     */
    public function __construct()
    {
        $this->beforeFilter('auth');

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected function index()
    {
        if ($this->pagingEnabled) {
            $page = (int) Input::get('page') == 0 ? 1 : (int) Input::get('page');
            $viewData = array(
                'page'      => $page,
                'pageCount' => ceil($this->service->count() / Config::get('app.pagelimit'))
            );
        }
        else {
            $page     = 0;
            $viewData = array();
        }

        $viewData[$this->collectionKey] = $this->service->all($page);

        if (is_array($this->viewData)) {
            $viewData = array_merge($viewData, $this->viewData);
        }

        $contentView = View::make($this->viewDirectory . '.index', $viewData);

        if (isset($this->layout)) {
            $this->layout->content = $contentView;
        }
        else {
            return $contentView;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    protected function create()
    {
        $viewData = array(
            $this->entityKey => $this->service->find(0),
            'method' => 'POST',
            'action' => URL::route($this->routePrefix . '.store')
        );

        if (is_array($this->viewData)) {
            $viewData = array_merge($viewData, $this->viewData);
        }

        $contentView = View::make($this->viewDirectory . '.edit', $viewData);

        if (isset($this->layout)) {
            $this->layout->content = $contentView;
        }
        else {
            return $contentView;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    protected function store()
    {
        $this->service->save(0, Input::get($this->viewFormElement));

        return Redirect::route($this->routePrefix . '.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    protected function show($id)
    {
        $viewData = array(
            $this->entityKey => $this->service->find($id)
        );

        if (is_array($this->viewData)) {
            $viewData = array_merge($viewData, $this->viewData);
        }

        $contentView = View::make($this->viewDirectory . '.show', $viewData);

        if (isset($this->layout)) {
            $this->layout->content = $contentView;
        }
        else {
            return $contentView;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    protected function edit($id)
    {
        $viewData = array(
            $this->entityKey => $this->service->find($id),
            'method' => 'PUT',
            'action' => URL::route($this->routePrefix . '.update', $id)
        );

        if (is_array($this->viewData)) {
            $viewData = array_merge($viewData, $this->viewData);
        }

        $contentView = View::make($this->viewDirectory . '.edit', $viewData);

        if (isset($this->layout)) {
            $this->layout->content = $contentView;
        }
        else {
            return $contentView;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    protected function update($id)
    {
        $this->service->save($id, Input::get($this->viewFormElement));

        return Redirect::route($this->routePrefix . '.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    protected function destroy($id)
    {
        $this->service->delete($id);

        return Redirect::route($this->routePrefix . '.index');
    }
}
