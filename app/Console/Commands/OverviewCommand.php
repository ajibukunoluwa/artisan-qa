<?php

namespace App\Console\Commands;

use App\Services\Question;
use Illuminate\Console\Command;

class OverviewCommand extends Command
{
    protected $question;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:overview  {--prev=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Overview of your final progress';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Question $question)
    {
        parent::__construct();
        $this->question = $question;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $headers = ['Question', 'Attempts', 'Status'];

        $result = $this->question->select(['title', 'attempts', 'status']);

        $this->table($headers, $result);

        $this->info('You have completed all questions!');

        if ($this->confirm('Will you like to go back to the menu?')) {
            $this->nextStep();
        }

    }

    private function nextStep()
    {
        $this->call('qanda:options');
    }
}
