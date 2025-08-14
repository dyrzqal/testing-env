<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        // Status valid sesuai DB
        $statusOptions = ['submitted', 'under_review', 'investigating', 'requires_more_info', 'resolved', 'dismissed'];
        $priorityOptions = ['low', 'medium', 'high'];
        $urgencyOptions = ['low', 'medium', 'high', 'critical'];

        $reports = [
            [
                'title' => 'Suspicious Financial Transactions in Accounting Department',
                'description' => 'I have noticed unusual financial transactions in the accounting department that seem to be unauthorized. Large amounts are being transferred to unknown accounts.',
                'status' => 'submitted',
                'priority' => 'high',
                'urgency_level' => 'high',
                'category_id' => $categories->where('name', 'Financial Misconduct')->first()->id,
                'assigned_to_user_id' => $users->where('role', 'investigator')->first()->id,
            ],
            [
                'title' => 'Workplace Harassment by Department Manager',
                'description' => 'A department manager has been creating a hostile work environment through inappropriate comments and behavior towards female employees.',
                'status' => 'under_review',
                'priority' => 'high',
                'urgency_level' => 'high',
                'category_id' => $categories->where('name', 'Harassment & Discrimination')->first()->id,
                'assigned_to_user_id' => $users->where('role', 'investigator')->first()->id,
            ],
            [
                'title' => 'Safety Hazards in Manufacturing Plant',
                'description' => 'Several safety violations have been observed in the manufacturing plant, including blocked emergency exits and malfunctioning safety equipment.',
                'status' => 'submitted',
                'priority' => 'high',
                'urgency_level' => 'critical',
                'category_id' => $categories->where('name', 'Safety Violations')->first()->id,
                'assigned_to_user_id' => null,
            ],
            [
                'title' => 'Data Privacy Breach in Customer Database',
                'description' => 'Unauthorized access to customer database has been detected. Personal information may have been compromised.',
                'status' => 'investigating',
                'priority' => 'high',
                'urgency_level' => 'critical',
                'category_id' => $categories->where('name', 'Data Privacy')->first()->id,
                'assigned_to_user_id' => $users->where('role', 'investigator')->skip(1)->first()->id,
            ],
            [
                'title' => 'Environmental Violations in Waste Disposal',
                'description' => 'Improper disposal of hazardous waste materials has been observed, which may violate environmental regulations.',
                'status' => 'submitted',
                'priority' => 'medium',
                'urgency_level' => 'medium',
                'category_id' => $categories->where('name', 'Environmental Issues')->first()->id,
                'assigned_to_user_id' => null,
            ],
        ];

        foreach ($reports as $reportData) {
            Report::create([
                'title' => $reportData['title'],
                'description' => $reportData['description'],
                'status' => $reportData['status'],
                'priority' => $reportData['priority'],
                'urgency_level' => $reportData['urgency_level'],
                'category_id' => $reportData['category_id'],
                'assigned_to_user_id' => $reportData['assigned_to_user_id'],
                'reference_number' => 'WBS-' . strtoupper(Str::random(8)),
                'reporter_name' => 'Anonymous',
                'reporter_email' => 'anonymous@wbs.com',
                'reporter_phone' => null,
                'incident_location' => 'Headquarters',
                'incident_date' => now()->subDays(rand(1, 30)),
                'is_anonymous' => 1,
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
                'submitted_at' => now(),
            ]);
        }

        // Generate dummy data sesuai enum
        for ($i = 6; $i <= 20; $i++) {
            $status = $statusOptions[array_rand($statusOptions)];
            $priority = $priorityOptions[array_rand($priorityOptions)];
            $urgency = $urgencyOptions[array_rand($urgencyOptions)];
            $category = $categories->random();
            $assignedUser = in_array($status, ['submitted', 'dismissed']) ? null : $users->where('role', 'investigator')->random();

            Report::create([
                'title' => 'Test Report ' . $i . ' - ' . $category->name,
                'description' => 'This is a test report for testing purposes. It contains sample data to demonstrate the system functionality.',
                'status' => $status,
                'priority' => $priority,
                'urgency_level' => $urgency,
                'category_id' => $category->id,
                'assigned_to_user_id' => $assignedUser ? $assignedUser->id : null,
                'reference_number' => 'WBS-' . strtoupper(Str::random(8)),
                'reporter_name' => 'Test User ' . $i,
                'reporter_email' => 'test' . $i . '@wbs.com',
                'reporter_phone' => '+1-555-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'incident_location' => 'Test Location ' . $i,
                'incident_date' => now()->subDays(rand(1, 60)),
                'is_anonymous' => 0,
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now()->subDays(rand(1, 60)),
                'submitted_at' => now(),
            ]);
        }
    }
}
