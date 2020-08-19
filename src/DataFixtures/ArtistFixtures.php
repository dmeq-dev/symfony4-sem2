<?php


namespace App\DataFixtures;

use App\Entity\Artist;

class ArtistFixtures extends  BaseFixture 
{
    protected function loadData()
    {
        // Créer 50 artistes
        $this->createMany(50,'artist', function() 
        {
            
            return(new Artist())
            ->setName($this->faker->name)
            ->setDescription($this->faker->optional()->realText());

        });
    }
}




?>