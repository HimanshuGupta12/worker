<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrController extends Controller
{
    public function qr(string $code)
    {
        $label = request('label');

        $pdf = new \TCPDF('P', 'mm', [19, 19]);

        $pdf->SetAutoPageBreak(false);
        $pdf->SetTitle('QR');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->AddPage('P', array(19, 19));

        $pdf->SetFont('helvetica', '', 7);
        $pdf->SetMargins(0,0, 0);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(1);
        $pdf->write2DBarcode($code, 'QRCODE,M', 2.5, 1.1, 14, 14, [], 'M');
        $pdf->Text(0, 15.5, $label, false, false, true, 0, 0, 'C');

        $pdf->Output();
    }


}
