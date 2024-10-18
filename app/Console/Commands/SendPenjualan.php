<?php

namespace App\Console\Commands;

use App\Http\Controllers\ScheduleController;
use Illuminate\Console\Command;

class SendPenjualan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-penjualan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer Penjualan dari POS Ke Server';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schedule = new ScheduleController;
        $schedule->sendPenjualan();
    }
}
