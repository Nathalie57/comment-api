<?php

namespace App\Controller;

use App\Entity\Answer;
use Doctrine\ORM\EntityManagerInterface;

class ValidReportAnswerController {

    /**@var EntityManagerInterface */
    private $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    
    public function __invoke(Answer $data)
    {
        if($data->getStatus()=='REPORTED') $data->setStatus('NOTDISPLAYED');
        
        $this->manager->flush();
        dd($data);
    }
}