<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "qanda:options {--prev=}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Choose between adding questions and answers and viewing previously entered answers';

    /**
     * Relevant commands.
     *
     * @var string
     */
    protected $commands = [
        "add"   => "qanda:addQandA",
        "view"  => "qanda:choose",
    ];

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
     * @return mixed
     */
    public function handle()
    {
        $options = $this->action();
        $choice = $this->choice('What would you like to do?', $options, 'add');
        $this->nextStep($choice);

    }

    private function action() : array
    {
        return [
            'add'   => 'Add Questions and Answers', 
            'view'  => 'View previously entered Questions'
        ];
    }

    private function nextStep($choice)
    {
        if ($choice == 'add') {
            $this->call($this->commands['add'], ['--prev' => 'options']);
        } elseif ($choice == 'view') {
            $this->call($this->commands['view'], ['--prev' => 'options']);
        }
    }
}
