<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use \App\Models\Report;

class ReportController extends Controller
{
    private function serializeReports($reports)
    {
        $serialized = [];

        foreach ($reports as $report) {
            // Initialize report structure if not exists
            if (!array_key_exists($report->mesage_id, $serialized))
            {
                $serialized[$report->message_id] = [
                    'message' => $report->message,
                    'reports' => [],
                    'count' => 0
                ];
            }

            $serialized[$report->message_id]['count']++;
            $serialized[$report->message_id]['reports'][] = [
                'user' => $report->user,
            ];
        }

        return $serialized;
    }

    public function store(Request $request, $country_id, $message_id)
    {
        // Get the authenticated user who reported the message
        $user_id = $request->user()->user_id;

        // Validate the request data
        $request->validate([
            'message_id' => 'required|exists:messages,message_id',
        ]);

        $exactMatch = Report::where([
            ['user_id', '=', $user_id],
            ['message_id', '=', $request->input('message_id')],
        ])->first();

        if ($exactMatch) // User already reported this message before
        {
            return redirect()->route('countries.show', ['id' => $country_id])
                ->with('success', 'Already reported!');
        }
        
        // Create new report
        $report = Report::create($request->input());
        $report->user_id = $user_id;
        $report->save();

        // Redirect back
        return redirect()->route('countries.show', ['id' => $country_id])
            ->with('success', 'Reported successfully!');
    }

    public function index($message_id = null)
    {
        // Summarize reports based on message_id
        $serializedReport = [];

        if ($message_id)
        {
            $selectedReports = Report::where('message_id', '=', $message_id)->get();

            if ($selectedReports->count() == 0)
            {
                //session->flash('error', 'No reports found for the selected message.');

                return redirect()->route('reports.view');
            }

            $serializedReport = $this->serializeReports($selectedReports)[$message_id];
        }

        // Summarize all reports based on message_id
        $allReports = Report::with(['message.country'])
            ->get()
            ->filter(function($report) { return !empty($report->message); })
            ->filter(function($report) { return $report->message->country->continent_id == Auth::user()->country->continent_id; })
            ->values();

        $serializedReports = $this->serializeReports($allReports);

        $search = Cookie::get('report-search');
        $filter = Cookie::get('report-filter');

        // Pass reduced reports to views
        return view('viewReports', [
            'reports' => $serializedReports,
            'selectedReport' => $serializedReport,
            'search' => $search,
            'filter' => $filter
        ]);
    }

    public function query(Request $request)
    {
        $search = $request->keyword;
        $filter = $request->country;

        $results = [];

        if ($filter == 0) {
            $results = Report::with(['user', 'message.country'])
                ->get()
                ->filter(function($report) { return !empty($report->message); })
                ->filter(function($report) { return $report->message->country->continent_id == Auth::user()->country->continent_id; })
                ->filter(function($report) use ($search) { return !empty($search) ? Str::contains($report->message->content, $search) : true; })
                ->values();
        } else {
            $results = Report::with(['user', 'message.country'])
                ->get()
                ->filter(function($report) { return !empty($report->message); })
                ->filter(function($report) use ($filter) { return $report->message->country_id == $filter; })
                ->filter(function($report) use ($search) { return !empty($search) ? Str::contains($report->message->content, $search) : true; })
                ->values();
        }
        
        // Set cookie after performing query (search/filter)
        return response($results)
            ->cookie('report-search', $search, 60, '/', null, true, true)
            ->cookie('report-filter', $filter, 60, '/', null, true, true);
    }

    /* Deprecated */
    /*
    public function searchReports(Request $request)
    {
        // Validate the search input
        $request->validate([
            'search' => 'string|max:255',
        ]);

        $reports = null;

        // Search for reports based on the search term
        $searchTerm = $request->input('search');

        if (strlen($searchTerm) > 0)
        {
            $reports = Report::whereHas('message', function ($query) use ($searchTerm) {
                $query->where('content', 'like', '%' . $searchTerm . '%');
            })->get();
        }
        else
        {
            $reports = Report::all();
        }

        // Serialize the reports
        $serializedReports = $this->serializeReports($reports);

        // Return the view with the serialized reports
        return view('viewReports', ['reports' => $serializedReports, 'selectedReport' => []]);
    }
    */
}
