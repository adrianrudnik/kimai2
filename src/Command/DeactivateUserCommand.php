<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\User\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeactivateUserCommand extends Command
{
    private $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('kimai:user:deactivate')
            ->setAliases(['fos:user:deactivate'])
            ->setDescription('Deactivate a user')
            ->setDefinition([
                new InputArgument('username', InputArgument::REQUIRED, 'The username'),
            ])
            ->setHelp(
                <<<'EOT'
The <info>kimai:user:deactivate</info> command deactivates a user (will not be able to log in)

  <info>php %command.full_name% susan_super</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $user = $this->userService->findUserByUsernameOrThrowException($username);

        $io = new SymfonyStyle($input, $output);

        if ($user->isEnabled()) {
            $user->setEnabled(false);
            $this->userService->updateUser($user);
            $io->success(sprintf('User "%s" has been deactivated.', $username));
        } else {
            $io->warning(sprintf('User "%s" is already deactivated.', $username));
        }

        return 0;
    }
}
