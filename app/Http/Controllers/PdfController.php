<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Payment;
use App\Models\PdfExport;
use App\Services\PdfExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function __construct(protected PdfExportService $pdfExportService)
    {
    }

    /**
     * Generate (or return existing) PDF for a todo.
     *
     * Flow:
     * 1. Accept optional order_id + transaction_id from request body
     * 2. If order_id provided → find payment by it (even if pending)
     * 3. Otherwise → find the latest paid payment for this todo
     * 4. If payment found but still pending → mark as success immediately
     * 5. If no payment exists → return { needs_payment: true }
     * 6. If PDF already exists → return download URL
     * 7. Otherwise → generate new PDF → return download URL
     */
    public function generate(Request $request, Todo $todo): JsonResponse
    {
        // If order_id is provided (from Snap onSuccess), find that specific payment
        if ($request->order_id) {
            $payment = Payment::where('order_id', $request->order_id)->first();
        } else {
            // Fallback: find latest paid/complete payment
            $payment = Payment::where('todo_id', $todo->id)
                ->whereIn('status', ['capture', 'settlement', 'success'])
                ->latest()
                ->first();
        }

        // No payment found at all
        if (!$payment) {
            return response()->json([
                'needs_payment' => true,
            ]);
        }

        // If payment is still pending, mark as success immediately
        // (onSuccess from Snap confirmed the payment, no need to wait for webhook)
        if (in_array($payment->status, ['pending', 'capture', 'settlement', 'success'])) {
            if ($payment->status === 'pending') {
                $payment->status = 'success';
            }
            if ($request->transaction_id) {
                $payment->transaction_id = $request->transaction_id;
            }
            $payment->save();
        }

        // Check if PDF already exists
        $existingExport = $this->pdfExportService->getLatestForTodo($todo);

        if ($existingExport) {
            return response()->json([
                'needs_payment' => false,
                'already_paid'  => true,
                'payment_id'    => $payment->id,
                'download_url'  => route('pdf.download', $existingExport),
            ]);
        }

        // Generate new PDF
        try {
            $pdfExport = $this->pdfExportService->generate($todo, $payment);

            return response()->json([
                'needs_payment' => false,
                'already_paid'  => false,
                'payment_id'    => $payment->id,
                'download_url'  => route('pdf.download', $pdfExport),
            ]);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat PDF. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Download a previously generated PDF file.
     */
    public function download(PdfExport $pdfExport): Response
    {
        $path = $pdfExport->file_path;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File PDF tidak ditemukan.');
        }

        return response(
            Storage::disk('public')->get($path),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . basename($path) . '"',
            ]
        );
    }
}
