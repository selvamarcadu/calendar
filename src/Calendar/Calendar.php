<?php

namespace loganhenson\Calendar;

use Carbon\Carbon;

/**
 * Class Calendar
 */
class Calendar{

    /**
     * @var Carbon
     */
    private $now;

    public function __construct($now = null){

        if(!isset($now)) {
            $now = Carbon::now();
        }

        $this->now = $now;

    }

    /**
     * Generates a year calendar array for front end usage
     *
     * @param array $marked for the dates to mark
     * @return array
     */
    public function generateYearCalendar(array $marked = []){

        $calendar = [];

        $firstOfYear = $this->now->copy()->firstOfYear();
        $lastOfYear = $this->now->copy()->lastOfYear();

        while($firstOfYear != $lastOfYear){

            $day = [
                'carbon' => $firstOfYear->copy(),
                'marked' => false
            ];

            foreach($marked as $mark){

                /** @var Carbon $mark */

                if($mark->isSameDay($day['carbon'])){
                    $day['marked'] = true;break;
                }

            }

            $calendar[$firstOfYear->month][] = $day;

            //increment day
            $firstOfYear->addDay();

        }

        //build out months => weeks => days

        $generated_year = [
            'year' => $this->now->year,
            'months' => []
        ];

        foreach($calendar as $month => $days){

            //add month
            $generated_year['months'][] = ['month' => [
                'month' => $month,
                'year' => $this->now->year,
                'pretty' => Carbon::create(null, $month)->format('F')
            ], 'weeks' => []];

            //reset week index, month index
            $week_index = 0;

            foreach($days as $day){

                if($day['carbon']->dayOfWeek == Carbon::MONDAY){

                    $week_index++;

                    //add week
                    $generated_year['months'][$month - 1]['weeks'][$week_index] = ['days' => []];

                }

                if($day['carbon']->day == 1){

                    $pad = ($day['carbon']->dayOfWeek - 1) >= 0 ? ($day['carbon']->dayOfWeek - 1) : 6;

                    for($p = $pad;$p > 0;$p--){

                        //add empty days for start of week
                        $generated_year['months'][$month - 1]['weeks'][$week_index]['days'][] = false;

                    }

                }

                //add day
                $generated_year['months'][$month - 1]['weeks'][$week_index]['days'][] = [
                    'day' => $day['carbon']->format('Y-m-d'),
                    'day_of_month' => $day['carbon']->day,
                    'marked' => $day['marked']
                ];

            }

        }

        //add empty days at end of weeks with less than 7 days
        foreach($calendar as $month => $days){

            $month -= 1;

            foreach($generated_year['months'][$month]['weeks'] as $week_index => $week){

                # empty days to add
                $empty = 7 - count($week['days']);
                for($e = $empty;$e > 0;$e--){
                    $generated_year['months'][$month]['weeks'][$week_index]['days'][] = false;
                }

            }

        }

        return $generated_year;

    }

}