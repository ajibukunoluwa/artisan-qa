<?php

namespace App\Console\Commands;

use App\Services\Question;
use Illuminate\Console\Command;

class AnswerCommand extends Command
{

    protected $question;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:answer {question}  {--prev=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allows user answer questions';

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
        $this->process();
    }

    private function process()
    {   
        $question   = $this->question->find($this->argument('question'));
        $userAnswer = $this->ask($question['title']);
        $isCorrect  = $this->question->validate($userAnswer, $question['correct_answer']);
        
        $this->question->incrementAttempts($question['id'], $userAnswer, $isCorrect);

        ($isCorrect) ?  $this->correct($question['id']) : $this->wrong($question);
    }

    private function correct($id)
    {
        $this->question->updateStatus($id);
        $this->info("You passed");
        $this->nextStep();
    }

    private function wrong(array $question)
    {
        $this->question->updateStatus($question['id'], false);
        if ($this->confirm('Your answer was wrong. Do you wish to try again?')) {
            $this->process();
        } else {
            $this->call("qanda:choose", ['--prev' => 'options']);
        }
    }

    private function nextStep()
    {
        $this->call("qanda:progress", ['--prev' => 'answer']);
    }
}
