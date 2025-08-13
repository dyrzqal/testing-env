<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use App\Models\ReportAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicReportController extends Controller
{
    public function index()
    {
        $categories = Category::active()->ordered()->get();
        return view('public.report.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('public.report.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'incident_location' => 'nullable|string|max:255',
            'incident_date' => 'nullable|date|before_or_equal:today',
            'incident_time' => 'nullable|date_format:H:i',
            'persons_involved' => 'nullable|array',
            'persons_involved.*' => 'string|max:255',
            'evidence_description' => 'nullable|string',
            'urgency_level' => 'required|in:low,medium,high,critical',
            'is_anonymous' => 'boolean',
            'reporter_name' => 'nullable|required_if:is_anonymous,false|string|max:255',
            'reporter_email' => 'nullable|required_if:is_anonymous,false|email|max:255',
            'reporter_phone' => 'nullable|string|max:20',
            'reporter_department' => 'nullable|string|max:255',
            'reporter_contact_preference' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,gif,mp4,avi,mov,mp3,wav'
        ]);

        // Create the report
        $report = Report::create([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'incident_location' => $validated['incident_location'] ?? null,
            'incident_date' => $validated['incident_date'] ?? null,
            'incident_time' => $validated['incident_time'] ?? null,
            'persons_involved' => $validated['persons_involved'] ?? null,
            'evidence_description' => $validated['evidence_description'] ?? null,
            'urgency_level' => $validated['urgency_level'],
            'is_anonymous' => $request->boolean('is_anonymous', true),
            'reporter_name' => $validated['reporter_name'] ?? null,
            'reporter_email' => $validated['reporter_email'] ?? null,
            'reporter_phone' => $validated['reporter_phone'] ?? null,
            'reporter_department' => $validated['reporter_department'] ?? null,
            'reporter_contact_preference' => $validated['reporter_contact_preference'] ?? null,
            'submitted_at' => now(),
        ]);

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $this->storeAttachment($report, $file);
            }
        }

        return redirect()->route('public.report.success', $report->reference_number)
            ->with('success', 'Your report has been submitted successfully.');
    }

    public function success(string $referenceNumber)
    {
        $report = Report::where('reference_number', $referenceNumber)->firstOrFail();
        return view('public.report.success', compact('report'));
    }

    public function track()
    {
        return view('public.report.track');
    }

    public function trackReport(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string'
        ]);

        $report = Report::where('reference_number', $request->reference_number)->first();

        if (!$report) {
            return back()->with('error', 'Report not found. Please check your reference number.');
        }

        // Only show limited information for tracking
        $trackingData = [
            'reference_number' => $report->reference_number,
            'title' => $report->title,
            'category' => $report->category->name,
            'status' => $report->status,
            'submitted_at' => $report->submitted_at,
            'last_updated' => $report->updated_at,
        ];

        // Get public comments if any
        $publicComments = $report->comments()
            ->visibleToReporter()
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('public.report.status', compact('trackingData', 'publicComments'));
    }

    private function storeAttachment(Report $report, $file)
    {
        $originalName = $file->getClientOriginalName();
        $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $filePath = 'reports/' . $report->id . '/' . $fileName;
        
        // Store file
        $file->storeAs('reports/' . $report->id, $fileName, 'private');

        // Determine attachment type
        $mimeType = $file->getMimeType();
        $attachmentType = $this->getAttachmentType($mimeType);

        // Create attachment record
        ReportAttachment::create([
            'report_id' => $report->id,
            'original_name' => $originalName,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'mime_type' => $mimeType,
            'file_size' => $file->getSize(),
            'file_hash' => hash_file('sha256', $file->getRealPath()),
            'attachment_type' => $attachmentType,
            'is_evidence' => true,
        ]);
    }

    private function getAttachmentType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } elseif (in_array($mimeType, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            return 'document';
        } else {
            return 'other';
        }
    }
}
