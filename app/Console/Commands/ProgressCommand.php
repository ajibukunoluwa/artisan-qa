<?php

namespace App\Console\Commands;

use App\Services\Question;
use Illuminate\Console\Command;

class ProgressCommand extends Command
{
    protected $question;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:progress  {--prev=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show progress of questions and answers';

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
        $this->line('Your Progress so far');

        $this->progress();

        $this->line(PHP_EOL);

        $this->nextStep();
    }

    private function progress()
    {
        $questions = $this->question->all();

        $numberOfQuestions = count($questions);

        $bar = $this->output->createProgressBar($numberOfQuestions);

        foreach ($questions as $key => $question)
        {
            if ($question['status'] == 'Passed') {
                $bar->advance();
            }
        }
    }

    private function overview()
    {
        $this->call('qanda:overview', ['--prev' => 'progress']);
    }

    private function choose()
    {
        $this->call('qanda:choose', ['--prev' => 'progress']);
    }

    private function nextStep()
    {
        if ($this->question->idle() == 0) {
            $this->overview();
        }

        $this->choose();
    }
}
