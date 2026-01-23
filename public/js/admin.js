// Minimal admin.js - hanya untuk UI interactions
// Database logic sudah dipindahkan ke Controllers

// Handle modal toggle
function openAddRoomModal() {
    document.getElementById('roomForm').action = "{{ route('rooms.store') }}";
    document.getElementById('roomForm').reset();
    document.getElementById('roomModalTitle').textContent = 'Tambah Kamar Baru';
    document.getElementById('roomModal').classList.remove('hidden');
}

function closeRoomModal() {
    document.getElementById('roomModal').classList.add('hidden');
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}

// Confirm delete
function confirmDelete(type, id) {
    if (confirm(`Apakah Anda yakin ingin menghapus ${type} ini?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = type === 'room' ? `/admin/rooms/${id}` : `/admin/bookings/${id}`;
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrfInput);
        
        // Add method override for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Section navigation
document.querySelectorAll('.admin-sidebar a').forEach(link => {
    link.addEventListener('click', function(e) {
        // Let links work naturally for server-side routing
        document.querySelectorAll('.admin-sidebar a').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
    });
});

console.log('Admin dashboard loaded - Database connected');
