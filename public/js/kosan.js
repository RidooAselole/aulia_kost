// Kosan Website JavaScript
// Global variables
let roomsData = [];
let currentHouseNumber = null;
const price = 600000; // Harga per bulan
const facilities = [
    'Kamar mandi luar',
    'WiFi gratis',
    'Kasur dan lemari',
    'Listrik dan air',
    'Dapur bersama',
    'Parkir motor'
];

// House configuration
const houseConfig = {
    '105': { totalRooms: 10, name: 'No. 105' },
    '121': { totalRooms: 6, name: 'No. 121' }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const roomModal = document.getElementById('roomModal');
    const modalBody = document.getElementById('modalBody');
    const closeModal = document.querySelector('.close');
    const bookingModal = document.getElementById('bookingModal');
    const bookingForm = document.getElementById('bookingForm');
    const closeBookingModal = document.querySelector('.close-booking');
    const selectedRoomNumberInput = document.getElementById('selectedRoomNumber');
    
    // WhatsApp number (format: 6281234567890 tanpa + dan -)
    const whatsappNumber = '+62 812-2328-8620';

    // Event listeners
    closeModal.addEventListener('click', closeModalFunc);

    window.addEventListener('click', function(event) {
        if (event.target === roomModal) {
            closeModalFunc();
        }
    });

    // Initialize house selection on page load
    backToHouseSelection();

    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Close modal function
    function closeModalFunc() {
        roomModal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Format price to Indonesian Rupiah
    window.formatPrice = function(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    };

    // Create room card element
    window.createRoomCard = function(room) {
        const card = document.createElement('div');
        card.className = 'room-card';

        const statusClass = room.status === 'available' ? 'status-available' : 'status-occupied';
        const statusText = room.status === 'available' ? 'Tersedia' : 'Terisi';

        card.innerHTML = `
            <div class="room-image">🏠</div>
            <div class="room-info">
                <div class="room-number">Kamar ${room.number}</div>
                <span class="room-status ${statusClass}">${statusText}</span>
                <div class="room-price">Rp ${formatPrice(room.price)}/bulan</div>
                <ul class="room-features">
                    ${room.facilities.slice(0, 3).map(facility => `<li>✓ ${facility}</li>`).join('')}
                </ul>
                <button class="room-btn">Lihat Detail</button>
            </div>
        `;

        // Add click event only to the button
        const button = card.querySelector('.room-btn');
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            openModal(room);
        });

        return card;
    };

    // Open modal with room details
    window.openModal = function(room) {
        const statusClass = room.status === 'available' ? 'status-available' : 'status-occupied';
        const statusText = room.status === 'available' ? 'Tersedia' : 'Terisi';

        modalBody.innerHTML = `
            <h2>Detail Kamar ${room.number}</h2>
            <p><strong>Status:</strong> <span class="room-status ${statusClass}">${statusText}</span></p>
            <p><strong>Harga:</strong> Rp ${formatPrice(room.price)} per bulan</p>
            <p><strong>Ukuran:</strong> 3x4 meter</p>
            <h3>Fasilitas:</h3>
            <ul class="modal-features">
                ${room.facilities.map(facility => `<li>✓ ${facility}</li>`).join('')}
            </ul>
            <p><strong>Deskripsi:</strong> Kamar nyaman dengan kualitas terjamin, dilengkapi dengan semua fasilitas yang dibutuhkan untuk kenyamanan Anda. Semua kamar memiliki standar kualitas yang sama.</p>
            ${room.status === 'available' ? `
                <button class="modal-btn" onclick="handleBooking('${room.number}')">Pesan Sekarang</button>
            ` : `
                <button class="modal-btn" style="background-color: #9ca3af; cursor: not-allowed;" disabled>Kamar Tidak Tersedia</button>
            `}
        `;

        roomModal.classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    // Handle booking - show booking form
    window.handleBooking = function(roomNumber) {
        closeModalFunc();
        selectedRoomNumberInput.value = roomNumber;
        openBookingModal();
    };

    // Open booking form modal
    function openBookingModal() {
        bookingModal.classList.add('show');
        document.body.style.overflow = 'hidden';
        // Reset form
        bookingForm.reset();
        clearErrors();
    }

    // Close booking modal
    function closeBookingModalFunc() {
        bookingModal.classList.remove('show');
        document.body.style.overflow = 'auto';
        bookingForm.reset();
        clearErrors();
    }

    // Clear error messages
    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => {
            el.textContent = '';
            el.style.display = 'none';
        });
        document.querySelectorAll('.form-group input').forEach(el => {
            el.classList.remove('error');
        });
    }

    // Show error message
    function showError(fieldId, message) {
        const errorElement = document.getElementById(fieldId + 'Error');
        const inputElement = document.getElementById(fieldId);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
        if (inputElement) {
            inputElement.classList.add('error');
        }
    }

    // Validate form
    function validateForm(formData) {
        let isValid = true;
        clearErrors();

        // Validate full name
        if (!formData.fullName || formData.fullName.trim().length < 3) {
            showError('fullName', 'Nama lengkap minimal 3 karakter');
            isValid = false;
        }

        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!formData.email || !emailRegex.test(formData.email)) {
            showError('email', 'Format email tidak valid');
            isValid = false;
        }

        // Validate phone
        const phoneRegex = /^[0-9]{10,13}$/;
        const cleanPhone = formData.phone.replace(/\D/g, '');
        if (!cleanPhone || !phoneRegex.test(cleanPhone)) {
            showError('phone', 'Nomor telepon harus 10-13 digit angka');
            isValid = false;
        }

        return isValid;
    }

    // Handle booking form submit
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            fullName: document.getElementById('fullName').value.trim(),
            email: document.getElementById('email').value.trim(),
            phone: document.getElementById('phone').value.trim(),
            roomNumber: selectedRoomNumberInput.value
        };

        if (!validateForm(formData)) {
            return;
        }

        // Get room data
        const room = roomsData.find(r => r.number === formData.roomNumber);
        const roomPrice = room ? formatPrice(room.price) : '';

        // Create WhatsApp message
        const message = encodeURIComponent(
            `Halo, saya ingin memesan kamar dengan detail sebagai berikut:\n\n` +
            `📋 *Data Pribadi:*\n` +
            `Nama Lengkap: ${formData.fullName}\n` +
            `Email: ${formData.email}\n` +
            `Nomor Telepon: ${formData.phone}\n\n` +
            `🏠 *Detail Pemesanan:*\n` +
            `Kamar: ${formData.roomNumber}\n` +
            `Harga: Rp ${roomPrice} per bulan\n\n` +
            `Saya tertarik untuk memesan kamar ini. Terima kasih.`
        );

        // Redirect to WhatsApp
        const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${message}`;
        window.open(whatsappUrl, '_blank');
        
        // Close modal after a short delay
        setTimeout(() => {
            closeBookingModalFunc();
        }, 500);
    });

    // Event listeners for booking modal
    closeBookingModal.addEventListener('click', closeBookingModalFunc);

    window.addEventListener('click', function(event) {
        if (event.target === bookingModal) {
            closeBookingModalFunc();
        }
    });

    // Remove error styling on input
    document.addEventListener('input', function(e) {
        if (e.target.matches('#bookingForm input')) {
            e.target.classList.remove('error');
            const errorId = e.target.id + 'Error';
            const errorElement = document.getElementById(errorId);
            if (errorElement) {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }
        }
    });
});

// Generate rooms for specific house
function generateRoomsForHouse(houseNumber) {
    const config = houseConfig[houseNumber];
    if (!config) return [];

    const rooms = [];
    for (let i = 1; i <= config.totalRooms; i++) {
        rooms.push({
            number: `${houseNumber}-${i.toString().padStart(2, '0')}`,
            price: price,
            status: Math.random() > 0.3 ? 'available' : 'occupied', // 70% available
            facilities: facilities
        });
    }
    return rooms;
}

// Render rooms for selected house
window.renderRooms = function(houseNumber) {
    currentHouseNumber = houseNumber;
    const config = houseConfig[houseNumber];
    
    if (!config) {
        console.error('House number not found:', houseNumber);
        return;
    }

    // Generate rooms for this house
    roomsData = generateRoomsForHouse(houseNumber);

    // Get container
    const roomsSection = document.querySelector('#rooms .container');
    if (!roomsSection) return;

    // Create rooms grid HTML
    const roomsGridHTML = `
        <div id="view-rooms-list">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2 class="section-title" style="margin: 0;">Kost ${config.name}</h2>
                <button onclick="backToHouseSelection()" class="hero-btn" style="padding: 0.75rem 1.5rem; font-size: 0.95rem;">
                    ← Kembali
                </button>
            </div>
            <div class="rooms-grid" id="roomsGrid" data-house="${houseNumber}">
                <!-- Rooms will be inserted here -->
            </div>
        </div>
    `;

    // Replace content
    roomsSection.innerHTML = roomsGridHTML;

    // Get rooms grid
    const roomsGrid = document.getElementById('roomsGrid');
    if (!roomsGrid) return;

    // Render room cards
    roomsGrid.innerHTML = '';
    roomsData.forEach(room => {
        const roomCard = createRoomCard(room);
        roomsGrid.appendChild(roomCard);
    });

    // Smooth scroll to rooms section
    document.getElementById('rooms').scrollIntoView({ behavior: 'smooth', block: 'start' });
};

// Back to house selection
window.backToHouseSelection = function() {
    const roomsSection = document.querySelector('#rooms .container');
    if (!roomsSection) return;

    roomsSection.innerHTML = `
        <div id="view-house-selection">
            <h2 class="section-title">Pilih Unit Rumah</h2>
            <div class="house-container">
                <div class="house-card" onclick="renderRooms('105')">
                    <h2>🏠 No. 105</h2>
                    <p>10 Kamar (2 Lantai)</p>
                    <span class="hero-btn">Lihat Kamar</span>
                </div>
                <div class="house-card" data-house="121" onclick="renderRooms('121')">
                    <h2>🏠 No. 121</h2>
                    <p>6 Kamar (1 Lantai)</p>
                    <span class="hero-btn">Lihat Kamar</span>
                </div>
            </div> 
        </div>
    `;

    // Smooth scroll to rooms section
    document.getElementById('rooms').scrollIntoView({ behavior: 'smooth', block: 'start' });
};
