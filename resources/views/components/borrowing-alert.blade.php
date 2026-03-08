<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(Auth::user()->role === 'admin' || Auth::user()->role === 'petugas')
    <script>
        // Approve Borrowing with SweetAlert
        function confirmApprove(borrowingId, bookTitle, borrowerName) {
            Swal.fire({
                title: 'Approve Borrowing?',
                text: `Approve borrowing request for "${bookTitle}" by ${borrowerName}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Approve',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/borrowings/${borrowingId}/approve`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'PATCH';
                    form.appendChild(methodField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Approve Return with SweetAlert
        function confirmApproveReturn(borrowingId, bookTitle, borrowerName) {
            Swal.fire({
                title: 'Approve Return?',
                text: `Approve return request for "${bookTitle}" from ${borrowerName}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Approve',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/borrowings/${borrowingId}/approve-return`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'PATCH';
                    form.appendChild(methodField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Return Book with SweetAlert
        function openReturnModal(borrowingId, bookTitle, borrowerName) {
            Swal.fire({
                title: 'Return Book',
                text: `Return "${bookTitle}" from ${borrowerName}?`,
                icon: 'warning',
                input: 'select',
                inputLabel: 'Return Reason',
                inputOptions: {
                    'normal': 'Normal Return',
                    'late_return': 'Late Return',
                    'book_damaged': 'Book Damaged',
                    'book_lost': 'Book Lost',
                    'user_missing': 'User Missing'
                },
                inputPlaceholder: 'Select reason',
                showCancelButton: true,
                confirmButtonText: 'Confirm Return',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: '#64748b',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Please select a return reason'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/borrowings/${borrowingId}/return`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'PATCH';
                    form.appendChild(methodField);
                    
                    const reasonField = document.createElement('input');
                    reasonField.type = 'hidden';
                    reasonField.name = 'return_reason';
                    reasonField.value = result.value;
                    form.appendChild(reasonField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Reject Borrowing with SweetAlert
        function openRejectModal(borrowingId, bookTitle, borrowerName) {
            Swal.fire({
                title: 'Reject Borrowing?',
                text: `Reject borrowing request for "${bookTitle}" by ${borrowerName}?`,
                icon: 'warning',
                input: 'textarea',
                inputLabel: 'Rejection Reason',
                inputPlaceholder: 'Enter reason for rejection...',
                inputAttributes: {
                    'aria-label': 'Enter reason for rejection'
                },
                showCancelButton: true,
                confirmButtonText: 'Yes, Reject',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Rejection reason is required'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/borrowings/${borrowingId}/reject`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'PATCH';
                    form.appendChild(methodField);
                    
                    const reasonField = document.createElement('input');
                    reasonField.type = 'hidden';
                    reasonField.name = 'reject_reason';
                    reasonField.value = result.value;
                    form.appendChild(reasonField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Show success/error messages with SweetAlert
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#8b5cf6',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@endif

@if(Auth::user()->role === 'user' || Auth::user()->role === 'petugas' || Auth::user()->role === 'admin')
    <script>
        // Hide History with SweetAlert (User/Petugas/Admin bisa hide history mereka sendiri)
        function confirmDeleteHistory(borrowingId, bookTitle) {
            Swal.fire({
                title: 'Hide History?',
                html: `Hide "<strong>${bookTitle}</strong>" from your borrowing history?<br><small class="text-gray-600">This won't delete the actual record from database.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Hide',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#f97316',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/borrowings/${borrowingId}/hide`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endif

@if(Auth::user()->role === 'admin')
    <script>
        // Permanent Delete with SweetAlert (Admin Only)
        function confirmPermanentDelete(borrowingId, bookTitle, borrowerName) {
            Swal.fire({
                title: 'Permanent Delete?',
                html: `<strong class="text-red-600">WARNING!</strong> This will permanently delete the borrowing record for "<strong>${bookTitle}</strong>" by <strong>${borrowerName}</strong> from the database.<br><br><small class="text-gray-600">This action cannot be undone!</small>`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete Permanently',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                input: 'checkbox',
                inputPlaceholder: 'I understand this action is permanent',
                inputValidator: (result) => {
                    return !result && 'You need to confirm this action'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/borrowings/${borrowingId}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endif
