<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(ReportRequest $request)
    {
        $report = Report::query()->create($request->validated() + [
            'user_id' => $request->user()->id,
            'status' => 'open',
        ]);

        return response()->json($report, 201);
    }

    public function index()
    {
        return response()->json(Report::query()->latest()->get());
    }

    public function resolve(Request $request, Report $report)
    {
        $report->update([
            'status' => 'resolved',
            'resolved_by' => $request->user()->id,
            'resolved_at' => now(),
        ]);

        return response()->json($report);
    }
}
