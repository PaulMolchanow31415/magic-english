<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class FlushSessions extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Удаляет все сессии пользователей';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return string
     */
    public function handle(): string {
        DB::table('sessions')->truncate();

        return 'Сессии успешно очищены';
    }
}
