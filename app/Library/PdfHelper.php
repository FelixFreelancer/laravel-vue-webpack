<?php

use App\Models\Shipment;
use App\Models\Quotation;

  function generateInvoice($id)
  {
      $shipemnt = Shipment::find($id);
      $url = url('shipment/'.$id);
      $publicPath = '/pdf/'.date('d-m-Y');
      $path = base_path().$publicPath;
      if (!file_exists($path)) {
          mkdir($path);
      }
      $shipemntstr = strtoupper(substr($shipemnt['name'], 0, 5));
      $filename = $shipemntstr.'_'.time().'.pdf';

      if(env('APP_ENV') == 'development')
      {
        //$command = 'xvfb-run wkhtmltopdf -T 0 -B 0 -L 0 -R 0"'.$url.'" "'.$path.'/'.$filename.'"';
        $command = 'xvfb-run wkhtmltopdf "'.$url.'" "'.$path.'/'.$filename.'"';
        exec($command);
      }
      else {
        $command = '/home/globalparcelforw/bin/wkhtmltopdf "'.$url.'" "'.$path.'/'.$filename.'" 2>&1';
        exec($command,$output);
      }

    //   $command = 'xvfb-run wkhtmltopdf "'.$url.'" "'.$path.'/'.$filename.'"';
     //echo($command);
    //  echo $filename;
     //  dd($output);
      $data['invoice_name'] = $filename;
      $data['invoice_path'] = $publicPath;
      return $data;
  }

  function generatePersonalShopperInvoice($id)
  {
      $url = url('personal-shopper-pdf/'.$id);
      $publicPath = '/pdf/'.date('d-m-Y');
      $path = base_path().$publicPath;
      if (!file_exists($path)) {
          mkdir($path);
      }
      $filename = strtoupper('Quotation').'_'.time().'.pdf';

      if(env('APP_ENV') == 'development')
      {
        //$command = 'xvfb-run wkhtmltopdf -T 0 -B 0 -L 0 -R 0"'.$url.'" "'.$path.'/'.$filename.'"';
        $command = 'xvfb-run wkhtmltopdf "'.$url.'" "'.$path.'/'.$filename.'"';
        exec($command);
      }
      else {
        $command = '/home/globalparcelforw/bin/wkhtmltopdf "'.$url.'" "'.$path.'/'.$filename.'" 2>&1';
        exec($command,$output);
      }
      //dd($command);
      $data['invoice_name'] = $filename;
      $data['invoice_path'] = $publicPath;
      return $data;
  }

  function generateMembershipInvoice($id)
  {
      $url = url('membership-pdf/'.$id);
      $publicPath = '/pdf/'.date('d-m-Y');
      $path = base_path().$publicPath;
      if (!file_exists($path)) {
          mkdir($path);
      }
      $filename = strtoupper('Membership').'_'.time().'.pdf';

      if(env('APP_ENV') == 'development')
      {
        //$command = 'xvfb-run wkhtmltopdf -T 0 -B 0 -L 0 -R 0"'.$url.'" "'.$path.'/'.$filename.'"';
        $command = 'xvfb-run wkhtmltopdf "'.$url.'" "'.$path.'/'.$filename.'"';
        exec($command);
      }
      else {
        $command = '/home/globalparcelforw/bin/wkhtmltopdf "'.$url.'" "'.$path.'/'.$filename.'" 2>&1';
        exec($command,$output);
      }
      // $command = 'xvfb-run wkhtmltopdf -T 0 -B 0 -L 0 -R 0"'.$url.'" "'.$path.'/'.$filename.'"';
     // dd($command);
      $data['invoice_name'] = $filename;
      $data['invoice_path'] = $publicPath;
      return $data;
  }
