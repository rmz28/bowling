<?php
require 'Frame.php';

class Calculator {
	
	protected $scores = [];
	protected $currentFrameData;
	protected $currentFrame;
	const PIN_COUNT = 10;
	const FINAL_FRAME_NO = 10;

	public function loadScores(array $scores): void
	{
        $count = 0;
        foreach($scores as $frame){
            $count++;
            $this->scores[$frame['frameNo']] = new Frame($frame['rolls'], $count);
        }
	}

	protected function setCurrentFrame(): void
	{
		$this->currentFrame = count($this->scores) + 1;
	}

	public function setCurrentFrameData(array $currentFrameData): void
	{
        $this->setCurrentFrame();

        if($this->currentFrame > self::FINAL_FRAME_NO){
            die('Game is over');
        }

		$currentFrameData = array_map(function($value){
				return (int)$value;
			}, $currentFrameData);

		$this->currentFrameData['rolls'] = $currentFrameData;
        $this->scores[$this->currentFrame] = new Frame($this->currentFrameData['rolls'], $this->currentFrame);

        //backend validation | only final frame has third roll
        if(!$this->scores[$this->currentFrame]->isFinal){
            unset($this->scores[$this->currentFrame]->rolls['roll_3']);
        }
	}

	public function calculateScores(): array
	{
		foreach($this->scores as $frameObj){
            $previousFrameObj = $frameObj->frameNo > 1 ? $this->scores[$frameObj->frameNo-1] : null;
            $previousFrameScore = !is_null($previousFrameObj) ? $previousFrameObj->finalScore : 0;
            $this->setFrameTotalScore($frameObj);

            if(!is_null($frameObj->frameTotal)){
                $frameObj->setFinalScore($previousFrameScore + $frameObj->frameTotal);
            }
        }

        return $this->scores;
	}

    protected function setFrameTotalScore(Frame $frameObj): void
    {
        $frameTotal = null;

        //usual frames
        if (!$frameObj->isFinal) {
            if (
                !$frameObj->isSpare
                && !$frameObj->isStrike
            ) {
                $frameTotal = $frameObj->rolls['roll_1'] + $frameObj->rolls['roll_2'];
            } elseif ($frameObj->isSpare) {
                $this->setFrameBonus($frameObj, 'spare');
                $frameTotal = !is_null($frameObj->frameBonus)
                    ? (self::PIN_COUNT + $frameObj->frameBonus)
                    : null;
            } elseif ($frameObj->isStrike) {
                $this->setFrameBonus($frameObj, 'strike');
                $frameTotal = !is_null($frameObj->frameBonus)
                    ? (self::PIN_COUNT + $frameObj->frameBonus)
                    : null;
            }
        } else {
            //last (10th) frame
            $frameTotal = array_sum($frameObj->rolls);
        }

        $frameObj->setFrameTotal($frameTotal);
    }

    protected function setFrameBonus(Frame $frameObj, string $bonusType): void
    {
        $nextFrame = $frameObj->frameNo+1;
        $nextFrameObj = $this->isFrameRolled($nextFrame) ? $this->scores[$nextFrame] : null;

        $bonus = null;

        if(!is_null($nextFrameObj)){
            if($bonusType === 'spare'){
                $bonus = $nextFrameObj->rolls['roll_1'];
            } elseif($bonusType === 'strike') {
                if($frameObj->frameNo <= (self::FINAL_FRAME_NO - 2)){
                    if($nextFrameObj->isStrike){
                        $furtherFrame = $nextFrame+1;

                        if($this->isFrameRolled($furtherFrame)){
                            $furtherFrameObj = $this->scores[$nextFrame+1];
                            $bonus = $nextFrameObj->rolls['roll_1']
                                + $furtherFrameObj->rolls['roll_1'];
                        }
                    } else {
                        $bonus = $nextFrameObj->rolls['roll_1'] + $nextFrameObj->rolls['roll_2'];
                    }
                } else {
                    $bonus = $nextFrameObj->rolls['roll_1'] + $nextFrameObj->rolls['roll_2'];
                }

            } else {
                die('Wrong bonus type');
            }
        }

        $frameObj->setFrameBonus($bonus);
    }

    protected function isFrameRolled(int $frame): bool
    {
        return isset($this->scores[$frame]);
    }
}