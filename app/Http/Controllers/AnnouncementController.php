<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->get();
        return view('admin.announcements', compact('announcements'));
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'title_en' => 'required|string|max:200',
                'title_sw' => 'required|string|max:200',
                'content_en' => 'required|string',
                'content_sw' => 'required|string',
                'priority' => 'required|in:normal,high,urgent',
            ]);

            $announcement = Announcement::create([
                'title_en' => $request->title_en,
                'title_sw' => $request->title_sw,
                'content_en' => $request->content_en,
                'content_sw' => $request->content_sw,
                'priority' => $request->priority,
                'is_published' => $request->has('is_published'),
                'created_by' => Auth::id(),
                'published_at' => $request->has('is_published') ? Carbon::now() : null,
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'ANNOUNCEMENT_CREATED',
                'details' => "Announcement created: {$announcement->title_en}",
                'ip_address' => $request->ip(),
                'device_info' => $request->userAgent(),
                'timestamp' => Carbon::now(),
            ]);

            return redirect()->route('admin.announcements')->with('success', 'Announcement created successfully.');
        }

        return view('admin.announcement_form');
    }

    public function edit(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        if ($request->isMethod('post')) {
            $request->validate([
                'title_en' => 'required|string|max:200',
                'title_sw' => 'required|string|max:200',
                'content_en' => 'required|string',
                'content_sw' => 'required|string',
                'priority' => 'required|in:normal,high,urgent',
            ]);

            $announcement->title_en = $request->title_en;
            $announcement->title_sw = $request->title_sw;
            $announcement->content_en = $request->content_en;
            $announcement->content_sw = $request->content_sw;
            $announcement->priority = $request->priority;
            $announcement->is_published = $request->has('is_published');
            $announcement->published_at = $request->has('is_published') ? ($announcement->published_at ?? Carbon::now()) : null;
            $announcement->save();

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'ANNOUNCEMENT_UPDATED',
                'details' => "Announcement updated: {$announcement->title_en}",
                'ip_address' => $request->ip(),
                'device_info' => $request->userAgent(),
                'timestamp' => Carbon::now(),
            ]);

            return redirect()->route('admin.announcements')->with('success', 'Announcement updated successfully.');
        }

        return view('admin.announcement_form', compact('announcement'));
    }

    public function publish($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->is_published = true;
        $announcement->published_at = Carbon::now();
        $announcement->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'ANNOUNCEMENT_PUBLISHED',
            'details' => "Announcement published: {$announcement->title_en}",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.announcements')->with('success', 'Announcement published successfully.');
    }

    public function unpublish($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->is_published = false;
        $announcement->published_at = null;
        $announcement->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'ANNOUNCEMENT_UNPUBLISHED',
            'details' => "Announcement unpublished: {$announcement->title_en}",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.announcements')->with('success', 'Announcement unpublished successfully.');
    }

    public function delete($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'ANNOUNCEMENT_DELETED',
            'details' => "Announcement deleted: {$announcement->title_en}",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.announcements')->with('success', 'Announcement deleted successfully.');
    }
}
