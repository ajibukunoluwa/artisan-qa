<?php

namespace App\Console\Commands;

use App\Services\Question;
use Illuminate\Console\Command;

class ChooseQuestion extends Command
{

    protected $question;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:choose  {--prev=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display and allow user choose a question';

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
        $questions = $this->question->all();
        $this->questionsExist($questions);

        $selectedQuestionId = $this->askQuestion($questions);
        $this->nextStep($selectedQuestionId);
    }

    private function askQuestion($questions)
    {
        $titles = array_column($questions, 'title');
        $choice = $this->choice('Select a question to answer.', $titles);
        $key = array_search($choice, $titles);

        return $questions[$key]['id'];
    }

    private function questionsExist(array $questions)
    {
        if (count($questions) === 0) {
            if ($this->confirm('No questions available. Do you wish to add questions to proceed?')) {
                $this->call("qanda:addQandA", ['--prev' => 'options']);
            } else {
                $this->call("qanda:options");
            }
        }
    }

    private function nextStep($selectedQuestionId)
    {
        $this->call("qanda:answer", [
            "question"  => $selectedQuestionId
        ]);
    }
}
