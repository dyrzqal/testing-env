<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();
        
        // Sample report data
        $reports = [
            [
                'title' => 'Suspicious Financial Transactions in Accounting Department',
                'description' => 'I have noticed unusual financial transactions in the accounting department that seem to be unauthorized. Large amounts are being transferred to unknown accounts.',
                'status' => 'pending',
                'priority' => 'high',
                'category_id' => $categories->where('name', 'Financial Misconduct')->first()->id,
                'assigned_to_user_id' => $users->where('role', 'investigator')->first()->id,
            ],
            [
                'title' => 'Workplace Harassment by Department Manager',
                'description' => 'A department manager has been creating a hostile work environment through inappropriate comments and behavior towards female employees.',
                'status' => 'in_progress',
                'priority' => 'high',
                'category_id' => $categories->where('name', 'Harassment & Discrimination')->first()->id,
                'assigned_to_user_id' => $users->where('role', 'investigator')->first()->id,
            ],
            [
                'title' => 'Safety Hazards in Manufacturing Plant',
                'description' => 'Several safety violations have been observed in the manufacturing plant, including blocked emergency exits and malfunctioning safety equipment.',
                'status' => 'pending',
                'priority' => 'high',
                'category_id' => $categories->where('name', 'Safety Violations')->first()->id,
                'assigned_to_user_id' => null,
            ],
            [
                'title' => 'Data Privacy Breach in Customer Database',
                'description' => 'Unauthorized access to customer database has been detected. Personal information may have been compromised.',
                'status' => 'in_progress',
                'priority' => 'high',
                'category_id' => $categories->where('name', 'Data Privacy')->first()->id,
                'assigned_to_user_id' => $users->where('role', 'investigator')->skip(1)->first()->id,
            ],
            [
                'title' => 'Environmental Violations in Waste Disposal',
                'description' => 'Improper disposal of hazardous waste materials has been observed, which may violate environmental regulations.',
                'status' => 'pending',
                'priority' => 'medium',
                'category_id' => $categories->where('name', 'Environmental Issues')->first()->id,
                'assigned_to_user_id' => null,
            ],
            [
                'title' => 'Corruption in Procurement Process',
                'description' => 'Evidence of kickbacks and bribery in the procurement process for office supplies and equipment.',
                'status' => 'resolved',
                'priority' => 'high',
                'category_id' => $categories->where('name', 'Corruption')->first()->id,
                'assigned_to_user_id' => $users->where('role', 'investigator')->skip(2)->first()->id,
            ],
            [
                'title' => 'Quality Control Issues in Product Line',
                'description' => 'Multiple customer complaints about product quality and defects that were not caught by quality control.',
                'status' => 'in_progress',
                'priority' => 'medium',
                'category_id' => $categories->where('name', 'Quality Control')->first()->id,
                'assigned_to_user_id' => $users->where('role', 'investigator')->skip(3)->first()->id,
            ],
            [
                'title' => 'Regulatory Compliance Failure in HR',
                'description' => 'HR department is not following proper procedures for employee documentation and compliance requirements.',
                'status' => 'pending',
                'priority' => 'medium',
                'category_id' => $categories->where('name', 'Regulatory Compliance')->first()->id,
                'assigned_to_user_id' => null,
            ],
            [
                'title' => 'Conflict of Interest in Vendor Selection',
                'description' => 'A manager is selecting vendors based on personal relationships rather than company best interests.',
                'status' => 'resolved',
                'priority' => 'medium',
                'category_id' => $categories->where('name', 'Conflict of Interest')->first()->id,
                'assigned_to_user_id' => $users->where('role', 'investigator')->skip(4)->first()->id,
            ],
            [
                'title' => 'Unauthorized Use of Company Resources',
                'description' => 'Employees are using company vehicles and equipment for personal purposes without authorization.',
                'status' => 'rejected',
                'priority' => 'low',
                'category_id' => $categories->where('name', 'Other')->first()->id,
                'assigned_to_user_id' => null,
            ],
        ];

        foreach ($reports as $reportData) {
            Report::create([
                'title' => $reportData['title'],
                'description' => $reportData['description'],
                'status' => $reportData['status'],
                'priority' => $reportData['priority'],
                'category_id' => $reportData['category_id'],
                'assigned_to_user_id' => $reportData['assigned_to_user_id'],
                'reference_number' => 'WBS-' . strtoupper(Str::random(8)),
                'reporter_name' => 'Anonymous',
                'reporter_email' => 'anonymous@wbs.com',
                'reporter_phone' => null,
                'location' => 'Headquarters',
                'incident_date' => now()->subDays(rand(1, 30)),
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Create additional reports for better testing
        for ($i = 11; $i <= 25; $i++) {
            $status = ['pending', 'in_progress', 'resolved', 'rejected'][rand(0, 3)];
            $priority = ['low', 'medium', 'high'][rand(0, 2)];
            $category = $categories->random();
            $assignedUser = $status === 'pending' ? null : $users->where('role', 'investigator')->random();
            
            Report::create([
                'title' => 'Test Report ' . $i . ' - ' . $category->name,
                'description' => 'This is a test report for testing purposes. It contains sample data to demonstrate the system functionality.',
                'status' => $status,
                'priority' => $priority,
                'category_id' => $category->id,
                'assigned_to_user_id' => $assignedUser ? $assignedUser->id : null,
                'reference_number' => 'WBS-' . strtoupper(Str::random(8)),
                'reporter_name' => 'Test User ' . $i,
                'reporter_email' => 'test' . $i . '@wbs.com',
                'reporter_phone' => '+1-555-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'location' => 'Test Location ' . $i,
                'incident_date' => now()->subDays(rand(1, 60)),
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now()->subDays(rand(1, 60)),
            ]);
        }
    }
}