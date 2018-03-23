<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/03/18
 * Time: 12:11
 */

namespace App\Manager;

use App\Entity\Attribut\Attribut;
use App\Entity\Client\Adresse;
use App\Entity\InterfaceDef\AttributInterface;
use App\Entity\InterfaceDef\CommandeProduitInterface;
use App\Entity\InterfaceDef\ListingAttributsInterface;
use App\Entity\Security\User;
use App\Manager\InterfaceDef\AttributManagerInterface;
use App\Repository\Attribut\AttributRepository;
use App\Repository\Client\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;

class AdresseManager extends AbstractManager
{
    private $adresseRepository;

    public function __construct(EntityManagerInterface $entityManager, AdresseRepository $attributRepository)
    {
        $this->adresseRepository = $attributRepository;
        parent::__construct($entityManager);
    }

    /**
     * @return Adresse
     */
    public function newInstance(User $user)
    {
        $adresse = new Adresse();
        $adresse->setNom($user->getPrenom().' '.$user->getNom());
        $adresse->setUser($user->getUsername());

        return $adresse;
    }

    public function insert(Adresse $adresse)
    {
        $this->persist($adresse);
        $this->flush();
    }

    public function delete(Adresse $adresse)
    {
        $this->remove($adresse);
        $this->flush();
    }


}