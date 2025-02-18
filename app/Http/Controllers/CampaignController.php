<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignChannelTemplate;
use App\Models\Notification;
use App\Models\Query;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ViewContract|ViewFactory
     */
    public function index()
    {
        // $campaigns = Campaign::all();
        $campaigns = Campaign::with('templates')->get();
        // dd($campaigns);
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
        $queries = Query::all();
        return view("pages.campaigns.create", compact("templates", "queries"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $templatesId = array_filter($request->input('templates_id'));
        $templatesId = array_values($templatesId); // Reindexa los elementos
        $request->merge(['templates_id' => $templatesId]);

        $attributes = $request->validate([
            'name' => 'required|string|unique:campaigns,name',
            'query_id' => 'required|exists:queries,id',
            // 'days' => 'required|array',
            // 'hour' => 'required|string',
            'days' => [
                'required',
                'array',
                Rule::unique('campaigns')->where(function ($query) use ($request) {
                    return $query->where('hour', $request->hour);
                }),
            ],
            'hour' => 'required|string',
            'is_active' => 'nullable|in:on,off',
            'templates_id' => 'required|array'
        ]);
        $attributes['days'] = json_encode($attributes['days']);
        $attributes['is_active'] = $request->is_active == 'on';

        try {
            $campaign = Campaign::create($attributes);

            if ($request->has('templates_id')) {
                // dd($request->templates_id);
                $campaign->templates()->sync($request->templates_id);
            }

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
        $campaign = $campaign->load('templates'); // Cargar la relación 'templates'

        $templates = Template::all();
        $queries = Query::all();
        return view("pages.campaigns.edit", compact("campaign", "templates", "queries"));
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

        $templatesId = array_filter($request->input('templates_id'));
        $templatesId = array_values($templatesId); // Reindexa los elementos
        $request->merge(['templates_id' => $templatesId]);

        $attributes = $request->validate([
            'name' => 'required|string|unique:campaigns,name,' . $campaign->id,
            'query_id' => 'required|exists:queries,id',
            'days' => 'required|array',
            'hour' => 'required|string',
            'is_active' => 'nullable|in:on,off',
            'templates_id' => 'required|array'
        ]);
        $attributes['days'] = json_encode($attributes['days']);
        $attributes['is_active'] = $request->is_active == 'on';

        try {
            $campaign->update($attributes);
            if ($request->has('templates_id')) {
                $campaign->templates()->sync($request->templates_id);
            }
            return redirect()->route('campaigns.index')->with('success', 'Campaign updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('campaigns.index')->with('error', $th->getMessage());
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

    public function proof(Request $request)
    {

        try {
            $getTemplates = Template::whereIn('id', $request->prueba_templates_id)->get();
            // dd($getTemplates->toArray());

            foreach ($getTemplates as $template) {
                $channelName = $template->channel->name;
                $templatePlaceholder = $template->placeholder;
                // dd($templatePlaceholder);

                echo $channelName . ', ' . $request->email . ', ' . $template->name . '<br>';
                if ($channelName == 'Email' && $request->email) {
                    sendEmail($request->email, $templatePlaceholder, $template->name);
                }

                if ($channelName == 'WhatsApp' && $request->telefono) {
                    try {
                        sendWhatsapp($request->telefono, $templatePlaceholder, $template->name);
                    } catch (\Throwable $th) {
                        return back()->with('error', $th->getMessage());
                    }
                }

                if ($channelName == 'SMS' && $request->telefono) {
                    try {
                        sendSms($request->telefono, $templatePlaceholder);
                    } catch (\Throwable $th) {
                        return back()->with('error', $th->getMessage());
                    }
                }
            }

            return back()->with('success', 'Prueba enviada con exito');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
