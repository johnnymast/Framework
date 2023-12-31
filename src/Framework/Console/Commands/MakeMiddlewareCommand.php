<?php

namespace App\Framework\Console\Commands;

use App\Framework\Console\Commands\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'make:middleware',
    description: 'Creates a new middleware.',
    hidden: false,
)]
class MakeMiddlewareCommand extends Command
{
    /**
     * Configure the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the middleware.', null);
    }

    /**
     * Handle the making of a new Middleware.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return int
     */
    public function handler(): int
    {
        $name = $this->input->getArgument('name');

        $view = view('stubs.middleware', [
            'name' => $name,
        ]);

        file_put_contents(
            source_path('Http/Middleware/' . $name . '.php'),
            $view->getBody()
        );

        $this->output->writeln(
            "<info>Middleware created " . realpath(source_path('Http/Middleware/' . $name . '.php')) . '</info>'
        );

        return Command::INVALID;
    }
}
