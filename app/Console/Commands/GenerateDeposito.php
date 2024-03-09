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
        $datenow = date('Y-m-d');
        $data = Deposito::where('status',1)->get();
        if(count($data) > 0){
            foreach ($data as $key => $value) {
                if($datenow < $value->expired_at){
                    $last_profit = $value->profit()->orderBy('id','desc')->first();
                    $date_profit = date('Y-m-d', strtotime($value->created_at. ' + 30 days'));
                    if($last_profit){
                        $date_profit = date('Y-m-d', strtotime($last_profit->created_at. ' + 30 days'));
                    }
                    if($date_profit == $datenow){
                        if($value->type_deposito == 'custom'){
                            $note = $value->type->name.' '.$value->contract.' Bulan '.($value->percent*100).'%';
                            $profit_amount = round($value->profit / $value->contract,0);
                            $rest = $value->contract - $value->profit()->count();
                        }else{
                            $note = $value->type->name;
                            $profit_amount = round($value->profit / $value->type->contract,0);
                            $rest = $value->type->contract - $value->profit()->count();
                        }
                        if($rest == 1){
                            $profit_amount = $value->profit - $value->profit()->sum('amount');
                        }
                        $profit = Profit::create([
                            'user_id' => $value->user_id,
                            'deposito_id' => $value->id,
                            'amount' => $profit_amount,
                            'percent' => 1,
                            'profit' => $value->profit()->count() + 1,
                            'status'=>1
                        ]);

                        Mutation::create([
                            'trxid' => $profit->trxid,
                            'user_id' => $profit->user_id,
                            'note' => 'Pendapatan bulan ke-'.($value->profit()->count() + 1).' '.$note,
                            'amount' => $profit->amount,
                            'debit' => $profit->amount,
                            'kredit' => 0,
                            'reference_id' => $profit->id,
                            'reference_type' => Profit::class,
                            'status' => 1
                        ]);
                        if($rest == 1){
                            $value->update(['status' => 4]);
                        }
                    }
                }
            }
            LogGenerate::create([
                'activity' => 'Generate Deposito'
            ]);
            echo "Done\n"; exit;
        }
        echo "Not Found\n"; exit;
    }
}
