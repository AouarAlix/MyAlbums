<?php

namespace App\DataFixtures;

use App\Entity\Artiste;
use App\Repository\ArtisteRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Album;
use App\Entity\Bibliotheque;
use App\Entity\User;
use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class AppFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Generates initialization data for artistes : [Nom, NbMembres, Genre]
     * @return \\Generator
     */
    private static function artistesDataGenerator()
    {
        yield ["Pink Floyd", 4, "Rock Progressif"];
        yield ["Michael Jackson", 1, "Pop"];
        yield ["AC/DC", 5, "Hard Rock"];
        yield ["The Rolling Stones", 4, "Rock"];
        yield ["Miles Davis", 1, "Jazz"];
        yield ["Beyonce", 1, "RnB"];
        yield ["Eminem", 1, "Rap"];
        yield ["Lin Manuel Miranda", 1, "Comedie Musicale"];
        yield ["The Beatles", 4, "Rock"];
    }

    /**
     * Generates initialization data for artiste albums:
     *  [Nom, Annee, Genre, Artiste_nom]
     * @return \\Generator
     */
    private static function artisteAlbumsGenerator()
    {
        yield ["Wish you were here", 1975, "Rock Progressif", "Pink Floyd"];
        yield ["Thriller", 1982, "Pop", "Michael Jackson"];
        yield ["Bad", 1987, "Pop", "Michael Jackson"];
        yield ["Dangerous", 1991, "Pop", "Michael Jackson"];
        yield ["Highway to Hell", 1979, "Hard Rock", "AC/DC"];
        yield ["Back in Black", 1980, "Hard Rock", "AC/DC"];
        yield ["Sketches of Spain", 1960, "Jazz", "Miles Davis"];
        yield ["Kind of Blue", 1959, "Jazz", "Miles Davis"];
    }

    /**
     *
     * @return \\Generator
     */
    private static function bibliothequeGenerator()
    {
        yield ["Rock'n roll", "Mon repretoire de rock préféré !", "pierre@localhost", ["Back in Black", "Highway to Hell", "Wish you were here"]];
        yield ["Le king", "J'adore Michael Jackson", "paul@localhost", ["Dangerous", "Thriller"]];
        
    }

    public function load(ObjectManager $manager)
    {
        $artisteRepo = $manager->getRepository(artiste::class);
        $albumRepo = $manager->getRepository(album::class);
        $userRepo = $manager->getRepository(user::class);

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

        foreach (self::bibliothequeGenerator() as [$nom, $description, $proprietaire_email, $albums_nom])
        {
            $biblio = new Bibliotheque();
            $biblio->setNom($nom);
            $biblio->setDescription($description);
            $biblio->setProprietaire($userRepo->findOneBy(['email' => $proprietaire_email]));
            foreach ($albums_nom as $album_nom){
                $biblio->addAlbum($albumRepo->findOneBy(['Nom' => $album_nom]));
            }
            // there's a cascade persist on fim-albums which avoids persisting down the relation
            $manager->persist($biblio);
            $manager->persist($album);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
