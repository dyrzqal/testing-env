<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportAttachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AttachmentController extends Controller
{
    /**
     * Display attachments for a report.
     */
    public function index(Request $request, Report $report): JsonResponse
    {
        $user = $request->user();
        
        // Role-based access control
        if ($user->role === 'investigator' && $report->assigned_to_user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $attachments = $report->attachments()
            ->select(['id', 'filename', 'filesize', 'mime_type', 'created_at'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['attachments' => $attachments]);
    }

    /**
     * Store new attachments.
     */
    public function store(Request $request, Report $report): JsonResponse
    {
        $request->validate([
            'attachments' => ['required', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:10240', 'mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif,mp4,avi,mov,zip,rar']
        ]);

        $user = $request->user();
        
        // Role-based access control
        if ($user->role === 'investigator' && $report->assigned_to_user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $uploadedAttachments = [];

        try {
            foreach ($request->file('attachments') as $file) {
                // Generate secure filename
                $extension = $file->getClientOriginalExtension();
                $secureFilename = Str::random(40) . '.' . $extension;
                $path = $file->storeAs('attachments', $secureFilename, 'private');

                // Scan file for malware (in a real app, you'd use a proper antivirus service)
                if ($this->containsMaliciousContent($file)) {
                    Storage::disk('private')->delete($path);
                    continue; // Skip this file
                }

                $attachment = ReportAttachment::create([
                    'report_id' => $report->id,
                    'filename' => $file->getClientOriginalName(),
                    'filepath' => $path,
                    'filesize' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);

                $uploadedAttachments[] = $attachment;
            }

            return response()->json([
                'message' => 'Attachments uploaded successfully',
                'attachments' => $uploadedAttachments
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to upload attachments',
                'message' => 'An error occurred while uploading files.'
            ], 500);
        }
    }

    /**
     * Download an attachment.
     */
    public function download(Request $request, Report $report, ReportAttachment $attachment): BinaryFileResponse|JsonResponse
    {
        $user = $request->user();
        
        // Role-based access control
        if ($user->role === 'investigator' && $report->assigned_to_user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($attachment->report_id !== $report->id) {
            return response()->json(['error' => 'Attachment not found'], 404);
        }

        if (!Storage::disk('private')->exists($attachment->filepath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $filePath = Storage::disk('private')->path($attachment->filepath);

        return response()->download($filePath, $attachment->filename);
    }

    /**
     * Delete an attachment.
     */
    public function destroy(Request $request, Report $report, ReportAttachment $attachment): JsonResponse
    {
        $user = $request->user();
        
        // Only admins can delete attachments
        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($attachment->report_id !== $report->id) {
            return response()->json(['error' => 'Attachment not found'], 404);
        }

        try {
            // Delete file from storage
            Storage::disk('private')->delete($attachment->filepath);
            
            // Delete database record
            $attachment->delete();

            return response()->json([
                'message' => 'Attachment deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete attachment',
                'message' => 'An error occurred while deleting the file.'
            ], 500);
        }
    }

    /**
     * Basic malware detection (placeholder for real implementation).
     */
    private function containsMaliciousContent($file): bool
    {
        // Basic file type validation
        $dangerousExtensions = ['exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (in_array($extension, $dangerousExtensions)) {
            return true;
        }

        // Check file signatures (magic numbers)
        $fileHandle = fopen($file->getPathname(), 'rb');
        $header = fread($fileHandle, 10);
        fclose($fileHandle);

        // Check for executable signatures
        $maliciousSignatures = [
            "\x4D\x5A", // PE/DOS executable
            "\x7F\x45\x4C\x46", // ELF executable
        ];

        foreach ($maliciousSignatures as $signature) {
            if (str_starts_with($header, $signature)) {
                return true;
            }
        }

        return false;
    }
}