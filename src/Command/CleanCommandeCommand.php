<?php

namespace App\Command;

use App\Entity\Commande\Commande;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanCommandeCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('acecommerce:cleancommande')
            ->setDescription('Supprime les commandes qui n\'ont aucun produits');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandes = $em->getRepository(Commande::class)->getCommandeObsolete(new \DateTime());
        $count = count($commandes);

        $commandeUtil->cleanCommandeWithoutProduit($commandes);

        $em->flush();
        $output->writeln($count . ' commandes effacees');
    }

}
