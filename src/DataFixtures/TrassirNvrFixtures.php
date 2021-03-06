<?php


namespace App\DataFixtures;


use App\Entity\TrassirNvr;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TrassirNvrFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $ipArray = [
            '10.18.144.33',
            '10.18.122.33',
            '10.18.242.33',
            '10.13.24.17',
            '10.17.8.33',
            '10.18.68.33',
            '10.18.200.33',
            '10.18.18.33',
            '10.18.32.33',
            '10.18.250.33',
            '10.18.236.33',
            '10.18.128.33',
            '10.17.20.33',
            '10.18.22.33',
            '10.18.48.33',
            '10.18.36.33',
            '10.18.212.33',
            '10.18.228.33',
            '10.17.36.33',
            '10.18.24.33',
            '10.18.244.33',
            '10.18.216.33',
            '10.13.24.23',
            '10.13.24.28',
            '10.13.24.31',
            '10.13.24.16',
            '10.17.14.33',
            '10.18.246.33',
            '10.18.224.33',
            '10.18.230.33',
            '10.18.84.33',
            '10.18.218.33',
            '10.17.26.33',
            '10.18.138.33',
            '10.18.34.33',
            '10.18.42.33',
            '10.18.58.33',
            '10.18.142.33',
            '10.17.10.33',
            '10.18.46.33',
            '10.18.54.33',
            '10.18.38.33',
            '10.18.232.33',
            '10.26.1.33',
            '10.26.1.34',
            '10.18.166.33',
            '10.18.30.33',
            '10.18.134.33',
            '10.18.164.33',
            '10.18.180.33',
            '10.18.238.33',
            '10.18.40.33',
            '10.17.46.33',
            '10.17.64.33',
            '10.18.20.33',
            '10.18.148.33',
            '10.17.12.33',
            '10.18.70.33',
            '10.18.4.33',
            '10.18.136.33',
            '10.18.16.33',
            '10.18.64.33',
            '10.17.62.33',
            '10.23.30.33',
            '10.18.252.33',
            '10.17.40.33',
            '10.17.24.33',
            '10.17.56.33',
            '10.18.12.33',
            '10.17.38.33',
            '10.17.58.33',
            '10.18.26.33',
            '10.18.8.33',
            '10.18.248.33',
            '10.18.56.33',
            '10.18.176.33',
            '10.18.78.33',
            '10.18.196.33',
            '10.18.74.33',
            '10.18.50.33',
            '10.18.28.33',
            '10.17.60.33',
            '10.17.48.33',
            '10.18.2.33',
            '10.18.184.33',
            '10.18.156.33',
            '10.18.76.33',
            '10.18.60.33',
            '10.17.42.33',
            '10.18.140.33',
            '10.18.82.33',
            '10.17.4.33',
            '10.18.44.33',
            '10.17.32.33',
            '10.17.2.33',
            '10.18.72.33',
            '10.18.6.33',
            '10.18.52.33',
            '10.18.124.33',
            '10.18.132.33',
            '10.17.130.33',
            '10.17.104.33',
        ];

        foreach ($ipArray as $currentIp){
            $nvr = new TrassirNvr();
            $nvr->setIp($currentIp);
            $manager->persist($nvr);
        }

        $manager->flush();
    }

}