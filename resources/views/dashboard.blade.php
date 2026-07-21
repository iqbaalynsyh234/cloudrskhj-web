<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    @php
        $diskPercent = $totalDisk > 0 ? round(($usedDisk / $totalDisk) * 100, 1) : 0;
        $uploadPercent = $totalDisk > 0 ? round(($totalUploadSize / $totalDisk) * 100, 2) : 0;

        function formatBytes($bytes, $precision = 2) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $i = 0;
            while ($bytes >= 1024 && $i < count($units) - 1) {
                $bytes /= 1024;
                $i++;
            }
            return round($bytes, $precision) . ' ' . $units[$i];
        }
    @endphp

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
        <!-- Server Storage Card -->
        <div class="card card-glow-blue">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-label">Storage Server ({{ $driveLetter }})</div>
                    <div class="stat-value" style="color: var(--accent-blue);">{{ formatBytes($usedDisk) }}</div>
                    <div class="stat-label">dari {{ formatBytes($totalDisk) }}</div>
                </div>
                <div class="stat-icon stat-icon-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
                    </svg>
                </div>
            </div>
            <div style="margin-top: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.375rem;">
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Terpakai</span>
                    <span style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary);">{{ $diskPercent }}%</span>
                </div>
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill {{ $diskPercent > 85 ? 'warning' : '' }}" style="width: {{ $diskPercent }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Free Space -->
        <div class="card card-glow-emerald">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-label">Sisa Tersedia</div>
                    <div class="stat-value" style="color: var(--accent-emerald);">{{ formatBytes($freeDisk) }}</div>
                    <div class="stat-label">{{ round(100 - $diskPercent, 1) }}% tersedia</div>
                </div>
                <div class="stat-icon stat-icon-emerald">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Files -->
        <div class="card card-glow-violet">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-label">Total File Diunggah</div>
                    <div class="stat-value" style="color: var(--accent-violet);">{{ number_format($totalFiles) }}</div>
                    <div class="stat-label">{{ formatBytes($totalUploadSize) }} total</div>
                </div>
                <div class="stat-icon stat-icon-violet">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="card card-glow-amber">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-label">Total Pengguna</div>
                    <div class="stat-value" style="color: var(--accent-amber);">{{ $totalUsers }}</div>
                    <div class="stat-label">pengguna terdaftar</div>
                </div>
                <div class="stat-icon stat-icon-amber">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Two-column layout -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
        <!-- Recent Files -->
        <div class="card" style="grid-column: 1 / -1;" id="recent-files">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <div>
                    <h3 style="font-weight: 700; font-size: 1rem; color: var(--text-primary);">File Terbaru</h3>
                    <p style="font-size: 0.8125rem; color: var(--text-muted); margin-top: 0.25rem;">10 file terakhir yang diunggah</p>
                </div>
                <a href="{{ route('files.index') }}" class="btn btn-ghost btn-sm">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>

            @if($recentFiles->isEmpty())
                <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <p>Belum ada file yang diunggah</p>
                </div>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>Pengunggah</th>
                            <th>Ukuran</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentFiles as $file)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @php
                                        $ext = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                                        $iconClass = 'file-icon-default';
                                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) $iconClass = 'file-icon-image';
                                        elseif ($ext === 'pdf') $iconClass = 'file-icon-pdf';
                                        elseif (in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])) $iconClass = 'file-icon-doc';
                                    @endphp
                                    <div class="file-icon {{ $iconClass }}">{{ strtoupper($ext) }}</div>
                                    <div>
                                        <div style="color: var(--text-primary); font-weight: 500;">{{ Str::limit($file->original_name, 30) }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $file->mime_type }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $file->user->name }}</td>
                            <td>{{ $file->human_size }}</td>
                            <td>{{ $file->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- User Storage Usage -->
        @if(Auth::user()->hasRole('admin') && $userStorageUsage->count() > 0)
        <div class="card" style="grid-column: 1 / -1;" id="user-storage">
            <div style="margin-bottom: 1.25rem;">
                <h3 style="font-weight: 700; font-size: 1rem; color: var(--text-primary);">Penggunaan Storage per Pengguna</h3>
                <p style="font-size: 0.8125rem; color: var(--text-muted); margin-top: 0.25rem;">Top 5 pengguna dengan penggunaan storage terbanyak</p>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($userStorageUsage as $userStat)
                    @php
                        $userPercent = $totalUploadSize > 0 ? round(($userStat->total_size / $totalUploadSize) * 100, 1) : 0;
                        $colors = ['var(--accent-blue)', 'var(--accent-violet)', 'var(--accent-emerald)', 'var(--accent-amber)', 'var(--accent-rose)'];
                        $color = $colors[$loop->index % count($colors)];
                    @endphp
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.375rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div class="user-avatar" style="width: 28px; height: 28px; font-size: 0.6875rem;">{{ strtoupper(substr($userStat->name, 0, 1)) }}</div>
                                <span style="font-size: 0.875rem; font-weight: 500; color: var(--text-primary);">{{ $userStat->name }}</span>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $userStat->files_count }} file</span>
                            </div>
                            <span style="font-size: 0.8125rem; font-weight: 600; color: var(--text-secondary);">{{ formatBytes($userStat->total_size ?? 0) }}</span>
                        </div>
                        <div class="progress-bar-bg" style="height: 8px;">
                            <div style="height: 100%; border-radius: 999px; width: {{ $userPercent }}%; background: {{ $color }}; transition: width 1s ease;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
