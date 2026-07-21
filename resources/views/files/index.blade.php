<x-app-layout>
    <x-slot name="header">File Manager</x-slot>

    <!-- Upload Modal -->
    <div id="uploadModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-weight: 700; font-size: 1.125rem;">Unggah File</h3>
                <button onclick="closeUploadModal()" style="background: transparent; border: none; color: var(--text-muted); cursor: pointer; padding: 0.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="upload-zone" id="dropZone" onclick="document.getElementById('fileInput').click();">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--accent-blue)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    <p style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem;">Klik atau seret file ke sini</p>
                    <p style="color: var(--text-muted); font-size: 0.8125rem;">Maksimal 100MB per file</p>
                    <div id="fileNames" style="margin-top: 1rem; font-size: 0.8125rem; color: var(--accent-emerald);"></div>
                </div>
                <input type="file" name="files[]" id="fileInput" multiple style="display: none;" onchange="showFileNames(this)">

                @error('files.*')
                    <p style="color: var(--accent-rose); font-size: 0.8125rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-ghost" onclick="closeUploadModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        Unggah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(244, 63, 94, 0.15); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--accent-rose)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </div>
                <h3 style="font-weight: 700; font-size: 1.125rem; margin-bottom: 0.5rem;">Hapus File?</h3>
                <p style="font-size: 0.875rem; color: var(--text-muted);">File <strong id="deleteFileName" style="color: var(--text-primary);"></strong> akan dihapus permanen.</p>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display: flex; justify-content: center; gap: 0.75rem;">
                    <button type="button" class="btn btn-ghost" onclick="closeDeleteModal()">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        Hapus Permanen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Header Actions -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary);">File Manager</h2>
            <p style="font-size: 0.875rem; color: var(--text-muted); margin-top: 0.25rem;">
                @if(Auth::user()->hasRole('admin'))
                    Kelola semua file yang diunggah pengguna
                @else
                    Kelola file milik Anda
                @endif
            </p>
        </div>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <!-- Search -->
            <form method="GET" action="{{ route('files.index') }}" style="display: flex; gap: 0.5rem;">
                <input type="text" name="search" placeholder="Cari file..." value="{{ request('search') }}" class="form-input" style="width: 240px;">
                <button type="submit" class="btn btn-ghost btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
            </form>
            <button class="btn btn-primary" onclick="openUploadModal()" id="btn-upload">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Unggah File
            </button>
        </div>
    </div>

    <!-- Files Table -->
    <div class="card" style="padding: 0; overflow: hidden;">
        @if($files->isEmpty())
            <div style="text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1.5rem; opacity: 0.5;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><polyline points="14 2 14 8 20 8"/></svg>
                <h3 style="font-weight: 700; font-size: 1.125rem; color: var(--text-primary); margin-bottom: 0.5rem;">Belum Ada File</h3>
                <p style="margin-bottom: 1.5rem;">Mulai unggah file pertama Anda</p>
                <button class="btn btn-primary" onclick="openUploadModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Unggah File Pertama
                </button>
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>File</th>
                        @if(Auth::user()->hasRole('admin'))
                        <th>Diunggah Oleh</th>
                        @endif
                        <th>Ukuran</th>
                        <th>Tanggal Upload</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($files as $file)
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
                                <div class="file-icon {{ $iconClass }}">{{ strtoupper(Str::limit($ext, 4, '')) }}</div>
                                <div>
                                    <div style="color: var(--text-primary); font-weight: 500;">{{ Str::limit($file->original_name, 40) }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $file->mime_type }}</div>
                                </div>
                            </div>
                        </td>
                        @if(Auth::user()->hasRole('admin'))
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div class="user-avatar" style="width: 24px; height: 24px; font-size: 0.625rem;">{{ strtoupper(substr($file->user->name, 0, 1)) }}</div>
                                {{ $file->user->name }}
                            </div>
                        </td>
                        @endif
                        <td>{{ $file->human_size }}</td>
                        <td>{{ $file->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                <a href="{{ route('files.download', $file) }}" class="btn btn-ghost btn-sm" title="Download">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete('{{ route('files.destroy', $file) }}', '{{ $file->original_name }}')" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            @if($files->hasPages())
            <div class="pagination-wrapper" style="padding: 1rem; border-top: 1px solid var(--border-color);">
                {{ $files->links() }}
            </div>
            @endif
        @endif
    </div>

    <script>
        function openUploadModal() {
            document.getElementById('uploadModal').style.display = 'flex';
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').style.display = 'none';
            document.getElementById('fileInput').value = '';
            document.getElementById('fileNames').innerHTML = '';
        }

        function showFileNames(input) {
            const container = document.getElementById('fileNames');
            if (input.files.length > 0) {
                let names = Array.from(input.files).map(f => `✓ ${f.name} (${(f.size / 1024 / 1024).toFixed(2)} MB)`);
                container.innerHTML = names.join('<br>');
            } else {
                container.innerHTML = '';
            }
        }

        function confirmDelete(url, fileName) {
            document.getElementById('deleteForm').action = url;
            document.getElementById('deleteFileName').textContent = fileName;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Drag and drop
        const dropZone = document.getElementById('dropZone');
        if (dropZone) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropZone.classList.add('dragover');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropZone.classList.remove('dragover');
                });
            });

            dropZone.addEventListener('drop', (e) => {
                const fileInput = document.getElementById('fileInput');
                fileInput.files = e.dataTransfer.files;
                showFileNames(fileInput);
            });
        }

        // Close modals on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeUploadModal();
                closeDeleteModal();
            }
        });
    </script>
</x-app-layout>
