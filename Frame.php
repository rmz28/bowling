<?php

class Frame {
    public $rolls = [];
    public $isSpare;
    public $isStrike;
    public $frameNo;
    public $isFinal;
    public $frameBonus;
    public $frameTotal;
    public $finalScore;
    const PIN_COUNT = 10;
    const FINAL_FRAME_NO = 10;

    public function __construct($rolls, $frameNo)
    {
        $this->rolls = $rolls;
        $this->setFrameNo($frameNo);
        $this->setIsSpare();
        $this->setIsStrike();
        $this->setIsFinal();
    }

    protected function setIsFinal(): void
    {
        $this->isFinal = $this->frameNo === self::FINAL_FRAME_NO;
    }

    protected function setFrameNo($frameNo): void
    {
        $this->frameNo = $frameNo;
    }
    protected function setIsStrike(): void
    {
        $this->isStrike = $this->rolls['roll_1'] === 10;
    }

    protected function setIsSpare(): void
    {
        if($this->rolls['roll_1'] < self::PIN_COUNT
            && $this->rolls['roll_2'] < self::PIN_COUNT
            && $this->rolls['roll_1'] + $this->rolls['roll_2'] === self::PIN_COUNT)
        {
            $this->isSpare = true;
        } else {
            $this->isSpare = false;
        }
    }
}