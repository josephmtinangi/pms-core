<?php

namespace App\Http\Controllers;

use Storage;
use App\Services\Pdf;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\RealEstateAgent;
use App\Models\CustomerContract;
use App\Models\ClientPaymentSchedule;
use App\Models\CustomerPaymentSchedule;

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
        if($invoice->invoiceable_type == get_class(new ClientPaymentSchedule))
        {
            //
        }
        if($invoice->invoiceable_type == get_class(new CustomerPaymentSchedule)){        
            $agent = RealEstateAgent::latest()->first();
            $customerPaymentSchedule = CustomerPaymentSchedule::find($invoice->invoiceable_id);
            $customerContract = CustomerContract::with(['customer', 'property', 'rooms', 'rooms.room', 'customer'])
                                                    ->find($customerPaymentSchedule->customer_contract_id); 

            return response($this->pdf->generate($invoice, $agent, $customerPaymentSchedule, $customerContract), 200)->withHeaders([
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "{$this->pdf->action()}; filename=INVOICE-{$invoice->number}.pdf",
            ]);
        }
    }

    public function save(Invoice $invoice)
    {
        $filepath = 'invoices/INVOICE-'.$invoice->number.'.pdf';
        
        if($invoice->invoiceable_type == get_class(new ClientPaymentSchedule))
        {
            //
        }
        if($invoice->invoiceable_type == get_class(new CustomerPaymentSchedule)){
            
            $agent = RealEstateAgent::latest()->first();
            $customerPaymentSchedule = CustomerPaymentSchedule::find($invoice->invoiceable_id);
            $customerContract = CustomerContract::with(['customer', 'property', 'rooms', 'rooms.room', 'customer'])
                                                    ->find($customerPaymentSchedule->customer_contract_id);

            if(!$invoice->path)
            {
                Storage::disk('public')->put($filepath, $this->pdf->generate($invoice, $agent, $customerPaymentSchedule, $customerContract));
            }

            $invoice->path = $filepath;
            $invoice->save();         
            
            return response([
                'status' => 200,
                'statusText' => 'success',
                'message' => '',
                'ok' => true,
                'data' => $invoice,
            ], 200);
        }
    }
}
