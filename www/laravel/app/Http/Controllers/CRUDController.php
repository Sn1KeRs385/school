<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use Illuminate\Routing\Controller as BaseController;
use Illuminate\Pagination\LengthAwarePaginator;

class CRUDController extends Controller implements iCRUDController
{
    protected $rules = [
        'all' => [],
        'index' => [],
        'show' => [],
        'store' => [],
        'update' => [],
        'destroy' => [],
    ];

    protected $fieldNames = [];

    protected $validateMessages = [];

    public function hook_validate_all(Request &$request)
    {
        $request->validate($this->rules['all'], $this->validateMessages, $this->fieldNames);
    }

    public function hook_before_all(&$query)
    {

    }

    public function hook_after_all(&$objects)
    {

    }
    public function all(Request $request)
    {
        $this->hook_validate_all($request);
        $query = $this->modelClass::query();
        $this->hook_before_all($query);
        $objects = $query->get();
        $this->hook_after_all($object);
        return getJson($objects);
    }

    public function hook_validate_index(Request &$request)
    {
        $request->validate($this->rules['index'], $this->validateMessages, $this->fieldNames);

    }

    public function hook_before_index(&$query)
    {

    }

    public function hook_after_index(&$paginateCollection)
    {

    }

    public function index(Request $request)
    {
        if ($request->has('all')) {
            return $this->all($request);
        }

        $this->hook_validate_index($request);
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 25);
        $query = $this->modelClass::query();
        $this->hook_before_index($query);

        $objects = $query->paginate($per_page);

        $this->hook_after_index($objects);

        return getJsonPaginate($objects);
    }


    public function hook_validate_show(Request &$request)
    {
        $request->validate($this->rules['show'], $this->validateMessages, $this->fieldNames);
    }

    public function hook_before_show(&$query, $id)
    {

    }

    public function hook_after_show(&$model)
    {

    }

    public function show(Request $request, $id)
    {
        $this->hook_validate_show($request);

        $this->authorize('view', $this->modelClass::find($id));

        $query = $this->modelClass::whereId($id);

        $this->hook_before_show($query, $id);

        $object = $query->first();

        $this->hook_after_show($object);

        return getJson($object);
    }

    public function hook_validate_store(Request &$request)
    {
        $request->validate($this->rules['store'], $this->validateMessages, $this->fieldNames);
    }

    public function hook_before_store(Request &$request)
    {

    }

    public function hook_after_store(&$model)
    {

    }

    public function store(Request $request)
    {
        $this->hook_validate_store($request);

        $this->authorize('create', $this->modelClass);

        $this->hook_before_store($request);

        $object = $this->modelClass::create($request->post());

        $this->hook_after_store($object);

        return getJson($object);
    }

    public function hook_validate_update(Request &$request, $id)
    {
        $request->validate($this->rules['update'], $this->validateMessages, $this->fieldNames);
    }

    public function hook_before_update(Request &$request, $id)
    {

    }

    public function hook_after_update(&$model)
    {

    }

    public function update(Request $request, $id)
    {
        $this->hook_validate_update($request, $id);

        $this->authorize('update', $this->modelClass::find($id));

        $this->hook_before_update($request, $id);

        $object = $this->modelClass::find($id);

        $object->update($request->post());

        $this->hook_after_update($object);

        $this->runElasticIndexer($object);

        return getJson($object);
    }

    public function hook_validate_destroy(Request &$request)
    {
        $request->validate($this->rules['destroy'], $this->validateMessages, $this->fieldNames);
    }

    public function hook_before_destroy(Request &$request, &$object)
    {

    }

    public function hook_after_destroy()
    {

    }

    public function destroy(Request $request, $id)
    {
        $this->hook_validate_destroy($request);

        $object = $this->modelClass::find($id);

        $this->authorize('delete', $object);

        $this->hook_before_destroy($request, $object);

        $object->delete();

        $this->hook_after_destroy();

        return getJson([]);
    }

}
