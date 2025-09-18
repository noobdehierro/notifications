<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = Template::all();
        return view('pages.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $channels = Channel::all();

        return view('pages.templates.create', compact('channels'));
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
            'name' => 'required',
            'channel_id' => 'required',
            'placeholder' => 'required_if:channel_id,2,3', // Solo requerido para ciertos channel_ids
            'template_name' => 'required_if:channel_id,1', // Solo requerido para channel_id 4 (WhatsApp)
        ]);

        // $channelName = Channel::find($request->channel_id)->name;

        // $request['name'] = $request->name . ' - ' . $channelName;

        try {
            Template::create($request->all());
            return redirect()->route('templates.index')->with('success', 'Template created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('templates.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {
        $channels = Channel::all();

        return view('pages.templates.edit', compact('template', 'channels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'channel_id' => 'required',
            'placeholder' => 'required_if:channel_id,2,3', // Solo requerido para ciertos channel_ids
            'template_name' => 'required_if:channel_id,1', // Solo requerido para channel_id 
        ]);

        try {
            $template = Template::find($id);
            $template->update($request->all());
            return redirect()->route('templates.index')->with('success', 'Template updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('templates.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        try {
            $template->delete();
            return redirect()->route('templates.index')->with('success', 'Template deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('templates.index')->with('error', $e->getMessage());
        }
    }
}
