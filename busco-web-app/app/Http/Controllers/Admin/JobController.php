<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(Request $request): View
    {
        $query = JobOpening::query();

        if ($request->filled('status')) {
            $query->where('status', (string) $request->query('status'));
        }

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
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        return view('admin.jobs.index', [
            'jobs' => $query
                ->orderByDesc('posted_at')
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->paginate(10)
                ->withQueryString(),
            'departments' => JobOpening::query()
                ->select('department')
                ->distinct()
                ->orderBy('department')
                ->pluck('department'),
            'employmentTypes' => JobOpening::EMPLOYMENT_TYPES,
            'statuses' => JobOpening::STATUSES,
            'filters' => [
                'status' => (string) $request->query('status', ''),
                'department' => (string) $request->query('department', ''),
                'employment_type' => (string) $request->query('employment_type', ''),
                'search' => (string) $request->query('search', ''),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.jobs.create', [
            'employmentTypes' => JobOpening::EMPLOYMENT_TYPES,
            'statuses' => JobOpening::STATUSES,
            'defaultApplicationEmail' => 'hrd_buscosugarmill@yahoo.com',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedJobData($request);
        $validated['application_email'] = 'hrd_buscosugarmill@yahoo.com';
        $validated['posted_at'] = $validated['posted_at'] ?? now()->toDateString();

        $job = JobOpening::create($validated);

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'Job opening "' . $job->title . '" created successfully.');
    }

    public function edit(JobOpening $job): View
    {
        return view('admin.jobs.edit', [
            'job' => $job,
            'employmentTypes' => JobOpening::EMPLOYMENT_TYPES,
            'statuses' => JobOpening::STATUSES,
        ]);
    }

    public function update(Request $request, JobOpening $job): RedirectResponse
    {
        $validated = $this->validatedJobData($request);
        $validated['application_email'] = 'hrd_buscosugarmill@yahoo.com';

        $job->update($validated);

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'Job opening updated successfully.');
    }

    public function destroy(JobOpening $job): RedirectResponse
    {
        $title = $job->title;
        $job->delete();

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', '"' . $title . '" has been deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedJobData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'in:' . implode(',', JobOpening::EMPLOYMENT_TYPES)],
            'status' => ['required', 'in:' . implode(',', JobOpening::STATUSES)],
            'posted_at' => ['nullable', 'date'],
            'deadline_at' => ['nullable', 'date', 'after_or_equal:posted_at'],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'qualifications' => ['nullable', 'string'],
            'responsibilities' => ['nullable', 'string'],
        ]);
    }
}
