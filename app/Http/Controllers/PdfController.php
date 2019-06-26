<?php

namespace App\Http\Controllers;

use Storage;
use App\Services\Pdf;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    /**
     * The dompdf instance.
     *
     * @var \App\Services\Pdf
     */
    protected $pdf;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\Pdf  $pdf
     * @return void
     */
    public function __construct(Pdf $pdf)
    {
        // $this->middleware('auth');

        $this->pdf = $pdf;
    }

    /**
     * Generate the PDF to inspect or download.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        return response($this->pdf->generate($invoice), 200)->withHeaders([
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "{$this->pdf->action()}; filename=invoice-{$invoice->id}.pdf",
        ]);
    }

    public function save(Invoice $invoice)
    {
        Storage::disk('public')->put('invoices/INVOICE-'.$invoice->number.'.pdf', $this->pdf->generate($invoice));
    }
}
