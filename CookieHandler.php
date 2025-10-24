<?php declare(strict_types=1);

class CookieHandler
{

    public const ALLOW_GOOGLE_KEY = "accept-google";
    public const ALLOW_ORDER_KEY = "accept-order";
    public const ASKED_BEFORE_KEY = "cookie-flag";

    private bool $askedBefore;

    private bool $allowGoogle;

    private bool $allowOrder;

    private static CookieHandler $instance;

    private function __construct()
    {
        session_start();
        if(!isset($_SESSION[self::ASKED_BEFORE_KEY])){
            $_SESSION[self::ASKED_BEFORE_KEY] = false;
        }

        if(!isset($_SESSION[self::ALLOW_GOOGLE_KEY])){
            $_SESSION[self::ALLOW_GOOGLE_KEY] = false;
        }

        if(!isset($_SESSION[self::ALLOW_ORDER_KEY])){
            $_SESSION[self::ALLOW_ORDER_KEY] = false;
        }
        $this->updatePrefs();

    }

    public static function getInstance(){
        if(!isset(self::$instance)) self::$instance = new CookieHandler();
        return self::$instance;
    }

    /**
     * @return bool
     */
    public function isAllowGoogle(): bool
    {
        return $this->allowGoogle;
    }

    /**
     * @return bool
     */
    public function isAllowOrder(): bool
    {
        return $this->allowOrder;
    }

    /**
     * @param bool $allowGoogle
     */
    public function setAllowGoogle(bool $allowGoogle): void
    {
        if($this->askedBefore){
            $this->allowGoogle = $allowGoogle;
            $_SESSION[self::ALLOW_GOOGLE_KEY] = $allowGoogle;
        }
    }

    /**
     * @param bool $allowOrder
     */
    public function setAllowOrder(bool $allowOrder): void
    {
        if($this->askedBefore){
            $this->allowOrder = $allowOrder;
            $_SESSION[self::ALLOW_ORDER_KEY] = $allowOrder;
        }

    }

    /**
     * @return bool
     */
    public function hasAskedBefore(): bool
    {
        return $this->askedBefore;
    }

    /**
     * @param bool $askedBefore
     */
    public function setAskedBefore(bool $askedBefore): void
    {
        $this->askedBefore = $askedBefore;
        $_SESSION[self::ASKED_BEFORE_KEY] = $askedBefore;
    }



    private function updatePrefs(){
        if(isset($_SESSION['cookie-flag'])){
            $_SESSION[self::ASKED_BEFORE_KEY] ? $this->askedBefore = true : $this->askedBefore = false;
            $_SESSION[self::ALLOW_GOOGLE_KEY] ? $this->allowGoogle = true : $this->allowGoogle = false;
            $_SESSION[self::ALLOW_ORDER_KEY] ? $this->allowOrder = true : $this->allowOrder = false;
        }
    }



}