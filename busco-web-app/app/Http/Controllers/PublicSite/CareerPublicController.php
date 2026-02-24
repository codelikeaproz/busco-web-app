<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CareerPublicController extends Controller
{
    public function index(Request $request): View
    {
        $query = JobOpening::publiclyOpen()
            ->orderByDesc('posted_at')
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($request->filled('department')) {
            $query->where('department', (string) $request->query('department'));
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', (string) $request->query('employment_type'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($builder) use ($search): void {
                $builder
                    ->where('title', 'like', '%' . $search . '%')
                    ->orWhere('department', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhere('summary', 'like', '%' . $search . '%');
            });
        }

        return view('pages.careers', [
            'jobs' => $query->paginate(6)->withQueryString(),
            'departments' => JobOpening::publiclyOpen()
                ->select('department')
                ->distinct()
                ->orderBy('department')
                ->pluck('department'),
            'employmentTypes' => JobOpening::EMPLOYMENT_TYPES,
            'filters' => [
                'department' => (string) $request->query('department', ''),
                'employment_type' => (string) $request->query('employment_type', ''),
                'search' => (string) $request->query('search', ''),
            ],
            'activePage' => 'careers',
        ]);
    }

    public function show(JobOpening $jobOpening): View
    {
        abort_if($jobOpening->status !== JobOpening::STATUS_OPEN, 404);

        $relatedJobs = JobOpening::publiclyOpen()
            ->where('id', '!=', $jobOpening->id)
            ->where('department', $jobOpening->department)
            ->orderByDesc('posted_at')
            ->orderByDesc('id')
            ->take(3)
            ->get();

        return view('pages.careers.show', [
            'job' => $jobOpening,
            'relatedJobs' => $relatedJobs,
            'activePage' => 'careers',
        ]);
    }
}
