<?php
namespace App\Command;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\LockableCommand;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateTasksCommand extends Command
{

    /**
     * @var string $defaultName within the `UpdateTasksCommand` class. This static protected variable is defined default name of command.
     * 
     * Exemple command with id : php bin/console app:update-tasks-anonym 1.
     */
    protected static $defaultName = 'app:update-tasks-anonym';

    /**
     * @var EntityManagerInterface $entityManager private property of type `EntityManagerInterface`. This property is then
     * initialized in the constructor of the `UpdateTasksCommand` class.
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var UserPasswordHasherInterface $passwordEncoder private property of type `UserPasswordHasherInterface`. This property is then
     * initialized in the constructor of the `UpdateTasksCommand` class.
     */
    private UserPasswordHasherInterface $passwordEncoder;


    /**
     * The function is a constructor that initializes an EntityManagerInterface object and calls the
     * parent constructor.
     * 
     * @param EntityManagerInterface entityManager The `$entityManager` parameter in the constructor is
     * an instance of the `EntityManagerInterface` class. This parameter is typically used for managing
     * entities in an ORM (Object-Relational Mapping) system, such as Doctrine in PHP. It allows you to
     * perform database operations like persisting, updating, and deleting.
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct();

    }


    /**
     * The function configures a command to associate old tasks with an anonymous user in PHP.
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Associe les anciennes tâches à un utilisateur anonyme.')
            ->addArgument('anonymousUserId', InputArgument::OPTIONAL, 'L\'ID de l\'utilisateur anonyme');

    }


    /**
     * This PHP function assigns an anonymous user to tasks that do not have any user associated with
     * them.
     * 
     * @param InputInterface $input The `execute` function you provided is a method of a Symfony Console
     * Command. It takes two parameters: `$input` of type `InputInterface` and `$output` of type
     * `OutputInterface`.
     * @param OutputInterface $output The `output` parameter in the `execute` method is an instance of
     * `OutputInterface` which is used to interact with the console output. It allows you to write
     * messages, display information, and control the output of your command when it is executed in the
     * console.
     * 
     * @return int The function `execute` returns an integer value, either `Command::FAILURE` or
     * `Command::SUCCESS` based on the outcome of the task.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $anonymousUserId = $input->getArgument('anonymousUserId');
        $anonymousUser = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'anonymous']);

        if ($anonymousUserId === null && $anonymousUser === null) {
            // Create anonym user if not one.
            $user = new User();
            $user->setUsername('anonymous');
            $password = 'password123';
            $user->setPassword($this->passwordEncoder->hashPassword($user, $password));
            $user->setEmail('anonymous@anonym.com');
            $user->setRoles(['ROLE_ANONYM']);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $anonymousUserId = $user->getId();
            $output->writeln(sprintf('Utilisateur anonyme créé avec l\'ID %d.', $anonymousUserId));
        }//end if
    
        if (empty($anonymousUser) === false && $anonymousUserId === null) {
            $output->writeln(sprintf('Un utilisateur anonyme est déjà présent ID : %d ', $anonymousUser->getId()));
            return Command::INVALID;
        }
        
        $user = $this->entityManager->getRepository(User::class)->find($anonymousUserId);

        if ($user === null) {
            $output->writeln('Utilisateur anonyme non trouvé.');
            return Command::FAILURE;
        }

        // Only check tasks with null user.
        $tasks = $this->entityManager->getRepository(Task::class)->findBy(['user' => null]);

        if (empty($tasks) === true) {
            $output->writeln('Aucune tâche sans utilisateur trouvé.');
            return Command::SUCCESS;
        }

        foreach ($tasks as $task) {
            $task->setUser($user);
            $this->entityManager->persist($task);
        }

        $this->entityManager->flush();

        $output->writeln(sprintf('%d tâches ont été mises à jour avec l\'utilisateur anonyme.', count($tasks)));

        return Command::SUCCESS;
    }
    

}
