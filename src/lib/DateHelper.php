<?php
/**
 * Created by PhpStorm.
 * User: djomla
 * Date: 22.12.18.
 * Time: 00.10
 */

class DateHelper extends DateTime
{
    /**
     * @param string $date1
     * @param string $date2
     * @return int
     * @throws Exception
     */
    public function dayDiffWithoutWeekend($date1, $date2) {
        /** @var DateTime $datetime1 */
        $datetime1 = new DateTime($date1);
        /** @var DateTime $datetime2 */
        $datetime2 = new DateTime($date2);
        $interval  = $datetime1->diff($datetime2);
        $woweekends = 0;
        for($i=0; $i<=$interval->d; $i++){
            $datetime1->modify('+1 day');
            $weekday = $datetime1->format('w');

            if($weekday != 0 && $weekday != 6){ // 0 for Sunday and 6 for Saturday
                $woweekends++;
            }

        }

        return $woweekends;
    }
}