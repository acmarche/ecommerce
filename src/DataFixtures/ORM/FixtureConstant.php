<?php

namespace App\DataFixtures\ORM;

Class FixtureConstant
{
    const CATEGORIES = [
        'jeux.jpg' => 'Jeux',
        'outils.jpg' => 'Outils bricolages',
        'papeterie.jpg' => 'Papeterie',
        'maison.jpg' => 'Maison décos',
    ];

    const COMMERCES = ["boite"];

    const PRODUIS_JEUX = [
        ["malice" => 'Cheval bascule'],
        ["malice" => 'Voiture batman'],
        ["malice" => 'Ballon extérieur'],
        ["malice" => 'Poupée barbie'],
    ];

    const PRODUIS_OUTILS = [
        ["lobet" => 'Marteau'],
        ["lobet" => 'Scie'],
        ["lobet" => 'Tourne vis'],
    ];

    const PRODUIS_PAPETERIE = [
        ["memo" => 'Gomme'],
        ["memo" => 'Crayon'],
        ["memo" => 'Marqueur'],
    ];

}