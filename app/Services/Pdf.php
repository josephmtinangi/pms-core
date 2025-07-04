<?php 

namespace App\Services; 

use Dompdf\Dompdf; 
use App\Models\Invoice;
use App\Models\RealEstateAgent;
use App\Models\CustomerContract;
use Illuminate\Support\Facades\View; 
use App\Models\CustomerPaymentSchedule;

class Pdf extends Dompdf 
{ 
    /** 
     * Create a new pdf instance. 
     * 
     * @param  array $config 
     * @return void 
     */ 
    public function __construct(array $config = []) 
    { 
        parent::__construct($config); 
    }

    /** 
     * Determine id the use wants to download or view. 
     * 
     * @return string 
     */ 
    public function action() 
    { 
        return request()->has('download') ? 'attachment' : 'inline';
    }


    /**
     * Render the PDF.
     *
     * @param  \App\Invoice  $invoice
     * @return string
     */
    public function generateCustomerInvoice(Invoice $invoice, RealEstateAgent $agent, CustomerPaymentSchedule $customerPaymentSchedule, CustomerContract $customerContract)
    {
        $this->loadHtml(
            View::make('invoices/customer', compact(['agent','invoice', 'customerPaymentSchedule','customerContract']))->render()
        );

        $this->render();

        return $this->output();
    }

    /**
     * Render the PDF.
     *
     * @param  \App\Invoice  $invoice
     * @return string
     */
    public function generateClientInvoice(Invoice $invoice, RealEstateAgent $agent, ClientPaymentSchedule $clientPaymentSchedule, ClientContract $clientContract)
    {
        $this->loadHtml(
            View::make('invoices/client', compact(['agent','invoice', 'clientPaymentSchedule','clientContract']))->render()
        );

        $this->render();

        return $this->output();
    }    
}