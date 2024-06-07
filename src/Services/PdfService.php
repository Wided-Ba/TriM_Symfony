<?php
namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private $dompdf;

    public function __construct() {
        $options = new Options();
        $options->set('defaultFont', 'Garamond');
        $options->set('isPhpEnabled', true); // Enable PHP execution within the HTML
        $options->set('isHtml5ParserEnabled', true); // Enable HTML5 parser
        $options->set('isRemoteEnabled', true); // Enable remote file access
        $this->dompdf = new Dompdf();
        $this->dompdf->setOptions($options);
    }

    public function showPdf($html): void
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        // Output PDF directly to browser
        $this->dompdf->stream("details.pdf", [
            'Attachment' => false,
        ]);
    }

    public function generateBinaryPDF($html)
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        // Return PDF binary data
        return $this->dompdf->output();
    }
}