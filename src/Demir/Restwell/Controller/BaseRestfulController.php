<?php

namespace Demir\Restwell\Controller;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Demir\Restwell\Repository\RepositoryInterface;

/**
 * Class BaseController with RESTful features.
 *
 * @package Demir\Restwell
 */
class BaseRestfulController extends BaseAuthController
{
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
     * Data passed to views.
     *
     * @var string
     */
    protected $viewData = [];

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
    public function __construct(RepositoryInterface $repository)
    {
        $this->beforeFilter('auth');

        parent::__construct($repository);
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
                'pageCount' => ceil($this->repository->count() / Config::get('restwell::pagelimit'))
            );
        }
        else {
            $page     = 0;
            $viewData = array();
        }

        $viewData[$this->collectionKey] = $this->repository->all($page);

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
        $viewData = [$this->entityKey => $this->repository->find(0)];

        $contentView = View::make(
            $this->viewDirectory . '.create',
            array_merge($viewData, $this->viewData)
        );

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
        $result = $this->repository->save(0, Input::all());

        if (!$result) {
            return Redirect::route($this->routePrefix . '.create')
                           ->withErrors($this->repository->getModel()->errors())
                           ->withInput();
        }
        else {
            return Redirect::route($this->routePrefix . '.index')->with('message', 'Item inserted.');
        }
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
            $this->entityKey => $this->repository->find($id)
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
        $viewData = [$this->entityKey => $this->repository->find($id)];

        $contentView = View::make(
            $this->viewDirectory . '.edit',
            array_merge($viewData, $this->viewData)
        );

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
        $result = $this->repository->save($id, Input::all());

        if (!$result) {
            return Redirect::route($this->routePrefix . '.edit', $id)
                           ->withErrors($this->repository->getModel()->errors())
                           ->withInput();
        }
        else {
            return Redirect::route($this->routePrefix . '.index')->with('message', 'Item updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    protected function destroy($id)
    {
        $this->repository->delete($id);

        return Redirect::route($this->routePrefix . '.index')->with('message', 'Item deleted.');
    }
}
