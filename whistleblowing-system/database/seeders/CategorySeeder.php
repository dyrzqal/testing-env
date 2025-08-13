<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Corruption & Bribery',
                'description' => 'Reports related to corruption, bribery, kickbacks, and financial misconduct',
                'color' => '#EF4444',
                'sort_order' => 1
            ],
            [
                'name' => 'Fraud & Financial Misconduct',
                'description' => 'Financial fraud, embezzlement, misuse of company funds',
                'color' => '#F59E0B',
                'sort_order' => 2
            ],
            [
                'name' => 'Workplace Harassment',
                'description' => 'Sexual harassment, bullying, discrimination, and hostile work environment',
                'color' => '#DC2626',
                'sort_order' => 3
            ],
            [
                'name' => 'Safety & Health Violations',
                'description' => 'Workplace safety violations, health hazards, environmental concerns',
                'color' => '#059669',
                'sort_order' => 4
            ],
            [
                'name' => 'Data & Privacy Breach',
                'description' => 'Unauthorized access to data, privacy violations, information security breaches',
                'color' => '#7C3AED',
                'sort_order' => 5
            ],
            [
                'name' => 'Ethics & Compliance',
                'description' => 'Violations of company policies, ethics code, or legal compliance',
                'color' => '#2563EB',
                'sort_order' => 6
            ],
            [
                'name' => 'Conflict of Interest',
                'description' => 'Undisclosed conflicts of interest, nepotism, favoritism',
                'color' => '#DB2777',
                'sort_order' => 7
            ],
            [
                'name' => 'Theft & Misuse of Resources',
                'description' => 'Theft of company property, misuse of company resources or time',
                'color' => '#EA580C',
                'sort_order' => 8
            ],
            [
                'name' => 'Regulatory Violations',
                'description' => 'Violations of industry regulations, licensing requirements',
                'color' => '#0891B2',
                'sort_order' => 9
            ],
            [
                'name' => 'Other Misconduct',
                'description' => 'Other types of misconduct not covered by the above categories',
                'color' => '#6B7280',
                'sort_order' => 10
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'color' => $category['color'],
                'sort_order' => $category['sort_order'],
                'is_active' => true
            ]);
        }
    }
}
