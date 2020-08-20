<?php


namespace App\DataFixtures;

use App\Entity\Label;

class LabelFixtures extends  BaseFixture 
{
    protected function loadData()
    {
        
        $this->createMany(10,'label', function() 
        {
            
            return(new Label())
            ->setName($this->faker->name . 'prod');
            
        });
    }
}




?>