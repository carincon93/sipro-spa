<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PdfController;
use App\Models\ProyectoPdfVersion;

class generatePdfProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'versioning:project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate PDF version of project';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pdfVersion = ProyectoPdfVersion::where('estado', 0)->orderBy('created_at', 'asc')->first();

        if (!empty($pdfVersion)) {
            PdfController::generateProjectSumary($pdfVersion->proyecto->convocatoria, $pdfVersion->proyecto, $pdfVersion->version);
        }
    }
}
