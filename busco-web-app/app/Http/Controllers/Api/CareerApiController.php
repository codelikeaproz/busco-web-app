<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobOpeningDetailResource;
use App\Http\Resources\JobOpeningResource;
use App\Models\JobOpening;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CareerApiController extends Controller
{
    public function index(Request $request): JsonResponse
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

            if ($search !== '') {
                $operator = $query->getModel()->getConnection()->getDriverName() === 'pgsql'
                    ? 'ilike'
                    : 'like';

                $query->where(function ($builder) use ($search, $operator): void {
                    $builder
                        ->where('title', $operator, '%' . $search . '%')
                        ->orWhere('department', $operator, '%' . $search . '%')
                        ->orWhere('location', $operator, '%' . $search . '%')
                        ->orWhere('summary', $operator, '%' . $search . '%');
                });
            }
        }

        $jobs = $query->paginate(6)->withQueryString();

        return response()->json([
            'data' => JobOpeningResource::collection($jobs)->resolve(),
            'meta' => [
                'current_page' => $jobs->currentPage(),
                'last_page' => $jobs->lastPage(),
                'per_page' => $jobs->perPage(),
                'total' => $jobs->total(),
                'from' => $jobs->firstItem(),
                'to' => $jobs->lastItem(),
            ],
            'links' => [
                'first' => $jobs->url(1),
                'last' => $jobs->url($jobs->lastPage()),
                'prev' => $jobs->previousPageUrl(),
                'next' => $jobs->nextPageUrl(),
            ],
            'departments' => JobOpening::publiclyOpen()
                ->select('department')
                ->distinct()
                ->orderBy('department')
                ->pluck('department'),
            'employment_types' => JobOpening::EMPLOYMENT_TYPES,
            'filters' => [
                'department' => (string) $request->query('department', ''),
                'employment_type' => (string) $request->query('employment_type', ''),
                'search' => (string) $request->query('search', ''),
            ],
        ]);
    }

    public function show(JobOpening $jobOpening): JsonResponse
    {
        abort_unless($jobOpening->isPubliclyOpen(), 404);

        $relatedJobs = JobOpening::publiclyOpen()
            ->where('id', '!=', $jobOpening->id)
            ->where('department', $jobOpening->department)
            ->orderByDesc('posted_at')
            ->orderByDesc('id')
            ->take(3)
            ->get();

        return response()->json([
            'data' => JobOpeningDetailResource::make($jobOpening)->resolve(),
            'related_jobs' => JobOpeningResource::collection($relatedJobs)->resolve(),
        ]);
    }
}
