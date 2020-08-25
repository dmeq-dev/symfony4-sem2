<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Exception;

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
 * @var array liste des références connues
 */
private $references= [];
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
          *@param string $groupName nom du groupe de références
       */
      protected function createMany(int $count, string $groupName, callable $factory)
      {
            for($i = 0;$i<$count;$i++)
            {   //on éxécute $factory qui doit retournée l'entitée générée.
                $entity = $factory($i);

                if($entity === null)
                {
                    throw new \LogicException('L\'entité doit etre retournée .Auriez vous oublié un "return"?');

              
                }
                      // on prépart l'enregistrement de l'entité
                      $this->manager->persist($entity); 


                     // on enregistre une référence a l'entité
                      $references = sprintf('%s_%d', $groupName, $i);  
                      $this->addReference($references, $entity);
              
            }
              
      }   
      /**
       * récupérer une entité par son nom de groupe de références
       * @param string $groupName nom de groupe de références
       *  */  
      protected function getRandomReference(string $groupName)
      {
        // vérifier si  on a déjas enregistré les références du groupe demandé
        if(!isset($this->references[$groupName])){
          //so npn , on va rechercher les références
          $this->references[$groupName]=[];
          // on parcour la liste de toutes les références (toutes classes confondues)
          foreach($this->referenceRepository->getReferences() as $key => $ref){
            //la clé $key correspond a nos références
            //si $key commence par $groupName , on le sauvegarde
            if(strpos($key,$groupName) === 0) {
              $this->references[$groupName][] = $key;

            }
          }
        }
          //cérifier que  l'on a récupéré des références
        if($this->references[$groupName]===[]) {
          throw new \Exception(sprintf('Aucune référence trouver pour le groupe "%s"',$groupName));

        }
        // retourner une entité corresspondant a une référence aléatoire
        $randomReference = $this->faker->randomElement($this->references[$groupName]);
        return $this->getReference($randomReference);
      }
}