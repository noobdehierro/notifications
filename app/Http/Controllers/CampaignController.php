<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignChannelTemplate;
use App\Models\Notification;
use App\Models\Template;
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
    public function index()
    {
        $campaigns = Campaign::all();
        return view("pages.campaigns.index", compact("campaigns"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return ViewContract|ViewFactory
     */
    public function create()
    {
        $templates = Template::all();
        return view("pages.campaigns.create", compact("templates"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|unique:campaigns",
            "query" => "required",
            "templates_id" => "required",
            "days" => "required",
            "hour" => "required",
            "status" => "required",
        ]);

        $request["templates_id"] = json_encode($request["templates_id"]);

        try {
            Campaign::create($request->all());
            return redirect()->route('campaigns.index')->with('success', 'Campaign created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('campaigns.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Campaign  $campaign
     * @return ViewContract|ViewFactory
     */
    public function show(Campaign $campaign)
    {
        // return view('pages.campaigns.show', ['campaign' => $campaign]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Campaign  $campaign
     * @return ViewContract|ViewFactory
     */
    public function edit(Campaign $campaign)
    {
        $templates = Template::all();

        return view("pages.campaigns.edit", compact("campaign", "templates"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Campaign  $campaign
     * @return RedirectResponse
     */
    public function update(Request $request, Campaign $campaign)
    {
        $request->validate([
            "name" => "required|unique:campaigns,name," . $campaign->id,
        ]);

        try {
            $campaign->update($request->all());
            return redirect()->route('campaigns.index')->with('success', 'Campaign updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('campaigns.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Campaign  $campaign
     * @return RedirectResponse
     */
    public function destroy(Campaign $campaign)
    {
        try {
            $campaign->delete();
            return redirect()->route('campaigns.index')->with('success', 'Campaign deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('campaigns.index')->with('error', $e->getMessage());
        }
    }
}
