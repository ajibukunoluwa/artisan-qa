<?php

namespace App\Console\Commands;

use App\Services\Question;
use Illuminate\Console\Command;

class AddQandA extends Command
{
    protected $question;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:addQandA {--prev=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Add a question and it's correct answer";

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
        $question   = $this->ask('Enter a question here:');
        $answer     = $this->ask('Enter the correct answer for the above question here:');

        $this->line("Saving question ... ");
        $this->question->add($question, $answer);
        $this->info("Saved");

        $this->nextStep();
    }

    private function nextStep()
    {
        if ($this->confirm('Do you wish to add more questions?')) {
            $this->call("qanda:addQandA", ['--prev' => 'options']);
        } else {
            $this->call("qanda:options");
        }
    }
}
