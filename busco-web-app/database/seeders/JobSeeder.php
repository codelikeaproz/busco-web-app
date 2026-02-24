<?php

namespace Database\Seeders;

use App\Models\JobOpening;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        $jobs = [
            [
                'title' => 'Mill Operations Supervisor',
                'department' => 'Operations',
                'location' => 'Bukidnon Plant',
                'employment_type' => 'Full-time',
                'status' => JobOpening::STATUS_OPEN,
                'posted_at' => $today->copy()->subDay()->toDateString(),
                'deadline_at' => $today->copy()->addDays(7)->toDateString(),
                'summary' => 'Lead mill floor operations and coordinate shift compliance, safety checks, and output targets.',
                'description' => "Lead daily mill operations and coordinate supervisors to meet production targets.\nMonitor compliance with safety and quality procedures.\nPrepare shift performance reports and escalation notes.",
                'qualifications' => "Graduate of Mechanical, Industrial, or related engineering course.\nAt least 3 years supervisory experience in industrial operations.\nStrong coordination and reporting skills.",
                'responsibilities' => "Supervise shift teams.\nCoordinate maintenance schedules with engineering.\nTrack downtime causes and corrective actions.",
            ],
            [
                'title' => 'Junior Accountant',
                'department' => 'Finance',
                'location' => 'Corporate Office',
                'employment_type' => 'Full-time',
                'status' => JobOpening::STATUS_OPEN,
                'posted_at' => $today->copy()->subDays(2)->toDateString(),
                'deadline_at' => $today->copy()->addDays(11)->toDateString(),
                'summary' => 'Support bookkeeping, reconciliations, and month-end finance documentation.',
                'description' => "Assist the finance team in bookkeeping and account reconciliation.\nPrepare vouchers and support month-end closing schedules.",
                'qualifications' => "BS Accountancy or Accounting Technology.\nDetail-oriented with strong spreadsheet skills.\nFresh graduates are welcome to apply.",
                'responsibilities' => "Record transactions.\nReconcile balances.\nAssist in audit-ready document preparation.",
            ],
            [
                'title' => 'Recruitment Specialist',
                'department' => 'HR',
                'location' => 'Corporate Office',
                'employment_type' => 'Contractual',
                'status' => JobOpening::STATUS_OPEN,
                'posted_at' => $today->copy()->subDays(3)->toDateString(),
                'deadline_at' => $today->copy()->addDays(14)->toDateString(),
                'summary' => 'Manage sourcing, screening, and coordination for manpower requirements across departments.',
                'description' => "Handle sourcing, screening, and interview coordination for open roles.\nCoordinate with department heads on hiring timelines and manpower requests.",
                'qualifications' => "Human Resource Management or Psychology graduate.\nExperience in recruitment workflow and interview coordination.",
                'responsibilities' => "Post job openings.\nScreen applicants.\nCoordinate interviews and pre-employment requirements.",
            ],
            [
                'title' => 'Supply Chain Analyst',
                'department' => 'Logistics',
                'location' => 'Distribution Center',
                'employment_type' => 'Full-time',
                'status' => JobOpening::STATUS_OPEN,
                'posted_at' => $today->copy()->subDays(5)->toDateString(),
                'deadline_at' => $today->copy()->addDays(18)->toDateString(),
                'summary' => 'Analyze dispatch, warehouse, and inventory movement data to improve planning accuracy.',
                'description' => "Analyze supply chain data across warehousing and dispatch activities.\nSupport reporting and process improvement recommendations.",
                'qualifications' => "Industrial Engineering, Logistics, or related course.\nStrong analytical and reporting skills.",
                'responsibilities' => "Maintain KPI reports.\nAnalyze stock movement.\nSupport inventory planning meetings.",
            ],
            [
                'title' => 'Field Technician',
                'department' => 'Operations',
                'location' => 'Bukidnon Fields',
                'employment_type' => 'Seasonal',
                'status' => JobOpening::STATUS_OPEN,
                'posted_at' => $today->copy()->subWeek()->toDateString(),
                'deadline_at' => $today->copy()->addDays(30)->toDateString(),
                'summary' => 'Assist field technical activities, equipment checks, and data gathering for seasonal operations.',
                'description' => "Provide field support for seasonal operations and technical inspections.\nAssist with equipment checks and basic maintenance reporting.",
                'qualifications' => "Vocational or technical background preferred.\nWilling to work on field assignments.",
                'responsibilities' => "Field inspection support.\nData logging.\nBasic troubleshooting assistance.",
            ],
            [
                'title' => 'PC & Network Technician',
                'department' => 'MIS',
                'location' => 'Millsite / Head Office',
                'employment_type' => 'Full-time',
                'status' => JobOpening::STATUS_OPEN,
                'posted_at' => $today->copy()->subDays(4)->toDateString(),
                'deadline_at' => $today->copy()->addDays(9)->toDateString(),
                'summary' => 'Support workstation setup, troubleshooting, CCTV, and basic network maintenance for MIS.',
                'description' => "Install, maintain, and troubleshoot computers and basic network equipment.\nSupport CCTV installation and user technical concerns.",
                'qualifications' => "IT, Computer Engineering, or related course.\nKnowledgeable in PC troubleshooting and basic networking.",
                'responsibilities' => "PC setup and maintenance.\nCCTV support.\nUser issue response and documentation.",
            ],
            [
                'title' => 'Boiler Operator',
                'department' => 'Operations',
                'location' => 'Bukidnon Plant',
                'employment_type' => 'Full-time',
                'status' => JobOpening::STATUS_HIRED,
                'posted_at' => $today->copy()->subDays(16)->toDateString(),
                'deadline_at' => $today->copy()->subDays(2)->toDateString(),
                'summary' => 'Position filled for current cycle; retained in admin records for tracking.',
                'description' => 'This role has been filled for the current hiring cycle and is retained for admin history tracking.',
                'qualifications' => 'Relevant operator certification and plant experience.',
                'responsibilities' => 'Operate boiler systems safely and monitor gauges.',
            ],
            [
                'title' => 'Corporate Communications Officer',
                'department' => 'Admin',
                'location' => 'Corporate Office',
                'employment_type' => 'Full-time',
                'status' => JobOpening::STATUS_CLOSED,
                'posted_at' => $today->copy()->subDays(20)->toDateString(),
                'deadline_at' => $today->copy()->subDays(5)->toDateString(),
                'summary' => 'Closed position retained for admin record management and future reopening reference.',
                'description' => 'This posting is closed. The record is kept for admin reference and future updates.',
                'qualifications' => 'Communication or related degree.',
                'responsibilities' => 'Internal and external communication support.',
            ],
            [
                'title' => 'Plant Safety Assistant',
                'department' => 'Safety',
                'location' => 'Bukidnon Plant',
                'employment_type' => 'Contractual',
                'status' => JobOpening::STATUS_DRAFT,
                'posted_at' => $today->copy()->toDateString(),
                'deadline_at' => $today->copy()->addDays(21)->toDateString(),
                'summary' => 'Draft posting for upcoming safety staffing requirement.',
                'description' => 'Draft version for planned hiring; not yet visible on the public careers page.',
                'qualifications' => 'OSH-related background preferred.',
                'responsibilities' => 'Assist safety inspections and compliance documentation.',
            ],
        ];

        foreach ($jobs as $job) {
            $slug = Str::slug((string) $job['title']) ?: 'job-opening';

            JobOpening::query()->updateOrCreate(
                ['title' => $job['title']],
                array_merge($job, [
                    'slug' => $slug,
                    'application_email' => 'hrd_buscosugarmill@yahoo.com',
                ])
            );
        }
    }
}
