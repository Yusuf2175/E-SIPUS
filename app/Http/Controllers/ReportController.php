<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowingReportRequest;
use App\Http\Requests\BookReportRequest;
use App\Http\Requests\UserReportRequest;
use App\Http\Requests\ReturnReportRequest;
use App\Services\ReportService;
use App\Services\ReportGeneratorService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $reportService;
    protected $generatorService;

    public function __construct(ReportService $reportService, ReportGeneratorService $generatorService)
    {
        $this->reportService = $reportService;
        $this->generatorService = $generatorService;
        
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin() && !Auth::user()->isPetugas()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('reports.index');
    }

    public function borrowingReport(BorrowingReportRequest $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $status = $request->status ?? 'all';

        $data = $this->reportService->getBorrowingReportData($startDate, $endDate, $status);

        if ($request->format === 'pdf') {
            return $this->generatorService->generateBorrowingPDF(
                $data['borrowings'], 
                $data['stats'], 
                $startDate, 
                $endDate, 
                $status
            );
        }
        
        return $this->generatorService->generateBorrowingExcel(
            $data['borrowings'], 
            $data['stats'], 
            $startDate, 
            $endDate
        );
    }

    public function bookReport(BookReportRequest $request)
    {
        $data = $this->reportService->getBookReportData(
            $request->category, 
            $request->availability
        );

        if ($request->format === 'pdf') {
            return $this->generatorService->generateBookPDF(
                $data['books'], 
                $data['stats'], 
                $request->category, 
                $request->availability
            );
        }
        
        return $this->generatorService->generateBookExcel(
            $data['books'], 
            $data['stats']
        );
    }

    public function userReport(UserReportRequest $request)
    {
        $startDate = $request->registration_start ? Carbon::parse($request->registration_start) : null;
        $endDate = $request->registration_end ? Carbon::parse($request->registration_end) : null;

        $data = $this->reportService->getUserReportData(
            $request->role, 
            $startDate, 
            $endDate
        );

        if ($request->format === 'pdf') {
            return $this->generatorService->generateUserPDF(
                $data['users'], 
                $data['stats'], 
                $request->role, 
                $startDate, 
                $endDate
            );
        }
        
        return $this->generatorService->generateUserExcel(
            $data['users'], 
            $data['stats'], 
            $request->role, 
            $startDate, 
            $endDate
        );
    }

    public function returnReport(ReturnReportRequest $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $returnStatus = $request->return_status ?? 'all';

        $data = $this->reportService->getReturnReportData($startDate, $endDate, $returnStatus);

        if ($request->format === 'pdf') {
            return $this->generatorService->generateReturnPDF(
                $data['returns'], 
                $data['stats'], 
                $startDate, 
                $endDate, 
                $returnStatus
            );
        }
        
        return $this->generatorService->generateReturnExcel(
            $data['returns'], 
            $data['stats'], 
            $startDate, 
            $endDate
        );
    }
}
