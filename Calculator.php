<?php

class Calculator {
	
	protected $scores = [];
	protected $currentFrameData;
	protected $currentFrame;
	const PIN_COUNT = 10;
	const FINAL_FRAME_NO = 10;

	public function __construct()
	{

	}

	public function setScores(array $scores): void
	{
		$this->scores = $scores; 
	}

	public function getScores(): array
	{
		return $this->scores;
	}

	public function setcurrentFrame(): void
	{
		$this->currentFrame = count($this->scores) + 1;; 
	}

	public function getcurrentFrame(): array
	{
		return $this->currentFrame;
	}

	public function setCurrentFrameData(array $currentFrameData): void
	{
        $this->setcurrentFrame();
		$currentFrameData = array_map(function($value){
				return (int)$value;
			}, $currentFrameData);


		$this->currentFrameData['rolls'] = $currentFrameData;
		$this->addCurrentFrameScores();
		// var_dump($currentFrameData);die;

	}

	public function addCurrentFrameScores()
	{
		$this->scores[$this->currentFrame] = $this->currentFrameData;
	}

	public function getCurrentFrameData(): ?array
	{
		return $this->currentFrameData;
	}

	public function calculateScores()
	{
		return null;
	}
}