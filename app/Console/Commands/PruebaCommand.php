<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class PruebaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Prueba:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a description of the Prueba command';

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
        Product::where('id', 1)->update(['stock' => 10]);
        // return dd('that ok');
    }
}
