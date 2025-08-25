<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Report;

class ReportController extends Controller
{
    private function serializeReports($reports)
    {
        $serialized = [];

        foreach ($reports as $report) {
            // Load relationships
            $report->message;
            if (!$report->message) {
                continue; // Skip if message is not found
            }
            
            $report->message->getUser;
            if (!$report->message->getUser) {
                continue; // Skip if author is not found
            }

            $report->user;
            if (!$report->user) {
                continue; // Skip if reporter is not found
            }

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

    public function createReport(Request $request, $country_id)
    {
        // Get the authenticated user who reported the message
        $user_id = $request->user()->user_id;

        // Validate the request data
        $request->validate([
            'message_id' => 'required|exists:messages,message_id',
        ]);
        
        // Create new report
        $report = Report::create($request->input());
        $report->user_id = $user_id;
        $report->save();

        // Redirect back to previous page
        return redirect('/countries/' . $country_id);
    }

    public function viewReports()
    {
        return $this->viewReportsWithId(-1);
    }

    public function viewReportsWithId($message_id)
    {
        // Summarize reports based on message_id
        $serializedReport = [];

        if ($message_id > -1)
        {
            $selectedReports = Report::where('message_id', '=', $message_id)->get();

            if ($selectedReports->count() == 0)
            {
                //session->flash('error', 'No reports found for the selected message.');

                return redirect()->back();
            }

            $serializedReport = $this->serializeReports($selectedReports)[$message_id];
        }

        // Summarize all reports based on message_id
        $allReports = Report::all();
        $serializedReports = $this->serializeReports($allReports);

        // Pass reduced reports to views
        return view('viewReports', ['reports' => $serializedReports, 'selectedReport' => $serializedReport]);
    }

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
}
