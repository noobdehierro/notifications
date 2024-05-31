<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configurations = Configuration::paginate(25);

        return view('pages.configurations.index', [
            'configurations' => $configurations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.configurations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'label' => 'required',
            'code' => ['required', 'unique:configurations'],
            'value' => 'required',
        ]);

        try {

            Configuration::create($attributes);
            return redirect()
                ->route('configurations.index')
                ->with('success', 'Se ha añadido una configuración correctamente.');
        } catch (\Exception $exception) {
            return redirect()
                ->route('configurations.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function show(Configuration $configuration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function edit(Configuration $configuration)
    {
        return view('pages.configurations.edit', [
            'configuration' => $configuration
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Configuration $configuration)
    {
        $attributes = $request->validate([
            'label' => 'required',
            'code' => ['required', 'unique:configurations,code,' . $configuration->id],
            'value' => 'required',
        ]);

        try {
            $configuration->update($attributes);
            return redirect()
                ->route('configurations.index')
                ->with('success', 'Se ha actualizado la configuración correctamente.');
        } catch (\Exception $exception) {
            return redirect()
                ->route('configurations.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Configuration $configuration)
    {
        try {
            $configuration->delete();
            return redirect()
                ->route('configurations.index')
                ->with('success', 'Se ha eliminado la configuración correctamente.');
        } catch (\Exception $exception) {
            return redirect()
                ->route('configurations.index')->with('error', $exception->getMessage());
        }
    }
}
