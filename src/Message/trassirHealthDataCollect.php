<?php


namespace App\Message;


class trassirHealthDataCollect
{
    private $nvrId;

    public function __construct($nvrId)
    {
        $this->nvrId = $nvrId;
    }


    public function getNvrId()
    {
        return $this->nvrId;
    }

}