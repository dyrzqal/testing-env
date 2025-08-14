<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Financial Misconduct',
                'description' => 'Reports related to financial fraud, embezzlement, or financial irregularities',
                'color' => '#EF4444',
                'is_active' => true,
            ],
            [
                'name' => 'Harassment & Discrimination',
                'description' => 'Reports of workplace harassment, discrimination, or hostile work environment',
                'color' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Safety Violations',
                'description' => 'Reports of safety hazards, workplace accidents, or safety protocol violations',
                'color' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Data Privacy',
                'description' => 'Reports of data breaches, privacy violations, or information security issues',
                'color' => '#3B82F6',
                'is_active' => true,
            ],
            [
                'name' => 'Environmental Issues',
                'description' => 'Reports of environmental violations, pollution, or sustainability concerns',
                'color' => '#8B5CF6',
                'is_active' => true,
            ],
            [
                'name' => 'Corruption',
                'description' => 'Reports of bribery, kickbacks, or other corrupt practices',
                'color' => '#DC2626',
                'is_active' => true,
            ],
            [
                'name' => 'Quality Control',
                'description' => 'Reports of product defects, quality issues, or manufacturing problems',
                'color' => '#7C3AED',
                'is_active' => true,
            ],
            [
                'name' => 'Regulatory Compliance',
                'description' => 'Reports of regulatory violations or compliance failures',
                'color' => '#059669',
                'is_active' => true,
            ],
            [
                'name' => 'Conflict of Interest',
                'description' => 'Reports of conflicts of interest or ethical violations',
                'color' => '#D97706',
                'is_active' => true,
            ],
            [
                'name' => 'Other',
                'description' => 'Other types of reports not covered by specific categories',
                'color' => '#6B7280',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $data) {
            $base = Str::slug($data['name']);
            $slug = $base;
            $i = 1;

            // ensure unique slug
            while (DB::table('categories')->where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }

            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name'        => $data['name'],
                    'description' => $data['description'],
                    'color'       => $data['color'],
                    'is_active'   => $data['is_active'],
                ]
            );
        }
    }
}
