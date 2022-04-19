<?php
include_once('Calculator.php');

use \PHPUnit\Framework\TestCase;

 class CalculationsTest extends TestCase
{
    public function testCalculations(): void
    {
        $dataSet = $this->dataSet();
        $calculator = new \Calculator();
        $calculator->loadScores($dataSet['data']);
        $results = $calculator->calculateScores();
        $results = array_map(function($value){
            return $value['frame_final_score'];
        }, $results);

        $this->assertEquals($dataSet['results_expected'], $results);
    }

    public function dataSet()
    {
        $dataSet = [];

        $data = [
            1 => [
                'rolls' => [
                    'roll_1' => 8,
                    'roll_2' => 2,
                ],
                'frame_final_score' => 15,
            ],
            2 => [
                'rolls' => [
                    'roll_1' => 5,
                    'roll_2' => 4,
                ],
                'frame_final_score' => 24,
            ],
            3 => [
                'rolls' => [
                    'roll_1' => 9,
                    'roll_2' => 0,
                ],
                'frame_final_score' => 33,
            ],
            4 => [
                'rolls' => [
                    'roll_1' => 10,
                    'roll_2' => 0,
                ],
                'frame_final_score' => 58,
            ],
            5 => [
                'rolls' => [
                    'roll_1' => 10,
                    'roll_2' => 0,
                ],
                'frame_final_score' => 78,
            ],
            6 => [
                'rolls' => [
                    'roll_1' => 5,
                    'roll_2' => 5,
                ],
                'frame_final_score' => 93,
            ],
            7 => [
                'rolls' => [
                    'roll_1' => 5,
                    'roll_2' => 3,
                ],
                'frame_final_score' => 101,
            ],
            8 => [
                'rolls' => [
                    'roll_1' => 6,
                    'roll_2' => 3,
                ],
                'frame_final_score' => 110,
            ],
            9 => [
                'rolls' => [
                    'roll_1' => 9,
                    'roll_2' => 1,
                ],
                'frame_final_score' => 129,
            ],
            10 => [
                'rolls' => [
                    'roll_1' => 9,
                    'roll_2' => 1,
                    'roll_3' => 10,
                ],
                'frame_final_score' => 149,
            ],
        ];

        foreach($data as $frameNo => $frameData){
                $dataSet['data'][$frameNo]['rolls'] = $frameData['rolls'];
                $dataSet['results_expected'][$frameNo] = $frameData['frame_final_score'];
        }

        return $dataSet;
    }
}