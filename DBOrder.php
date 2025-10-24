<?php declare(strict_types=1);

include_once 'DBArticle.php';

class DBOrder
{
    private string $name;
    private string $street;
    private int $PLZ;
    private string $city;
    private string $address;
    private string $phoneNumber;
    private string $email;
    private string $note;
    private array $articles;

    /**
     * @param string $name
     * @param string $address
     * @param string $phoneNumber
     */
    public function __construct(string $name = "", string $street = "", int $PLZ = 63739, string $city = "", string $phoneNumber = "")
    {
        $this->name = $name;
        $this->street = $street;
        $this->PLZ = $PLZ;
        $this->city = $city;
        $this->phoneNumber = $phoneNumber;
        $this->email = "";
        $this->note = "";
        $this->articles = array();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return int
     */
    public function getPLZ(): int
    {
        return $this->PLZ;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @param int $PLZ
     */
    public function setPLZ(string $PLZ): void
    {
        $this->PLZ = (int)$PLZ;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function addArticle(DBArticle $article):void{
        if(!array_key_exists($article->getArticleId(), $this->articles)){
            $this->articles[$article->getArticleId()] = $article;
        }else{
            $this->articles[$article->getArticleId()]->addInstance();
        }
    }

    public function getSum(){
        $sum = 0;
        foreach($this->articles as $a){
            $sum += $a->sumUpf();
        }
        return number_format($sum,2,',','');
    }

    /**
     * @return array
     */
    public function getArticles(): array
    {
        return $this->articles;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote(string $note): void
    {
        $this->note = $note;
    }


}