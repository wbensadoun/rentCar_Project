<?php
namespace App\Command;


use App\Entity\User;
use App\Service\UserManagerHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;


class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:user:create';

    private $userManager;

    public function __construct(UserManagerHelper $userManager)
    {
        parent::__construct();

        $this->userManager = $userManager;
    }

    protected function configure()
    {
        $this
            ->setName('user:create')
            ->setDescription('Create a user.')
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, 'Identifiant'),
                new InputArgument('email', InputArgument::REQUIRED, 'Email'),
                new InputArgument('password', InputArgument::REQUIRED, 'Mot de passe'),
                new InputArgument('role', InputArgument::REQUIRED, 'Role (ROLE_USER, ROLE_ADMIN..)'),
                new InputOption('inactive', null, InputOption::VALUE_NONE, 'Active/désactive L\'utilisateur'),
            ));
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $role = $input->getArgument('role');
        $inactive = $input->getOption('inactive');

        $this->userManager->createUser($username, $password, $email, $role, !$inactive);

        $output->writeln(sprintf('Utilisateur <comment>%s</comment> créé avec succès (role : '.$role.')', $username));

        return Command::SUCCESS;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('username')) {
            $question = new Question('Vous devez saisir un identifiant : ');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('Le username ne peut être vide');
                }

                return $username;
            });
            $questions['username'] = $question;
        }

        if (!$input->getArgument('email')) {
            $question = new Question('Vous devez saisir un email : ');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('L\email ne peut être vide');
                }

                return $email;
            });
            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Vous devez saisir un mot de passe : ');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Le mot de passe ne peut être vide');
                }

                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        if (!$input->getArgument('role')) {
            $question = new Question('Vous devez saisir un role (ROLE_USER, ROLE_ADMIN..) : ');
            $question->setValidator(function ($role) {
                if (empty($role)) {
                    throw new \Exception('Le role ne peut être vide');
                }

                return $role;
            });
            $questions['role'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
