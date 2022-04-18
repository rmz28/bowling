<?php

class Calculator {
	
	protected $scores = [];
	protected $currentFrameData;
	protected $currentFrame;
	const PIN_COUNT = 10;
	const FINAL_FRAME_NO = 10;

	public function loadScores(array $scores): void
	{
		$this->scores = $scores;
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

        //backend validation | only final frame has third roll
        if(!$this->isFrameFinal($this->currentFrame)){
            unset($currentFrameData['roll_3']);
        }

		$currentFrameData = array_map(function($value){
				return (int)$value;
			}, $currentFrameData);


		$this->currentFrameData['rolls'] = $currentFrameData;
        $this->scores[$this->currentFrame] = $this->currentFrameData;
	}

	public function calculateScores(): array
	{
		foreach($this->scores as $frame => $frameScores){

            $previousFrameScore = $frame > 1 ? $this->scores[$frame-1]['frame_final_score'] : 0;
            $this->scores[$frame]['frame_total_score'] = $this->getFrameTotalScore($frame);
            $this->scores[$frame]['frame_final_score'] =
                $previousFrameScore + $this->scores[$frame]['frame_total_score'];
        }

        return $this->scores;
	}

    protected function getFrameTotalScore(int $frame): ?int
    {
        $frameTotal = null;

        //usual frames
        if (!$this->isFrameFinal($frame)) {
            if (
                !$this->isFrameSpare($frame)
                && !$this->isFrameStrike($frame)
            ) {
                $frameTotal = $this->scores[$frame]['rolls']['roll_1'] + $this->scores[$frame]['rolls']['roll_2'];
            } elseif ($this->isFrameSpare($frame)) {
                $frameBonus = $this->getFrameBonus($frame, 'spare');
                $frameTotal = !is_null($frameBonus)
                    ? (self::PIN_COUNT + $frameBonus)
                    : null;
            } elseif ($this->isFrameStrike($frame)) {
                $frameBonus = $this->getFrameBonus($frame, 'strike');
                $frameTotal = !is_null($frameBonus)
                    ? (self::PIN_COUNT + $frameBonus)
                    : null;
            }
        } else {
            //last (10th) frame
            $frameTotal = array_sum($this->scores[$frame]['rolls']);
        }

        return $frameTotal;
    }

    protected function getFrameBonus(int $frame, string $bonusType): ?int
    {
        $nextFrame = $frame+1;
        $nextFrameRolls = $this->isFrameRolled($nextFrame) ? $this->scores[$nextFrame]['rolls'] : null;

        $bonus = null;

        if(!is_null($nextFrameRolls)){
            if($bonusType === 'spare'){
                $bonus = $nextFrameRolls['roll_1'];
            } elseif($bonusType === 'strike') {
                if($frame <= (self::FINAL_FRAME_NO - 2)){
                    if($this->isFrameStrike($nextFrame)){
                        $furtherFrame = $nextFrame+1;

                        if($this->isFrameRolled($furtherFrame)){
                            $furtherFrameRolls = $this->scores[$furtherFrame]['rolls'];
                            $bonus = $nextFrameRolls['roll_1']
                                + $furtherFrameRolls['roll_1'];
                        }
                    } else {
                        $bonus = $nextFrameRolls['roll_1'] + $nextFrameRolls['roll_2'];
                    }
                } else {
                    $bonus = $nextFrameRolls['roll_1'] + $nextFrameRolls['roll_2'];
                }

            } else {
                die('Wrong bonus type');
            }
        }

        return $bonus;
    }

    protected function isFrameSpare(int $frame): bool
    {
        if($this->scores[$frame]['rolls']['roll_1'] < self::PIN_COUNT
        && $this->scores[$frame]['rolls']['roll_2'] < self::PIN_COUNT
        && $this->scores[$frame]['rolls']['roll_1'] + $this->scores[$frame]['rolls']['roll_2'] === self::PIN_COUNT){
            return true;
        }

        return false;
    }

    protected function isFrameStrike(int $frame): bool
    {
        return $this->scores[$frame]['rolls']['roll_1'] === 10;
    }

    protected function isFrameFinal(int $frame): bool
    {
        return $frame === self::FINAL_FRAME_NO;
    }

    protected function isFrameRolled(int $frame): bool
    {
        return isset($this->scores[$frame]);
    }
}