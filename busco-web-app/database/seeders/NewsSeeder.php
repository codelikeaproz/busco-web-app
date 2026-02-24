<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'BUSCO Recognized for Quality Milling Standards',
                'sub_title' => 'BUSCO was formally recognized by the SRA for strong performance in quality milling operations during the regional industry summit.',
                'content' => 'BUSCO Sugar Milling Co., Inc. has been formally recognized by the Philippine Sugar Regulatory Administration (SRA) for outstanding performance in quality milling operations. The recognition was awarded during the Regional Sugar Industry Summit held in Cagayan de Oro City...',
                'category' => 'Achievements',
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'title' => 'Advisory on Milling Schedule for the Week',
                'sub_title' => 'Updated milling schedules and operational reminders were issued to partner farmers and stakeholders for smoother weekly coordination.',
                'content' => 'Updated milling schedule and operational reminders for partner farmers and stakeholders. Coordination with field teams is encouraged for seamless operations this week...',
                'category' => 'Announcements',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'BUSCO Participates in Regional Agriculture Expo',
                'sub_title' => 'BUSCO joined regional stakeholders to promote sustainable sugarcane farming and community development at the annual expo.',
                'content' => 'BUSCO joins regional stakeholders to promote sustainable sugarcane farming and community development at the annual agricultural exposition in Cagayan de Oro...',
                'category' => 'Events',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Farmer Training Program Conducted in Bukidnon',
                'sub_title' => 'Hands-on farmer training sessions focused on productivity, crop management, and safety practices were conducted in Bukidnon.',
                'content' => 'BUSCO supports farmers through hands-on training sessions focused on productivity, crop management, and safety practices in the field...',
                'category' => 'CSR / Community',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Operational Milestone: Successful Season Completion',
                'sub_title' => 'BUSCO marked a major seasonal milestone with improved efficiency and stronger collaboration with planter partners.',
                'content' => 'BUSCO marks a major operational milestone with improved efficiency and stronger farmer collaboration this milling season...',
                'category' => 'Achievements',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Holiday Office Advisory & Community Support Activities',
                'sub_title' => 'BUSCO released holiday office advisories and shared upcoming community outreach activities for the season.',
                'content' => 'BUSCO shares holiday advisories and highlights upcoming community outreach initiatives for the holiday season...',
                'category' => 'Announcements',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'BUSCO Conducts Preventive Maintenance for Milling Equipment',
                'sub_title' => 'A preventive maintenance program was completed to improve equipment reliability and prepare for peak milling operations.',
                'content' => 'A scheduled preventive maintenance program was completed to improve reliability, reduce downtime, and prepare for peak milling operations in the coming weeks...',
                'category' => 'Announcements',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Youth Internship Orientation Held for Partner Schools',
                'sub_title' => 'Student interns from partner schools joined an orientation on safety, plant operations awareness, and workplace professionalism.',
                'content' => 'BUSCO welcomed student interns from partner institutions and conducted an orientation focused on workplace safety, plant operations awareness, and professional conduct...',
                'category' => 'CSR / Community',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Safety Drill Strengthens Emergency Response Readiness',
                'sub_title' => 'A company-wide emergency drill improved preparedness and coordination with internal marshals and local responders.',
                'content' => 'A company-wide safety and emergency response drill was conducted in coordination with internal marshals and local responders to improve preparedness and response time...',
                'category' => 'Events',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'BUSCO Receives Recognition for Environmental Compliance',
                'sub_title' => 'The company was recognized for sustained environmental compliance and improvements in operational safeguards.',
                'content' => 'The company was recognized for sustained compliance efforts and continued improvements in environmental monitoring and operational safeguards...',
                'category' => 'Achievements',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Community Outreach Delivers Support to Partner Barangays',
                'sub_title' => 'BUSCO community teams completed outreach work and stakeholder consultations in partner barangays.',
                'content' => 'BUSCO community teams completed outreach activities in partner barangays, including support distribution and stakeholder consultations for upcoming programs...',
                'category' => 'CSR / Community',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Updated Planting Coordination Schedule for Planter Partners',
                'sub_title' => 'Coordination schedules and field support timelines were updated to streamline communication and improve operational planning.',
                'content' => 'Coordination schedules and field support timelines were updated to streamline communication with planter partners and improve operational planning...',
                'category' => 'Announcements',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'BUSCO Team Showcases Process Improvements at Industry Forum',
                'sub_title' => 'BUSCO representatives presented process improvements, operational insights, and best practices at an industry forum.',
                'content' => 'Representatives shared recent process improvements, operational insights, and best practices with peers and stakeholders during an industry forum...',
                'category' => 'Events',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Draft: Internal Planning Update for Next Milling Cycle',
                'sub_title' => 'Internal planning draft for admin coordination and pre-season readiness activities (unpublished demo record).',
                'content' => 'Internal planning update draft for administrative coordination and pre-season readiness activities. This record remains unpublished for admin workflow testing...',
                'category' => 'Announcements',
                'status' => 'draft',
                'is_featured' => false,
            ],
        ];

        foreach ($articles as $index => $article) {
            $article['created_at'] = now()->subDays(count($articles) - $index);
            $article['updated_at'] = $article['created_at'];
            News::create($article);
        }

        $this->command?->info(count($articles) . ' demo news articles seeded.');
    }
}
