<?php

namespace App\Command;

use App\Entity\Security\Group;
use App\Entity\Security\User;
use App\Entity\Categorie\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InitUserCommand extends ContainerAwareCommand
{
    /**
     * @var OutputInterface $output
     */
    private $output;
    private $em;
    private $encoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->em = $entityManager;
        $this->encoder = $encoder;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('acecommerce:inituser')
            ->setDescription('Encode les groupes et l\'utilisateur admin')
            ->addArgument('test', InputArgument::OPTIONAL, 'For phpunit');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->createDefaultAccount();

        $test = $input->getArgument('test');
    //    if ($test) {
            $this->createForTest();
      //  }
    }

    protected function createDefaultAccount()
    {
        $groupAdmin = $this->em->getRepository(Group::class)->findOneBy(['name' => 'ECOMMERCE_ADMIN']);
        $groupCommerce = $this->em->getRepository(Group::class)->findOneBy(['name' => 'ECOMMERCE_COMMERCE']);
        $groupClient = $this->em->getRepository(Group::class)->findOneBy(['name' => 'ECOMMERCE_CLIENT']);
        $groupLogisticien = $this->em->getRepository(Group::class)->findOneBy(['name' => 'ECOMMERCE_LOGISTICIEN']);
        $adminUser = $this->em->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $userPort = $this->em->getRepository(User::class)->findOneBy(['username' => 'porte']);
        $logisticienUser = $this->em->getRepository(User::class)->findOneBy(['username' => 'logisticien']);
        $clientUser = $this->em->getRepository(User::class)->findOneBy(['username' => 'homer']);
        $admin_acmarche = $this->em->getRepository(Group::class)->findOneBy(['name' => 'ACMARCHE_ADMIN']);

        if (!$groupAdmin) {
            $groupAdmin = new Group("ECOMMERCE_ADMIN");
            $groupAdmin->setTitle('Administrateur de l\'applis lunch');
            $groupAdmin->setDescription('Dispose de tous les droits');
            $groupAdmin->addRole("ROLE_ECOMMERCE_ADMIN");
            $groupAdmin->addRole("ROLE_ECOMMERCE");
            $groupAdmin->addRole("ROLE_ECOMMERCE_COMMERCE");
            $groupAdmin->addRole("ROLE_ECOMMERCE_LOGISTICIEN");
            $groupAdmin->addRole("ROLE_ECOMMERCE_CLIENT");
            $this->em->persist($groupAdmin);
            $this->em->flush();
            $this->output->writeln('Groupe ECOMMERCE_ADMIN créé');
        }

        if (!$groupCommerce) {
            $groupCommerce = new Group("ECOMMERCE_COMMERCE");
            $groupCommerce->setTitle('Accès commerçant lunch');
            $groupCommerce->setDescription('Dispose de tous les droits sur son commerce');
            $groupCommerce->addRole("ROLE_ECOMMERCE_COMMERCE");
            $groupCommerce->addRole("ROLE_ECOMMERCE_CLIENT");
            $groupCommerce->addRole("ROLE_ECOMMERCE");
            $this->em->persist($groupCommerce);
            $this->em->flush();
            $this->output->writeln('Groupe ECOMMERCE_COMMERCE créé');
        }

        if (!$groupClient) {
            $groupClient = new Group("ECOMMERCE_CLIENT");
            $groupClient->setTitle('Accès commerçant lunch');
            $groupClient->setDescription('Peut commander des paniers');
            $groupClient->addRole("ROLE_ECOMMERCE_CLIENT");
            $groupClient->addRole("ROLE_ECOMMERCE");
            $this->em->persist($groupClient);
            $this->em->flush();
            $this->output->writeln('Groupe ECOMMERCE_CLIENT créé');
        }

        if (!$groupLogisticien) {
            $groupLogisticien = new Group("ECOMMERCE_LOGISTICIEN");
            $groupLogisticien->setTitle('Accès logisticien lunch');
            $groupLogisticien->setDescription('Consulte les commandes finalisées');
            $groupLogisticien->addRole("ROLE_ECOMMERCE_LOGISTICIEN");
            $groupLogisticien->addRole("ROLE_ECOMMERCE_CLIENT");
            $groupLogisticien->addRole("ROLE_ECOMMERCE");
            $this->em->persist($groupLogisticien);
            $this->em->flush();
            $this->output->writeln('Groupe ECOMMERCE_LOGISTICIEN créé');
        }

        if (!$adminUser) {
            $adminUser = new User();
            $adminUser->setNom('admin');
            $adminUser->setPrenom('admin');
            $this->setUser($adminUser, "admin@marche.be", "admin");
            $this->em->persist($adminUser);
            $this->output->writeln("L'utilisateur admin a bien été créé");
        }

        if (!$userPort) {
            $userPort = new User();
            $userPort->setNom('Bonne');
            $userPort->setPrenom('Porte');
            $this->setUser($userPort, "porte@marche.be", "porte");
            $this->em->persist($userPort);
            $this->output->writeln("L'utilisateur porte a bien été créé");
        }

        if (!$logisticienUser) {
            $logisticienUser = new User();
            $logisticienUser->setNom('Criquielion');
            $logisticienUser->setPrenom('Claude');
            $this->setUser($logisticienUser, "logisticien@marche.be", "logisticien");
            $this->em->persist($logisticienUser);
            $this->output->writeln("L'utilisateur logisticien a bien été créé");
        }

        if (!$clientUser) {
            $clientUser = new User();
            $clientUser->setUsername('homer');
            $clientUser->setNom('Simpson');
            $clientUser->setPrenom('Homer');
            $this->setUser($clientUser, "homer@marche.be", "homer");
            $this->em->persist($clientUser);
            $this->output->writeln("L'utilisateur homer a bien été créé");
        }

        if (!$userPort->hasGroup($groupCommerce)) {
            $userPort->addGroup($groupCommerce);
            $this->em->persist($userPort);
            $this->output->writeln("L'utilisateur porte a été ajouté dans le groupe commerce");
        }

        if (!$logisticienUser->hasGroup($groupLogisticien)) {
            $logisticienUser->addGroup($groupLogisticien);
            $this->em->persist($logisticienUser);
            $this->output->writeln("L'utilisateur logisticien a été ajouté dans le groupe logisticien");
        }

        if (!$clientUser->hasGroup($groupClient)) {
            $clientUser->addGroup($groupClient);
            $this->em->persist($clientUser);
            $this->output->writeln("L'utilisateur homer a été ajouté dans le groupe client");
        }

        if (!$adminUser->hasGroup($groupAdmin)) {
            $adminUser->addGroup($groupAdmin);
            $this->em->persist($adminUser);
            $this->output->writeln("L'utilisateur admin a été ajouté dans le groupe admin");
        }

        if ($admin_acmarche) {
            if (!$adminUser->hasGroup($admin_acmarche)) {
                $adminUser->addGroup($admin_acmarche);
                $this->em->persist($admin_acmarche);
                $this->output->writeln("L'utilisateur admin a été ajouté dans le groupe acadmin");
            }
        } else {
            $this->output->writeln("Créé le groupe admin_acmarche avec la commande acsecurity:initdata ");
        }

        $this->em->flush();
    }

    protected function createForTest()
    {
        $commerceEnka = $this->em->getRepository(User::class)->findOneBy(['username' => 'enka']);
        $commerceMalice = $this->em->getRepository(User::class)->findOneBy(['username' => 'malice']);
        $clientZora = $this->em->getRepository(User::class)->findOneBy(['username' => 'zora']);
        $groupCommerce = $this->em->getRepository(Group::class)->findOneBy(['name' => 'ECOMMERCE_COMMERCE']);
        $groupClient = $this->em->getRepository(Group::class)->findOneBy(['name' => 'ECOMMERCE_CLIENT']);

        if (!$commerceEnka) {
            $commerceEnka = new User();
            $commerceEnka->setNom('Enka');
            $commerceEnka->setPrenom('Toque');
            $this->setUser($commerceEnka, "enka@marche.be", "enka");
            $this->em->persist($commerceEnka);
            $this->output->writeln("L'utilisateur enka a bien été créé");
        }

        if (!$commerceMalice) {
            $commerceMalice = new User();
            $commerceMalice->setNom('Malice');
            $commerceMalice->setPrenom('Boite');
            $this->setUser($commerceMalice, "malice@marche.be", "malice");
            $this->em->persist($commerceMalice);
            $this->output->writeln("L'utilisateur malice a bien été créé");
        }

        if (!$clientZora) {
            $clientZora = new User();
            $clientZora->setNom('Simpson');
            $clientZora->setPrenom('Zora');
            $this->setUser($clientZora, "zora@marche.be", "zora");
            $this->em->persist($clientZora);
            $this->output->writeln("L'utilisateur zora a bien été créé");
        }

        if (!$commerceEnka->hasGroup($groupCommerce)) {
            $commerceEnka->addGroup($groupCommerce);
            $this->em->persist($commerceEnka);
            $this->output->writeln("L'utilisateur enka a été ajouté dans le groupe commerce");
        }

        if (!$commerceMalice->hasGroup($groupCommerce)) {
            $commerceMalice->addGroup($groupCommerce);
            $this->em->persist($commerceMalice);
            $this->output->writeln("L'utilisateur malice a été ajouté dans le groupe commerce");
        }

        if (!$clientZora->hasGroup($groupClient)) {
            $clientZora->addGroup($groupClient);
            $this->em->persist($clientZora);
            $this->output->writeln("L'utilisateur zora a été ajouté dans le groupe client");
        }

        $this->em->flush();
    }

    protected function setUser(User $user, $email, $mdp)
    {
        $user->setUsername($email);
        $user->setEmail($email);
        $password = $this->encoder->encodePassword($user, $mdp);
        $user->setPassword($password);
    }
}