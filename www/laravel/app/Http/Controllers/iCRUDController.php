<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

interface iCRUDController
{

    function hook_validate_all(Request &$request);
    function hook_before_all(&$query);
    function all(Request $request);
    function hook_after_all(&$paginateCollection);

    function hook_validate_index(Request &$request);
    function hook_before_index(&$query);
    function index(Request $request);
    function hook_after_index(&$paginateCollection);

    function hook_validate_show(Request &$request);
    function hook_before_show(&$query, $id);
    function show(Request $request, $id);
    function hook_after_show(&$model);

    function hook_validate_store(Request &$request);
    function hook_before_store(Request &$request);
    function store(Request $request);
    function hook_after_store(&$model);

    function hook_validate_update(Request &$request, $id);
    function hook_before_update(Request &$request, $id);
    function update(Request $request, $id);
    function hook_after_update(&$model);

    function hook_validate_destroy(Request &$request);
    function hook_before_destroy(Request &$request, &$object);
    function destroy(Request $request, $id);
    function hook_after_destroy();
}
