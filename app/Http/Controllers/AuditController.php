<?php

namespace App\Http\Controllers;

use App\Audit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->view('audits.index', ['audits' => Audit::all(),]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Audit  $audit
     * @return Response
     */
    public function show(Audit $audit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Audit  $audit
     * @return Response
     */
    public function edit(Audit $audit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\Audit  $audit
     * @return Response
     */
    public function update(Request $request, Audit $audit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Audit  $audit
     * @return Response
     */
    public function destroy(Audit $audit)
    {
        //
    }
}
