<?php

class Article
{
    public string $PLU;
    public string $Name;
    public string $Description;
    public float $Price;
    public string $Category;

    public function __construct(string $PLU, string $Name, string $Description, float $Price, string $Category)
    {
        $this->PLU = $PLU;
        $this->Name = $Name;
        $this->Description = $Description;
        $this->Price = $Price;
        $this->Category = $Category;
    }
}
