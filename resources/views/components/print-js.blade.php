<script>
    // Store current borrowing ID for printing
    let currentBorrowingId = null;

    // Print Modal Functions
    function openPrintModal(borrowingId) {
        console.log('Opening print modal for borrowing:', borrowingId);
        
        // Store the borrowing ID
        currentBorrowingId = borrowingId;
        
        const modal = document.getElementById('printModal');
        const printContent = document.getElementById('printContent-' + borrowingId);
        const modalPrintContent = document.getElementById('modalPrintContent');
        
        if (!modal) {
            console.error('Print modal not found');
            return;
        }
        
        if (!printContent) {
            console.error('Print content not found for borrowing:', borrowingId);
            return;
        }
        
        if (!modalPrintContent) {
            console.error('Modal print content container not found');
            return;
        }
        
        // Copy content to modal
        modalPrintContent.innerHTML = printContent.innerHTML;
        console.log('Content copied to modal. Content length:', modalPrintContent.innerHTML.length);
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePrintModal() {
        const modal = document.getElementById('printModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        // Clear the current borrowing ID
        currentBorrowingId = null;
    }

    function printReceipt() {
        console.log('Print function called. Current borrowing ID:', currentBorrowingId);
        
        if (!currentBorrowingId) {
            console.error('No borrowing ID stored');
            alert('Error: Unable to identify the borrowing record. Please try again.');
            return;
        }
        
        const printContent = document.getElementById('printContent-' + currentBorrowingId);
        
        if (!printContent) {
            console.error('Print content not found for borrowing:', currentBorrowingId);
            alert('Error: Receipt content not found. Please try again.');
            return;
        }
        
        console.log('Preparing to print...');
        
        // Create iframe for printing
        let printFrame = document.getElementById('printFrame');
        if (!printFrame) {
            printFrame = document.createElement('iframe');
            printFrame.id = 'printFrame';
            printFrame.style.position = 'fixed';
            printFrame.style.right = '0';
            printFrame.style.bottom = '0';
            printFrame.style.width = '0';
            printFrame.style.height = '0';
            printFrame.style.border = '0';
            document.body.appendChild(printFrame);
        }
        
        const printDocument = printFrame.contentWindow.document;
        
        // Write content to iframe
        printDocument.open();
        printDocument.write(`
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Bukti Peminjaman Buku</title>
                <script src="https://cdn.tailwindcss.com"><\/script>
                <style>
                    @media print {
                        @page {
                            size: A4;
                            margin: 15mm;
                        }
                        body {
                            margin: 0;
                            padding: 0;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                        * {
                            -webkit-print-color-adjust: exact !important;
                            print-color-adjust: exact !important;
                        }
                    }
                    body {
                        font-family: system-ui, -apple-system, sans-serif;
                    }
                    /* Ensure colors are printed */
                    .bg-slate-100 {
                        background-color: #f1f5f9 !important;
                    }
                    .bg-slate-50 {
                        background-color: #f8fafc !important;
                    }
                    .bg-purple-500, .border-purple-500 {
                        background-color: #a855f7 !important;
                        border-color: #a855f7 !important;
                    }
                    .text-purple-600 {
                        color: #9333ea !important;
                    }
                    .text-slate-600 {
                        color: #475569 !important;
                    }
                    .text-slate-800 {
                        color: #1e293b !important;
                    }
                    .text-slate-500 {
                        color: #64748b !important;
                    }
                    .text-green-600 {
                        color: #16a34a !important;
                    }
                    .text-orange-600 {
                        color: #ea580c !important;
                    }
                    .text-blue-600 {
                        color: #2563eb !important;
                    }
                    .border-slate-800 {
                        border-color: #1e293b !important;
                    }
                    .border-b-4 {
                        border-bottom-width: 4px !important;
                    }
                    .border-l-4 {
                        border-left-width: 4px !important;
                    }
                    .border-t-2 {
                        border-top-width: 2px !important;
                    }
                </style>
            </head>
            <body class="bg-white">
                ${printContent.innerHTML}
            </body>
            </html>
        `);
        printDocument.close();
        
        // Wait for Tailwind CSS and content to load, then print
        printFrame.contentWindow.focus();
        setTimeout(() => {
            printFrame.contentWindow.print();
        }, 1000); // Increased timeout to ensure Tailwind loads
    }

    // Close modal on backdrop click
    document.addEventListener('DOMContentLoaded', function() {
        const printModal = document.getElementById('printModal');
        if (printModal) {
            printModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closePrintModal();
                }
            });
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePrintModal();
        }
    });
</script>
