<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Product;
use App\Entity\BeerType;
use App\Entity\Provider;
use App\Entity\ProductionType;
use App\Repository\BeerTypeRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture 
{
    private Generator $faker;
    
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }


    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i=0; $i<6; $i++) {
            $provider = new Provider();
            $provider->setName($this->faker->company())
                    ->setadress($this->faker->address())
                    ->setCp($this->faker->postcode())
                    ->setCity($this->faker->city())
                    ->setWebsite($this->faker->url());
                    $manager->persist($provider);
                }
        
        for($i=0; $i<30; $i++) {
            $beerTypeRepo = $manager->getRepository(BeerType::class);
            dd($beerTypeRepo->find(1));
            
            //dd($styles);
            $product = new Product();
            $product->setDesignation($this->faker->sentence(2))
                ->setDescription($this->faker->paragraph(1))
                ->setPrice($this->faker->numberBetween(100, 990))
                ->setQuantity(1)
                ->setStock($this->faker->numberBetween(0,50))
                ->setAvailable( $this->faker->boolean())
                ->setProvider($provider)
                ->setVolume($this->faker->randomElement(['25', '33', '75', '44']))
                ->setIngredients($this->faker->text())
                ->setAlcoholLevel($this->faker->randomFloat(1, 0, 12))
                ->setBitterness($this->faker->numberBetween(5,100))
                ->addBeerType($beerType);
            // for($k = 0; mt_rand(1,2); $k++){
            //      $product->setProductionType($this->faker->randomElement())
            // }
            // ->addBeerType($this->faker->addEntity(BeerType::class, 1));
            // dd($product);
           
            //         ->setSlug($this->faker->unique()->slug(4))
            //         ->setImageFile($this->faker->image(360, 360, 'beer' ));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
