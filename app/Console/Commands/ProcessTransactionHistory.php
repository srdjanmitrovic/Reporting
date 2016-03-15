<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class ProcessTransactionHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all transactions for the month.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->source_table = 'transactions'.date('Y').date('m');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = explode('-',date('Y-m-d'));
        DB::table('transaction_aggregation')->truncate();
        $bar = $this->output->createProgressBar($date[2]);
        for ($day = 1; $day <= ($date[2]-1); ++$day) {

            $day_sale_sum = DB::select("SELECT SUM(sale_amount) AS 'sum' FROM " . $this->source_table . " WHERE date BETWEEN '2016-" . $date[1] . "-" . $day . "' AND '2016-" . $date[1] . "-" . ($day + 1) . "'; ");

            $day_commission_sum = DB::select("SELECT SUM(commission) AS 'commission' FROM " . $this->source_table . " WHERE date BETWEEN '2016-" . $date[1] . "-" . $day . "' AND '2016-" . $date[1] . "-" . ($day + 1) . "'; ");

            $transaction_count = DB::select("SELECT COUNT(*) AS 'count' FROM " . $this->source_table . " WHERE date BETWEEN '2016-" . $date[1] . "-" . $day . "' AND '2016-" . $date[1] . "-" . ($day + 1) . "'; ");

            $last_transaction_id = DB::select("SELECT id FROM " . $this->source_table . " WHERE date BETWEEN '2016-" . $date[1] . "-" . $day . "' AND  '2016-" . $date[1] . "-" . ($day + 1) . "' ORDER BY id DESC LIMIT 1; ");

            DB::table('transaction_aggregation')->insert(['month' => $date[1], 'day' => $day, 'last_transaction_id' => $last_transaction_id[0]->id, 'commission_average' => $day_commission_sum[0]->commission / $transaction_count[0]->count, 'commission_sum' => $day_commission_sum[0]->commission, 'sale_average' => $day_sale_sum[0]->sum / $transaction_count[0]->count, 'sale_sum' => $day_sale_sum[0]->sum, 'transaction_count' => $transaction_count[0]->count]);
            $bar->advance();
        }
        // DB::table('transaction_aggregation')->insert(['month' => $date[1], 'day' => $day, 'last_transaction_id' => 0, 'commission_average' => 0, 'commission_sum' => 0, 'sale_average' => 0, 'sale_sum' => $day_sale_sum[0]->sum, 'transaction_count' => 0]);
        $bar->finish();
    }
}
