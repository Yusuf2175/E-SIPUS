{{-- Approval Status Badges --}}
<div class="flex-shrink-0 flex flex-col gap-2">
    {{-- Borrowing Approval Status --}}
    @if($borrowing->approval_status === 'pending')
        <div class="relative">
            <div class="relative bg-orange-500 text-white px-5 py-3 rounded-2xl shadow-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-xs font-black tracking-widest">PENDING APPROVAL</span>
                </div>
            </div>
        </div>
    @elseif($borrowing->approval_status === 'rejected')
        <div class="relative">
            <div class="relative bg-red-600 text-white px-5 py-3 rounded-2xl shadow-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="text-xs font-black tracking-widest">REJECTED</span>
                </div>
            </div>
        </div>
        @if($borrowing->rejection_reason)
            <div class="bg-red-50 border-2 border-red-200 rounded-xl p-3">
                <p class="text-xs font-semibold text-red-800 mb-1">Rejection Reason:</p>
                <p class="text-xs text-red-700">{{ $borrowing->rejection_reason }}</p>
            </div>
        @endif
    @elseif($borrowing->approval_status === 'approved')
        {{-- Return Approval Status (if return is pending) --}}
        @if($borrowing->return_approval_status === 'pending')
            <div class="relative">
                <div class="relative bg-cyan-500 text-white px-5 py-3 rounded-2xl shadow-lg">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path>
                        </svg>
                        <span class="text-xs font-black tracking-widest">RETURN PENDING</span>
                    </div>
                </div>
            </div>
        @elseif($borrowing->return_approval_status === 'rejected')
            <div class="relative">
                <div class="relative bg-red-600 text-white px-5 py-3 rounded-2xl shadow-lg">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="text-xs font-black tracking-widest">RETURN REJECTED</span>
                    </div>
                </div>
            </div>
            @if($borrowing->return_rejection_reason)
                <div class="bg-red-50 border-2 border-red-200 rounded-xl p-3">
                    <p class="text-xs font-semibold text-red-800 mb-1">Rejection Reason:</p>
                    <p class="text-xs text-red-700">{{ $borrowing->return_rejection_reason }}</p>
                </div>
            @endif
        @else
            {{-- Normal Status (Borrowed/Returned/Overdue) --}}
            @if($borrowing->status === 'borrowed')
                @if($borrowing->isOverdue())
                    <div class="relative">
                        <div class="relative bg-red-500 text-white px-5 py-3 rounded-2xl shadow-lg">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs font-black tracking-widest">OVERDUE</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="relative">
                        <div class="relative bg-green-500 text-white px-5 py-3 rounded-2xl shadow-lg">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span class="text-xs font-black tracking-widest">BORROWED</span>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="relative">
                    <div class="relative bg-green-600 text-white px-5 py-3 rounded-2xl shadow-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-xs font-black tracking-widest">RETURNED</span>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    @endif
</div>
