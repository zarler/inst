<?php



class Lib_Pdf extends Model_Database
{

    function createPdf($data = array())
    {
        $pdf = Lib::factory('Tcpdf_Pdf');
        // create new PDF document
        //$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //添加浏览器端显示标题
        $pdf->SetTitle($data['title'] . ".pdf");
        $pdf->setPrintHeader(false);
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        // set font
        // $pdf->SetFont('dejavusans', '', 10);
        // add a page

        $pdf->AddPage();
        //Lib::factory('Debug')->D($data['content']);
        $pdf->writeHTML($data['content'], true, false, true, false, '');
        $pdf->lastPage();
        return $pdf;
    }
    
    /**
     * 下载pdf
     */
    function downloadPdf($outPutFile, $downloadFilename)
    {
        $content = file_get_contents($outPutFile);
        header('Content-Description: File Transfer');
        if (headers_sent()) {
            $this->error('Some data has already been output to browser, can\'t send PDF file');
        }
        header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
        //header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
        header('Pragma: public');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        // force download dialog
        header('Content-Type: application/force-download');
        header('Content-Type: application/octet-stream', false);
        header('Content-Type: application/download', false);
        // use the Content-Disposition header to supply a recommended filename
        header('Content-Disposition: attachment; filename="'.($downloadFilename).'"');
        echo $content;
        exit;
    }


}
