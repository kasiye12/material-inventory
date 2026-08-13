<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dompdf\Dompdf;
use Dompdf\Options;

class DomPDFServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('dompdf', function () {
            $options = new Options();
            $options->set('defaultFont', 'Noto Sans Ethiopic');
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            
            $dompdf = new Dompdf($options);
            return $dompdf;
        });
    }

    public function boot(): void
    {
        //
    }
}
