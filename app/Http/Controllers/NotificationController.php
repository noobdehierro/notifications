<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignChannelTemplate;
use App\Models\Notification;
use App\Models\Template;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::all();

        return view('pages.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $campaigns = Campaign::all();

        return view('pages.notifications.create', compact('campaigns'));
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
            "campaign_id" => "required",
            "sent_at" => "required",
        ]);

        Notification::create([
            "campaign_id" => $request->input("campaign_id"),
            "sent_at" => $request->input("sent_at"),
            "status" => "Active",
        ]);

        return redirect()->route('notifications.index')->with('success', 'Campaign created successfully.');
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
    public function edit(Notification $notification)
    {
        $campaigns = Campaign::all();

        return view('pages.notifications.edit', compact('notification', 'campaigns'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        try {
            $notification->delete();
            return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('notifications.index')->with('error', $e->getMessage());
        }
    }
}
