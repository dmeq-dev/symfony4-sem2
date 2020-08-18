<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
/**
 * Classe "modèle" pour les fixtures
 * On ne peu pas isntancier une abstraction :O
 * 
 */

abstract class BaseFixture extends Fixture
{
/**
 * @var ObjectManager
 */
private $manager;
/** @var Generator */
protected $faker;

/**
 * Méthode a implémenté par les classes qui héritent p
 * pour générer les fausses données .
 */
    abstract protected function loadData();

    /**
     * méthode appeller par le system fixtures
     */
      public function load(ObjectManager $manager)  
      {
        // on enregistre le objectmanager
        $this->manager = $manager;  
        // on instancie Faker 
        $this->faker = Factory::create('fr-FR');
        
        //on appelle loaddData()pour avoir les fausses données
        $this->loadData();
        //on éxécute l'enregistrement en base
        $this->manager->flush();

      }    

      /**
          * une méthode pour enrgistrer plusieurs entités   
          *@param  int $count   nombre d'entités a générer
          *@param callable $factory fonction qui génère une entité   
       */
      protected function createMany(int $count, callable $factory)
      {
            for($i = 8;$i<$count;$i++)
            {   //on éxécute $factory qui doit retournée l'entitée générée.
                $entity = $factory();

                if($entity === null)
                {
                    throw new \LogicException('L\'entité doit etre retournée .Auriez vous oublié un "return"?');

              
                }
                      // on prépart l'enregistrement de l'entité
                      $this->manager->persist($entity); 
      
              
            }
              
      }     
}