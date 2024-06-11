<?php

namespace App\Http\Controllers;

use App\Models\Query;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $queries = Query::all();

        return view("pages.queries.index", compact("queries"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("pages.queries.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "query" => "required",
        ]);

        try {
            Query::create($request->all());
            return redirect()->route("queries.index")->with("success", "Query creado con éxito.");
        } catch (\Exception $e) {
            return redirect()->route("queries.index")->with("error", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function show(Query $query)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function edit(Query $query)
    {
        return view("pages.queries.edit", compact("query"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Query $query)
    {
        $request->validate([
            "name" => "required",
            "query" => "required",
        ]);

        try {
            $query->update($request->all());
            return redirect()->route("queries.index")->with("success", "Query actualizado con éxito.");
        } catch (\Exception $e) {
            return redirect()->route("queries.index")->with("error", $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function destroy(Query $query)
    {
        try {
            $query->delete();
            return redirect()->route("queries.index")->with("success", "Query eliminado con éxito.");
        } catch (\Exception $e) {
            return redirect()->route("queries.index")->with("error", $e->getMessage());
        }
    }
}
