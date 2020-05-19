<?php

namespace App\Services;

use App\Question as QuestionModel;
use Illuminate\Database\Eloquent\Collection;

class Question {

    protected $question;

    public function __construct(QuestionModel $question)
    {
        $this->question = $question;
    }

    public function add(string $question, $answer)
    {
        $this->question->create([
            'title' => $question,
            'correct_answer' => $answer,
        ]);
    }

    public function all() : array
    {
        return $this->question->all()->toArray();
    }

    public function select(array $columns) : array
    {
        return $this->question->all($columns)->toArray();
    }

    public function find(int $id) : array
    {
        return $this->question->find($id)->toArray();
    }

    public function incrementAttempts(int $id, $userAnswer, $isCorrect) : void
    {
        $question = $this->question->find($id);
        $question->status = $isCorrect;
        $question->user_answer = $userAnswer;
        $question->attempts = ++$question->attempts;
        $question->save();
    }

    public function updateStatus(int $id, $status = true) : void
    {
        $question = $this->question->find($id);
        $question->status = $status;
        $question->save();
    }

    public function validate($userAnswer, $correctAnswer)
    {
        return ($userAnswer === $correctAnswer) ?: false;
    }

    public function idle()
    {
        return $this->question->idle()->count();
    }


}