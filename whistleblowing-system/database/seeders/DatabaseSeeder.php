<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Report;
use App\Models\ReportComment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@whistleblowing.local',
            'password' => Hash::make('admin123!'),
            'role' => 'admin',
            'department' => 'IT Administration',
            'is_active' => true,
        ]);

        // Create moderator
        $moderator = User::create([
            'name' => 'Report Moderator',
            'email' => 'moderator@whistleblowing.local',
            'password' => Hash::make('moderator123!'),
            'role' => 'moderator',
            'department' => 'Compliance',
            'is_active' => true,
        ]);

        // Create investigators
        $investigator1 = User::create([
            'name' => 'John Investigator',
            'email' => 'john.inv@whistleblowing.local',
            'password' => Hash::make('investigator123!'),
            'role' => 'investigator',
            'department' => 'Internal Audit',
            'is_active' => true,
        ]);

        $investigator2 = User::create([
            'name' => 'Sarah Investigator',
            'email' => 'sarah.inv@whistleblowing.local',
            'password' => Hash::make('investigator123!'),
            'role' => 'investigator',
            'department' => 'Legal',
            'is_active' => true,
        ]);

        // Create categories
        $categories = [
            [
                'name' => 'Financial Misconduct',
                'description' => 'Reports related to financial irregularities, fraud, embezzlement, or corruption.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Workplace Harassment',
                'description' => 'Reports of harassment, discrimination, or hostile work environment.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Safety Violations',
                'description' => 'Reports of safety violations, unsafe working conditions, or regulatory non-compliance.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Ethical Violations',
                'description' => 'Reports of unethical behavior, conflicts of interest, or policy violations.',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Data Protection',
                'description' => 'Reports related to data breaches, privacy violations, or information security issues.',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Environmental Issues',
                'description' => 'Reports of environmental violations or regulatory non-compliance.',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create sample reports
        $reports = [
            [
                'category_id' => 1,
                'title' => 'Suspected Financial Fraud in Procurement',
                'description' => 'I have observed irregularities in the procurement process where inflated invoices are being approved without proper verification. Multiple vendors seem to be related to each other and there are suspicious patterns in the bidding process.',
                'incident_location' => 'Finance Department, Building A',
                'incident_date' => now()->subDays(15),
                'incident_time' => '14:30:00',
                'persons_involved' => ['Finance Manager', 'Procurement Officer'],
                'evidence_description' => 'Screenshots of invoices, email communications, and vendor registration documents.',
                'urgency_level' => 'high',
                'status' => 'investigating',
                'is_anonymous' => true,
                'assigned_to_user_id' => $investigator1->id,
                'submitted_at' => now()->subDays(15),
                'reviewed_at' => now()->subDays(14),
            ],
            [
                'category_id' => 2,
                'title' => 'Sexual Harassment by Department Head',
                'description' => 'A department head has been making inappropriate comments and advances towards female employees. Multiple incidents have occurred during meetings and private conversations.',
                'incident_location' => 'Marketing Department',
                'incident_date' => now()->subDays(7),
                'incident_time' => '16:00:00',
                'persons_involved' => ['Marketing Director', 'Several female employees'],
                'evidence_description' => 'Text messages, witness statements from colleagues.',
                'urgency_level' => 'critical',
                'status' => 'under_review',
                'is_anonymous' => false,
                'reporter_name' => 'Jane Smith',
                'reporter_email' => 'jane.smith@company.com',
                'reporter_department' => 'Marketing',
                'assigned_to_user_id' => $investigator2->id,
                'submitted_at' => now()->subDays(7),
                'reviewed_at' => now()->subDays(6),
            ],
            [
                'category_id' => 3,
                'title' => 'Safety Equipment Not Provided',
                'description' => 'Workers in the manufacturing unit are not being provided with proper safety equipment. Hard hats, safety goggles, and protective gloves are either missing or of poor quality.',
                'incident_location' => 'Manufacturing Unit B',
                'incident_date' => now()->subDays(3),
                'urgency_level' => 'high',
                'status' => 'submitted',
                'is_anonymous' => true,
                'submitted_at' => now()->subDays(3),
            ],
            [
                'category_id' => 4,
                'title' => 'Conflict of Interest in Vendor Selection',
                'description' => 'The IT Manager is selecting vendors owned by his brother without proper disclosure or competitive bidding process.',
                'incident_location' => 'IT Department',
                'incident_date' => now()->subDays(20),
                'urgency_level' => 'medium',
                'status' => 'resolved',
                'resolution_details' => 'Investigation completed. IT Manager was counseled and new procurement policies implemented.',
                'is_anonymous' => false,
                'reporter_name' => 'Mike Johnson',
                'reporter_email' => 'mike.j@company.com',
                'assigned_to_user_id' => $investigator1->id,
                'submitted_at' => now()->subDays(20),
                'reviewed_at' => now()->subDays(19),
                'resolved_at' => now()->subDays(5),
            ],
            [
                'category_id' => 5,
                'title' => 'Customer Data Being Shared Improperly',
                'description' => 'I noticed that customer personal data is being shared with third parties without proper consent or data protection agreements.',
                'incident_location' => 'Customer Service Department',
                'incident_date' => now()->subDays(10),
                'urgency_level' => 'high',
                'status' => 'requires_more_info',
                'is_anonymous' => true,
                'assigned_to_user_id' => $investigator2->id,
                'submitted_at' => now()->subDays(10),
                'reviewed_at' => now()->subDays(9),
            ],
        ];

        foreach ($reports as $reportData) {
            $report = Report::create($reportData);

            // Add some comments to reports
            if (in_array($report->status, ['investigating', 'under_review', 'requires_more_info'])) {
                ReportComment::create([
                    'report_id' => $report->id,
                    'user_id' => $report->assigned_to_user_id ?? $moderator->id,
                    'comment' => 'Investigation has been initiated. Gathering preliminary evidence and witness statements.',
                    'is_internal' => true,
                ]);

                if ($report->status === 'requires_more_info') {
                    ReportComment::create([
                        'report_id' => $report->id,
                        'user_id' => $report->assigned_to_user_id,
                        'comment' => 'Please provide more specific details about the data sharing incidents, including dates and involved parties.',
                        'is_internal' => false,
                    ]);
                }
            }
        }

        echo "Database seeded successfully!\n";
        echo "Admin credentials: admin@whistleblowing.local / admin123!\n";
        echo "Moderator credentials: moderator@whistleblowing.local / moderator123!\n";
        echo "Investigator credentials: john.inv@whistleblowing.local / investigator123!\n";
        echo "Investigator credentials: sarah.inv@whistleblowing.local / investigator123!\n";
    }
}
