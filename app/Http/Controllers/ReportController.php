<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportGenerator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class ReportController extends Controller
{
    protected $reportGenerator;

    public function __construct(ReportGenerator $reportGenerator)
    {
        $this->reportGenerator = $reportGenerator;
    }

    public function showReportForm()
    {
        return view('tenant.reports.index');
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string',
            'period' => 'nullable|string|in:day,week,month,year',
            'sale_id' => 'nullable|array',
            'sale_id.*' => 'integer|exists:sales,id',
        ]);

        $tenant = Auth::user();
        $subscriptionPlan = $tenant->subscription_plan ?? 'basic';
        $role = $tenant->role ?? 'user';

        if ($role === 'pharmacist' && !$tenant->can_generate_reports) {
            return redirect()->back()->withErrors(['report' => 'Pharmacists are not allowed to generate reports unless permitted by tenant admin.']);
        }

        if ($subscriptionPlan === 'basic' && $request->report_type !== 'invoice') {
            return redirect()->back()->withErrors(['report' => 'Basic plan does not allow report generation.']);
        }
        // Allow 'advance' and 'pro' plans to generate reports

        if ($request->report_type === 'invoice') {
            if (!$request->sale_id || count($request->sale_id) === 0) {
                return redirect()->back()->withErrors(['sale_id' => 'At least one Sale ID is required for invoice report']);
            }
            // Generate combined PDF for multiple sale_ids or handle accordingly
            $pdfContents = [];
            foreach ($request->sale_id as $saleId) {
                $pdfContents[] = $this->reportGenerator->generateInvoice($saleId);
            }
            // For simplicity, concatenate PDFs or return first PDF (depends on implementation)
            // Here, returning first PDF for demo
            $pdfContent = $pdfContents[0];
            $fileName = 'invoice_' . implode('_', $request->sale_id) . '.pdf';
        } else {
            $pdfContent = $this->reportGenerator->generateReport($request->report_type, $request->period);
            $fileName = $request->report_type . '_report.pdf';
        }

        // For pro plan, send report to tenant email
        if ($subscriptionPlan === 'pro') {
            $email = $tenant->email;
            Mail::raw('Your requested report is attached.', function ($message) use ($email, $pdfContent, $fileName) {
                $message->to($email)
                    ->subject('Your Report')
                    ->attachData($pdfContent, $fileName, [
                        'mime' => 'application/pdf',
                    ]);
            });
        }

        return Response::make($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }

    public function sendEmail(Request $request)
    {
        \Log::info('sendEmail method called');
        // Set tenant connection context manually before validation
        $tenantName = $request->route('tenant') ?? $request->query('tenant') ?? $request->session()->get('tenant');
        \Log::info('Tenant name: ' . $tenantName);
        if (!$tenantName) {
            \Log::error('Tenant identifier missing');
            return redirect()->back()->withErrors(['tenant' => 'Tenant identifier missing.']);
        }
        $tenantModel = Tenant::where('tenant_name', $tenantName)->first();
        if (!$tenantModel) {
            \Log::error('Tenant not found: ' . $tenantName);
            return redirect()->back()->withErrors(['tenant' => 'Tenant not found.']);
        }
        \Log::info('Tenant email: ' . $tenantModel->email);
        $conn = config('database.connections.mysql');
        $conn['database'] = $tenantModel->tenant_name;
        Config::set('database.connections.tenant', $conn);
        DB::purge('tenant');

        // Set default connection to tenant
        Config::set('database.default', 'tenant');
        DB::reconnect('tenant');

        $request->validate([
            'report_type' => 'required|string',
            'period' => 'nullable|string|in:day,week,month,year',
            'sale_id' => 'nullable|array',
            'sale_id.*' => 'integer|exists:sales,id',
        ]);

        $tenant = Auth::user();
        $subscriptionPlan = $tenant->subscription_plan ?? 'basic';
        $role = $tenant->role ?? 'user';

        if ($role === 'pharmacist' && !$tenant->can_generate_reports) {
            \Log::error('Pharmacist not allowed to send reports');
            return redirect()->back()->withErrors(['report' => 'Pharmacists are not allowed to send reports unless permitted by tenant admin.']);
        }

        if ($subscriptionPlan === 'basic' && $request->report_type !== 'invoice') {
            \Log::error('Basic plan does not allow sending reports');
            return redirect()->back()->withErrors(['report' => 'Basic plan does not allow sending reports.']);
        }
        // Allow 'advance' and 'pro' plans to send reports

        if ($request->report_type === 'invoice') {
            if (!$request->sale_id || count($request->sale_id) === 0) {
                \Log::error('At least one Sale ID is required for invoice report');
                return redirect()->back()->withErrors(['sale_id' => 'At least one Sale ID is required for invoice report']);
            }
            $pdfContents = [];
            foreach ($request->sale_id as $saleId) {
                $pdfContents[] = $this->reportGenerator->generateInvoice($saleId);
            }
            // For simplicity, attach only first PDF (or implement multiple attachments)
            $pdfContent = $pdfContents[0];
            $fileName = 'invoice_' . implode('_', $request->sale_id) . '.pdf';
        } else {
            $pdfContent = $this->reportGenerator->generateReport($request->report_type, $request->period);
            $fileName = $request->report_type . '_report.pdf';
        }

        try {
            Mail::raw('Your requested report is attached.', function ($message) use ($tenantModel, $pdfContent, $fileName) {
                $message->to($tenantModel->email)
                    ->subject('Your Report')
                    ->attachData($pdfContent, $fileName, [
                        'mime' => 'application/pdf',
                    ]);
            });
            \Log::info('Mail sent successfully to ' . $tenantModel->email);
        } catch (\Exception $e) {
            \Log::error('Mail sending failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['mail' => 'Failed to send email: ' . $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Report sent to your email successfully.');
    }
}
