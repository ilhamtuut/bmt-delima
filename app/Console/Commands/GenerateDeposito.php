<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Profit;
use App\Models\Deposito;
use App\Models\Mutation;
use App\Models\LogGenerate;
use Illuminate\Console\Command;

class GenerateDeposito extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deposito:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Deposito';

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
        $data = Deposito::whereDate('expired_at',date('Y-m-d'))->get();
        if(count($data) > 0){
            foreach ($data as $key => $value) {
                $profit = Profit::create([
                    'user_id' => $value->user_id,
                    'deposito_id' => $value->id,
                    'amount' => $value->amount,
                    'percent' => $value->type->percent,
                    'profit' => $value->amount * $value->type->percent,
                    'status'=>1
                ]);

                Mutation::create([
                    'trxid' => $profit->trxid,
                    'user_id' => $profit->user_id,
                    'note' => 'Pendapatan '.$value->type->name,
                    'amount' => $profit->profit,
                    'debit' => $profit->profit,
                    'kredit' => 0,
                    'reference_id' => $profit->id,
                    'reference_type' => Profit::class,
                    'status' => 1
                ]);
                $value->update(['status' => 4]);
            }
            LogGenerate::create([
                'activity' => 'Generate Deposito'
            ]);
        }
    }
}
