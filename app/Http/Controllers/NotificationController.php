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
        $templates = Template::all();
        $campaigns = Campaign::all();

        return view('pages.notifications.create', compact('templates', 'campaigns'));
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
            "templates_id" => "required",
            "sent_at" => "required",
        ]);

        $idTemplates = $request->input("templates_id");

        foreach ($idTemplates as $idTemplate) {
            $template = Template::find($idTemplate);
            CampaignChannelTemplate::create([
                "campaign_id" => $request->input("campaign_id"),
                "template_id" => $idTemplate,
                "channel_id" => $template->channel_id,
                "send" => 1,
            ]);
        }

        Notification::create([
            "campaign_id" => $request->input("campaign_id"),
            "sent_at" => $request->input("sent_at"),
            "status" => "pending",
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
    public function edit($id)
    {
        //
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
