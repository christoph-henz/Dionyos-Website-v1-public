<?php declare(strict_types=1);

// classes/DBArticle.php

class DBArticle
{
    private string $articleId;
    private string $articleName;
    private string $articleDescription;
    private float $articlePrice;
    private int $quantity;

    /**
     * Konstruktor zur Initialisierung eines Artikels.
     *
     * @param string $articleId ID des Artikels
     * @param string $name Name des Artikels
     * @param string $description Beschreibung des Artikels
     * @param float $price Preis des Artikels
     * @param int $quantity Menge des Artikels (Standard: 1)
     */
    public function __construct(string $articleId, string $name/*, string $description*/, float $price, int $quantity = 1)
    {
        $this->articleId = htmlspecialchars($articleId, ENT_QUOTES, 'UTF-8');
        $this->articleName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        //$this->articleDescription = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
        $this->articlePrice = $price;
        $this->quantity = $quantity;
    }

    /**
     * Gibt die ID des Artikels zurück.
     *
     * @return string
     */
    public function getArticleId(): string
    {
        return $this->articleId;
    }

    /**
     * Gibt den Namen des Artikels zurück.
     *
     * @return string
     */
    public function getArticleName(): string
    {
        return $this->articleName;
    }

    /**
     * Gibt die Beschreibung des Artikels zurück.
     *
     * @return string
     */
    public function getArticleDescription(): string
    {
        return $this->articleDescription;
    }

    /**
     * Gibt den Preis des Artikels zurück.
     *
     * @return float
     */
    public function getArticlePrice(): float
    {
        return $this->articlePrice;
    }

    /**
     * Gibt die Menge des Artikels zurück.
     *
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Setzt die Menge des Artikels.
     *
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        if ($quantity > 0) {
            $this->quantity = $quantity;
        }
    }

    /**
     * Erhöht die Menge des Artikels um 1.
     *
     * @return void
     */
    public function addInstance(): void
    {
        $this->quantity += 1;
    }

    public function sumUpf() :float
    {
        return $this->articlePrice * $this->quantity;
    }
    /**
     * Berechnet den Gesamtpreis des Artikels (Preis * Menge).
     *
     * @return float
     */

    public function sumUp()
    {
        return number_format($this->articlePrice * $this->quantity,2,',','');
    }
}
?>
