<?php

namespace App\DataFixtures;

use App\Entity\Artiste;
use App\Repository\ArtisteRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Album;

class AppFixtures extends Fixture
{
    /**
     * Generates initialization data for artistes : [Nom, NbMembres, Genre]
     * @return \\Generator
     */
    private static function artistesDataGenerator()
    {
        yield ["Pink Floyd", 4, "Rock Progressif"];
    }

    /**
     * Generates initialization data for artiste albums:
     *  [Nom, Annee, Genre, Artiste_nom]
     * @return \\Generator
     */
    private static function artisteAlbumsGenerator()
    {
        yield ["Wish you were here", 1975, "Rock Progressif", "Pink Floyd"];
    }

    public function load(ObjectManager $manager)
    {
        $artisteRepo = $manager->getRepository(artiste::class);

        foreach (self::artistesDataGenerator() as [$nom, $nbMembres, $genre] ) {
            $artiste = new artiste();
            $artiste->setNom($nom);
            $artiste->setNbMembres($nbMembres);
            $artiste->setGenre($genre);
            $manager->persist($artiste);          
        }
        $manager->flush();

        foreach (self::artisteAlbumsGenerator() as [$nom, $annee, $genre, $artiste_nom])
        {
            $artiste = $artisteRepo->findOneBy(['Nom' => $artiste_nom]);
            $album = new Album();
            $album->setNom($nom);
            $album->setAnnee($annee);
            $album->setGenre($genre);
            $artiste->addAlbum($album);
            // there's a cascade persist on fim-albums which avoids persisting down the relation
            $manager->persist($artiste);
            $manager->persist($album);
        }
        $manager->flush();
    }
}
