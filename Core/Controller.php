<?php

class Controller
{
    protected Database $db;
    function __construct(Database $db)
    {
        $this->db= $db;
    }
}