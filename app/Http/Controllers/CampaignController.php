<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\RedirectResponse;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ViewContract|ViewFactory
     */
    public function index(): ViewContract|ViewFactory
    {
        $campaigns = Campaign::all();
        return view("pages.campaigns.index", ["campaigns" => $campaigns]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return ViewContract|ViewFactory
     */
    public function create(): ViewContract|ViewFactory
    {
        return view("pages.campaigns.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "name" => "required|unique:campaigns",
        ]);

        $campaign = new Campaign();
        $campaign->name = $request->get("name");
        $campaign->save();

        return redirect()->route('campaigns.index')->with('success', 'Campaign created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Campaign  $campaign
     * @return ViewContract|ViewFactory
     */
    public function show(Campaign $campaign): ViewContract|ViewFactory
    {
        // return view('pages.campaigns.show', ['campaign' => $campaign]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Campaign  $campaign
     * @return ViewContract|ViewFactory
     */
    public function edit(Campaign $campaign): ViewContract|ViewFactory
    {
        return view("pages.campaigns.edit", ["campaign" => $campaign]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Campaign  $campaign
     * @return RedirectResponse
     */
    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $request->validate([
            "name" => "required|unique:campaigns,name," . $campaign->id,
        ]);

        $campaign->name = $request->get("name");
        $campaign->save();

        return redirect()->route('campaigns.index')->with('success', 'Campaign updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Campaign  $campaign
     * @return RedirectResponse
     */
    public function destroy(Campaign $campaign): RedirectResponse
    {
        $campaign->delete();
        return redirect()->route('campaigns.index')->with('success', 'Campaign deleted successfully.');
    }
}
