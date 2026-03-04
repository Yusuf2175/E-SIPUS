{{-- Approve Borrowing Modal --}}
<div id="approveBorrowingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-xl font-bold text-slate-800 mb-4">Approve Borrowing Request</h3>
        <p class="text-slate-600 mb-6">Are you sure you want to approve this borrowing request? The book stock will be decreased.</p>
        
        <form id="approveBorrowingForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModal('approveBorrowingModal')" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition">
                    Approve
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Reject Borrowing Modal --}}
<div id="rejectBorrowingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-xl font-bold text-slate-800 mb-4">Reject Borrowing Request</h3>
        <p class="text-slate-600 mb-4">Please provide a reason for rejecting this request:</p>
        
        <form id="rejectBorrowingForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <textarea name="rejection_reason" rows="4" class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Enter rejection reason..." required></textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModal('rejectBorrowingModal')" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition">
                    Reject
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Approve Return Modal --}}
<div id="approveReturnModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-xl font-bold text-slate-800 mb-4">Approve Return Request</h3>
        <p class="text-slate-600 mb-6">Are you sure you want to approve this return? The book stock will be increased.</p>
        
        <form id="approveReturnForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModal('approveReturnModal')" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition">
                    Approve Return
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Reject Return Modal --}}
<div id="rejectReturnModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-xl font-bold text-slate-800 mb-4">Reject Return Request</h3>
        <p class="text-slate-600 mb-4">Please provide a reason for rejecting this return:</p>
        
        <form id="rejectReturnForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <textarea name="return_rejection_reason" rows="4" class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Enter rejection reason..." required></textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModal('rejectReturnModal')" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition">
                    Reject Return
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openApproveModal(borrowingId) {
    const modal = document.getElementById('approveBorrowingModal');
    const form = document.getElementById('approveBorrowingForm');
    form.action = `/borrowings/${borrowingId}/approve`;
    modal.classList.remove('hidden');
}

function openRejectModal(borrowingId) {
    const modal = document.getElementById('rejectBorrowingModal');
    const form = document.getElementById('rejectBorrowingForm');
    form.action = `/borrowings/${borrowingId}/reject`;
    modal.classList.remove('hidden');
}

function openApproveReturnModal(borrowingId) {
    const modal = document.getElementById('approveReturnModal');
    const form = document.getElementById('approveReturnForm');
    form.action = `/borrowings/${borrowingId}/approve-return`;
    modal.classList.remove('hidden');
}

function openRejectReturnModal(borrowingId) {
    const modal = document.getElementById('rejectReturnModal');
    const form = document.getElementById('rejectReturnForm');
    form.action = `/borrowings/${borrowingId}/reject-return`;
    modal.classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modals = ['approveBorrowingModal', 'rejectBorrowingModal', 'approveReturnModal', 'rejectReturnModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
            closeModal(modalId);
        }
    });
});
</script>
