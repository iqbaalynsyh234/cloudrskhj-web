<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Display a listing of files.
     */
    public function index(Request $request)
    {
        $query = File::with('user');

        // Admin sees all files, regular user sees only their own
        if (!Auth::user()->hasRole('admin')) {
            $query->where('user_id', Auth::id());
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('original_name', 'like', '%' . $request->search . '%');
        }

        $files = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('files.index', compact('files'));
    }

    /**
     * Upload files.
     */
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'file|max:102400', // max 100MB per file
        ]);

        $uploadedCount = 0;

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $uploadedFile) {
                $originalName = $uploadedFile->getClientOriginalName();
                $fileName = Str::uuid() . '.' . $uploadedFile->getClientOriginalExtension();
                $filePath = $uploadedFile->storeAs('uploads/' . Auth::id(), $fileName, 'public');

                File::create([
                    'user_id' => Auth::id(),
                    'file_name' => $fileName,
                    'original_name' => $originalName,
                    'file_path' => $filePath,
                    'file_size' => $uploadedFile->getSize(),
                    'mime_type' => $uploadedFile->getMimeType(),
                ]);

                $uploadedCount++;
            }
        }

        return redirect()->route('files.index')
            ->with('success', $uploadedCount . ' file berhasil diunggah!');
    }

    /**
     * Download a file.
     */
    public function download(File $file)
    {
        // Check permission: user can only download their own files unless admin
        if (!Auth::user()->hasRole('admin') && $file->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        $path = Storage::disk('public')->path($file->file_path);

        if (!file_exists($path)) {
            return redirect()->route('files.index')
                ->with('error', 'File tidak ditemukan di server.');
        }

        return response()->download($path, $file->original_name);
    }

    /**
     * Delete a file.
     */
    public function destroy(File $file)
    {
        // Check permission
        if (!Auth::user()->hasRole('admin') && $file->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus file ini.');
        }

        // Delete physical file
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return redirect()->route('files.index')
            ->with('success', 'File berhasil dihapus!');
    }
}
