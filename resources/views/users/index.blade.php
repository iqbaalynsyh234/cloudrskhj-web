<x-app-layout>
    <x-slot name="header">Kelola Pengguna</x-slot>

    <!-- Delete User Confirmation Modal -->
    <div id="deleteUserModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(244, 63, 94, 0.15); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--accent-rose)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="17" y1="11" x2="23" y2="11"/></svg>
                </div>
                <h3 style="font-weight: 700; font-size: 1.125rem; margin-bottom: 0.5rem;">Hapus Pengguna?</h3>
                <p style="font-size: 0.875rem; color: var(--text-muted);">Pengguna <strong id="deleteUserName" style="color: var(--text-primary);"></strong> dan semua file-nya akan dihapus permanen.</p>
            </div>
            <form id="deleteUserForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display: flex; justify-content: center; gap: 0.75rem;">
                    <button type="button" class="btn btn-ghost" onclick="closeDeleteUserModal()">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        Hapus Permanen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Header -->
    <div style="margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary);">Kelola Pengguna</h2>
        <p style="font-size: 0.875rem; color: var(--text-muted); margin-top: 0.25rem;">Atur role dan akses pengguna di sistem</p>
    </div>

    <!-- Users Table -->
    <div class="card" style="padding: 0; overflow: hidden;">
        @if($users->isEmpty())
            <div style="text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
                <p>Belum ada pengguna</p>
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>File</th>
                        <th>Penggunaan Storage</th>
                        <th>Terdaftar</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <span style="font-weight: 500; color: var(--text-primary);">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <form method="POST" action="{{ route('users.updateRole', $user) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="form-select" onchange="this.form.submit()" {{ $user->id === Auth::id() ? 'disabled' : '' }}>
                                    <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ $user->hasRole('user') ? 'selected' : '' }}>User</option>
                                </select>
                            </form>
                        </td>
                        <td>{{ $user->files_count }} file</td>
                        <td>
                            @php
                                $userSize = $user->total_size ?? 0;
                                $units = ['B', 'KB', 'MB', 'GB'];
                                $i = 0;
                                $size = $userSize;
                                while ($size >= 1024 && $i < count($units) - 1) {
                                    $size /= 1024;
                                    $i++;
                                }
                            @endphp
                            {{ round($size, 2) }} {{ $units[$i] }}
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                @if($user->id !== Auth::id())
                                <button class="btn btn-danger btn-sm" onclick="confirmDeleteUser('{{ route('users.destroy', $user) }}', '{{ $user->name }}')" title="Hapus Pengguna">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                                @else
                                <span class="badge badge-admin" style="font-size: 0.6875rem;">Anda</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($users->hasPages())
            <div class="pagination-wrapper" style="padding: 1rem; border-top: 1px solid var(--border-color);">
                {{ $users->links() }}
            </div>
            @endif
        @endif
    </div>

    <script>
        function confirmDeleteUser(url, name) {
            document.getElementById('deleteUserForm').action = url;
            document.getElementById('deleteUserName').textContent = name;
            document.getElementById('deleteUserModal').style.display = 'flex';
        }

        function closeDeleteUserModal() {
            document.getElementById('deleteUserModal').style.display = 'none';
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDeleteUserModal();
        });
    </script>
</x-app-layout>
