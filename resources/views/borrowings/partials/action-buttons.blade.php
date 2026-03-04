{{-- Action Buttons --}}
<div class="flex gap-3 flex-wrap">
    {{-- View Book Button --}}
    <a href="{{ route('books.show', $borrowing->book) }}" class="group/btn">
        <div class="px-6 py-3 bg-ungu hover:bg-primarys text-white font-bold text-sm rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <span>View</span>
            </div>
        </div>
    </a>

    {{-- Admin/Petugas Actions --}}
    @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
        {{-- Approve/Reject Borrowing Buttons (if pending and user is the one who should approve) --}}
        @if($borrowing->approval_status === 'pending')
            @if(Auth::user()->isAdmin() || Auth::id() === $borrowing->should_be_approved_by)
                <button type="button" onclick="openApproveModal({{ $borrowing->id }})" class="group/btn">
                    <div class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-bold text-sm rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Approve</span>
                        </div>
                    </div>
                </button>
                <button type="button" onclick="openRejectModal({{ $borrowing->id }})" class="group/btn">
                    <div class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-bold text-sm rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Reject</span>
                        </div>
                    </div>
                </button>
            @else
                <div class="px-6 py-3 bg-gray-200 text-gray-600 font-bold text-sm rounded-2xl">
                    <span class="text-xs">Waiting for approval from {{ $borrowing->shouldBeApprovedBy->name ?? 'staff' }}</span>
                </div>
            @endif
        @endif

        {{-- Approve/Reject Return Buttons (if return pending and user is the one who should approve) --}}
        @if($borrowing->return_approval_status === 'pending')
            @if(Auth::user()->isAdmin() || Auth::id() === $borrowing->should_be_approved_by)
                <button type="button" onclick="openApproveReturnModal({{ $borrowing->id }})" class="group/btn">
                    <div class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-bold text-sm rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Approve Return</span>
                        </div>
                    </div>
                </button>
                <button type="button" onclick="openRejectReturnModal({{ $borrowing->id }})" class="group/btn">
                    <div class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-bold text-sm rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Reject Return</span>
                        </div>
                    </div>
                </button>
            @else
                <div class="px-6 py-3 bg-gray-200 text-gray-600 font-bold text-sm rounded-2xl">
                    <span class="text-xs">Waiting for return approval from {{ $borrowing->shouldBeApprovedBy->name ?? 'staff' }}</span>
                </div>
            @endif
        @endif

        {{-- Return Button (if borrowed and approved) --}}
        @if($borrowing->status === 'borrowed' && $borrowing->approval_status === 'approved' && !$borrowing->return_approval_status)
            <button type="button" onclick="openReturnModal({{ $borrowing->id }}, '{{ $borrowing->due_date->format('Y-m-d') }}')" class="group/btn">
                <div class="px-6 py-3 bg-red-400 hover:bg-red-500 text-white font-bold text-sm rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path>
                        </svg>
                        <span>Return</span>
                    </div>
                </div>
            </button>
        @endif
    @endif

    {{-- Delete Button (if returned) --}}
    @if($borrowing->status === 'returned' && $borrowing->return_approval_status === 'approved')
        @php
            $hasUnpaidPenalty = $borrowing->penalty_amount > 0 && !$borrowing->penalty_paid;
            $canDelete = !$hasUnpaidPenalty || Auth::user()->isAdmin() || Auth::user()->isPetugas();
        @endphp
        
        @if($canDelete)
            <button type="button" onclick="deleteRecord({{ $borrowing->id }})" class="group/btn">
                <div class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-sm rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span>Delete</span>
                    </div>
                </div>
            </button>
        @endif
    @endif
</div>
