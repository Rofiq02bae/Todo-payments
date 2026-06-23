<?php

namespace App\Services;

use App\Models\Todo;
use App\Models\Payment;
use App\Models\PdfExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PdfExportService
{
    /**
     * Generate a PDF report for a specific Todo.
     *
     * This method:
     * 1. Renders the Blade template with todo & payment data
     * 2. Converts HTML to PDF using DomPDF
     * 3. Saves the file to storage/app/public/pdfs/
     * 4. Creates a PdfExport record in the database
     *
     * @param Todo    $todo
     * @param Payment $payment
     * @return PdfExport
     * @throws \Exception
     */
    public function generate(Todo $todo, Payment $payment): PdfExport
    {
        $exportDate = Carbon::now();
        $filename = sprintf(
            'TodoReport_%s.pdf',
            $exportDate->format('Y-m-d_His')
        );
        $relativePath = 'pdfs/' . $filename;

        $pdf = Pdf::loadView('pdf.todo', [
            'todo'       => $todo,
            'payment'    => $payment,
            'exportDate' => $exportDate,
        ]);

        Storage::disk('public')->put($relativePath, $pdf->output());

        return PdfExport::create([
            'payment_id'   => $payment->id,
            'todo_id'      => $todo->id,
            'file_path'    => $relativePath,
            'generated_at' => $exportDate,
        ]);
    }

    /**
     * Get the latest PdfExport for a todo, if one exists.
     */
    public function getLatestForTodo(Todo $todo): ?PdfExport
    {
        return PdfExport::where('todo_id', $todo->id)
            ->latest('generated_at')
            ->first();
    }

    /**
     * Get the full storage URL for a PdfExport file.
     */
    public function getDownloadUrl(PdfExport $pdfExport): string
    {
        return Storage::disk('public')->url($pdfExport->file_path);
    }
}
