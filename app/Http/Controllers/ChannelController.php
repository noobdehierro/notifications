<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $channels = Channel::all();
        return view('pages.channels.index', compact('channels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.channels.create');
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
            'name' => 'required|unique:campaigns',
        ]);

        try {
            Channel::create($request->all());
            return redirect()->route('channels.index')->with('success', 'Canal creado con éxito.');
        } catch (\Exception $e) {
            return redirect()->route('channels.index')->with('error', $e->getMessage());
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
    public function edit(Channel $channel)
    {
        return view('pages.channels.edit', compact('channel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Channel $channel)
    {

        $request->validate([
            'name' => 'required|unique:campaigns,name,' . $channel->id,
        ]);

        try {
            $channel->update($request->all());
            return redirect()->route('channels.index')->with('success', 'Canal actualizado con éxito.');
        } catch (\Exception $e) {
            return redirect()->route('channels.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel)
    {

        try {
            $channel->delete();
            return redirect()->route('channels.index')->with('success', 'Canal eliminado con éxito.');
        } catch (\Exception $e) {
            return redirect()->route('channels.index')->with('error', $e->getMessage());
        }
    }
}
