<?php

namespace h4kuna;

/**
 * @author Milan Matějček
 */
class ArrayStep extends \ArrayIterator {

    const POINTER = 0;
    const LOOP = 1;
    const REVERSE = 2;
    const IN_REVERSE = 4;

    protected $setup = self::LOOP;
    protected $pointer = self::POINTER;

//----------uprava standartnich metod-------------------------------------------

    /**
     * je-li v rozsahu pole
     * @return bool
     */
    public function valid() {
        if ($this->pointer < 0) {
            return FALSE;
        }

        return parent::valid();
    }

    /**
     * posune vnitrni ukazatel na další
     * @return void
     */
    public function next() {
        if ($this->reverse('prev')) {
            return;
        }
        ++$this->pointer;
        parent::next();
        $this->checkEndOfLoop();
    }

    /**
     * opak pro $this->next();
     * @return mixed
     */
    public function prev() {
        if ($this->reverse('next')) {
            return;
        }
        --$this->pointer;
        $this->seek($this->pointer);
        $this->checkEndOfLoop();
    }

    /**
     * rotuje ukazatel na konec nebo začátek
     * @return void
     */
    public function rewind() {
        if ($this->isReverse()) {
            return $this->setEnd();
        }

        $this->setStart();
    }

    /**
     * nastaví podle vnitřního ukazatele
     */
    public function seek($pointer) {
        $pointer = (int) $pointer;
        try {
            parent::seek($pointer);
            $this->pointer = $pointer;
        } catch (\OutOfBoundsException $e) {
            if ($this->pointer != -1) {
                throw $e;
            }
        }
    }

//-------------------nove metody------------------------------------------------

    /**
     *
     * @param $flag
     * @return this
     */
    public function setup($flag = 0) {
        if ($flag < 0 || $flag > 3) {
            throw new \RuntimeException('You can setup 0, 1, 2 as constants.');
        }
        $this->setup = $flag;
        if ($this->isReverse()) {
            return $this->setEnd();
        }
        return $this->setStart();
    }

    /**
     * @return bool
     */
    public function isLoop() {
        return (bool) ($this->setup & self::LOOP);
    }

    /**
     * @return bool
     */
    public function isReverse() {
        return (bool) ($this->setup & self::REVERSE);
    }

    /**
     * @internal
     * @return bool
     */
    private function isInReverse() {
        return (bool) ($this->setup & self::IN_REVERSE);
    }

    /**
     * posun ukazatele o jedno
     * @param bool $move
     * @return void
     */
    public function move($next = TRUE) {
        if ($next) {
            return $this->next();
        }
        $this->prev();
    }

    /**
     * vrati aktualni hodnotu a posune vnitrni ukazatel na dalsi polozku
     * @param bool $next
     * @return mixed
     */
    public function item($next = TRUE) {
        $current = $this->current();
        $this->move($next);
        return $current;
    }

    /**
     * vrati aktualni klic a posune vnitrni ukazatel na dalsi polozku
     * @param bool $next
     * @return mixed
     */
    public function itemKey($next = TRUE) {
        $current = $this->key();
        $this->move($next);
        return $current;
    }

    /**
     * je poslední
     * @return bool
     */
    public function isLast() {
        return $this->pointer === $this->last();
    }

    /**
     * je první
     * @return bool
     */
    public function isFirst() {
        return $this->pointer === self::POINTER;
    }

    /**
     * je sudá
     * @return bool
     */
    public function isEven() {
        return (bool) ($this->pointer % 2);
    }

    /**
     * je lichý
     * @return bool
     */
    public function isOdd() {
        return (bool) ($this->pointer % 2);
    }

    /**
     * nastavi ukazatel na konec pole
     * @return mixed
     */
    public function end() {
        $this->setEnd();
        return $this->current();
    }

//---------------prace s pointrem-----------------------------------------------

    /**
     * vrati posledni hodnotu ukazatele (pointer)
     * @return int
     */
    public function last() {
        return max(0, parent::count() - 1);
    }

    /**
     * vrati vnitřní ukazatel
     * @return int
     */
    public function getPointer() {
        return $this->pointer;
    }

    /**
     * nastavi vnitrni ukazatel
     * @param int unsigned $pointer
     * @return int
     * @throw \OutOfBoundsException
     */
    public function setPointer($pointer, $interval = TRUE) {
        $pointer = (int) $pointer;
        if ($interval) {
            $pointer = (int) Math::interval($pointer, self::POINTER, $this->last());
        }

        $this->seek($pointer);
        return $this;
    }

    /**
     * vrati hodnotu na dannem pointeru
     * @param int $pointer
     * @return mixed
     */
    public function getValueByPointer($pointer) {
        $point = $this->pointer;
        $this->setPointer($pointer);
        $val = $this->current();
        $this->seek($point);
        return $val;
    }

    /**
     * vrati klic na dannem pointru
     * @param int $pointer
     * @return mixed
     */
    public function getKeyByPointer($pointer) {
        $point = $this->pointer;
        $this->setPointer($pointer);
        $val = $this->key();
        $this->seek($point);
        return $val;
    }

    /**
     * přejede-li se pole tak ho přetočí
     * @return type
     */
    private function checkEndOfLoop() {
        if ($this->isLoop() && !$this->valid()) {
            if ($this->pointer == -1) {
                return $this->setEnd();
            }
            $this->setStart();
        }
    }

    /**
     * jedná li se o opačný chod
     * @param type $method
     * @return boolean
     */
    private function reverse($method) {
        if ($this->isReverse()) {
            $this->setup += self::REVERSE;
            $this->{$method}();
            return TRUE;
        }

        if ($this->isInReverse()) {
            $this->setup -= self::REVERSE;
        }
        return FALSE;
    }

    /**
     * nastaví ukazatel na poslední záznam
     */
    private function setEnd() {
        $this->seek($this->last());
        return $this;
    }

    /**
     * nastaví ukazatel na první záznam
     */
    private function setStart() {
        $this->pointer = self::POINTER;
        parent::rewind();
        return $this;
    }

}
