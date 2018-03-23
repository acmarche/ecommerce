<?php

namespace App\DataFixtures\ORM;

Class FixtureLunchConstant
{
    const CATEGORIES = [
        'bio.jpg' => 'Frais, bio',
        'boissons.jpg' => 'Boissons et vins',
        'classic.jpg' => 'Sandwichs classiques',
        'specials.jpg' => 'Sandwichs spécialisés',
        'chauds.jpg' => 'Plats chauds',
        'chocolats.jpg' => 'Chocolateries',
    ];

    const COMMERCES = ["porte", "grain", "leonidas", "enka", "pause"];

    const PRODUIS_LUNCH_CLASSIQUES = [
        ["grain" => 'Américain (pur boeuf)'],
        ["grain" => 'Boulette'],
        ["porte" => 'Chèvre'],
        ["porte" => 'Crabe'],
        ["grain" => 'Gouda'],
        ["porte" => 'Poulet andalouse'],
        ["pause" => 'Poulet curry'],
        ["pause" => 'Philadelphia fines herbes'],
        ["enka" => 'Roti moutarde'],
        ["enka" => 'Crevettes'],
        ["enka" => 'Saumon fumé'],
        ["leonidas" => 'Poulet mayonnaise'],
        ["leonidas" => 'Thon piquant'],
        ["leonidas" => 'Thon mayonnaise'],
    ];

    const PRODUIS_LUNCH_SPECIALISE = [
        ["porte" => 'Anglais'],
        ["enka" => 'Ardennais'],
        ["leonidas" => 'Berger'],
        ["leonidas" => 'Campagnard'],
        ["grain" => 'Martino'],
        ["pause" => 'Norvégien'],
        ["leonidas" => 'Niçois'],
        ["leonidas" => 'Paysan'],
        ["porte" => 'Tartare italien'],
        ["enka" => 'Toscane'],
        ["grain" => 'Pise'],
        ["enka" => 'Club'],
    ];

    const INGREDIENTS = [
        'pain de viande',
        'andalouse',
        'oignons',
        'salade',
        'oeuf',
        'jambon fumé',
        'mayonnaise',
        'chèvre',
        'lard grillé',
        'miel',
        'roasbeef',
        'tomates',
        'cressonnette',
        'sauce yaourt pesto rouge',
        'jambon',
        'fromage',
        'concombre',
        'maïs',
        'cornichons',
        'moutarde',
        'anchois',
        'olives',
        'mozzarella',
        'herbes de Provence',
        'vinaigre balsamique',
        'roquette',
        'parmesan',
        'poulet',
        'huile d\'olive',
        'crabe',
        'émincés poulet tex-mex',
        'cerneaux de noix',
    ];

    const SUPLLEMENTS = [
        'Ananas',
        'Anchois',
        'Bacon',
        'Boulette entière',
        'Brie',
        'Carotte',
        'Chèvre',
        'Concombre',
        'Cornichons',
        'Cressonnette',
        'Gouda',
        'Jambon cui',
        'Jambon italien',
        'Maïs ',
        'Noix',
        'euf(1/2)',
        'Oignons frais',
        'Oignons frits ',
        'Oignons vinaigre',
        'Olive ',
        'Parmesan',
        'Pesto rouge',
        'Pesto vert',
        'Philadelphia fines herbes',
        'Philadelphia nature',
        'Pignons',
        'Pêche ',
        'Roquette',
        'Roti de dinde',
        'Salade classique',
        'Salami',
        'Saumon fumé',
        'Tomates fraiches',
        'Tomates séchées ',
    ];

    const SAUCES = [
        'Sauce andalouse',
        'Sauce bicky',
        'Sauce cocktail',
        'Sauce ketchup',
        'Sauce martino(piquant)',
        'Sauce mayonnaise',
        'Sauce moutarde',
    ];
}