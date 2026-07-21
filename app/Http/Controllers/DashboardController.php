<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get disk space for the drive where Laravel is installed
        $drivePath = base_path();
        // On Windows, get the drive letter (e.g., E:\)
        $driveLetter = strtoupper(substr($drivePath, 0, 3));

        $totalDisk = disk_total_space($driveLetter);
        $freeDisk = disk_free_space($driveLetter);
        $usedDisk = $totalDisk - $freeDisk;

        // Get uploaded files stats
        $totalFiles = File::count();
        $totalUploadSize = File::sum('file_size');
        $totalUsers = User::count();

        // Recent uploads (last 10)
        $recentFiles = File::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Per-user storage usage (top 5)
        $userStorageUsage = User::withSum('files as total_size', 'file_size')
            ->withCount('files')
            ->orderByDesc('total_size')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalDisk',
            'freeDisk',
            'usedDisk',
            'totalFiles',
            'totalUploadSize',
            'totalUsers',
            'recentFiles',
            'userStorageUsage',
            'driveLetter'
        ));
    }
}
