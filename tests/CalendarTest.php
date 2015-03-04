<?php

use loganhenson\Calendar\Calendar;

class CalendarTest extends \PHPUnit_Framework_TestCase {

    public function testMatches2015()
    {
        //file_put_contents(__DIR__ . '/2015.php', var_export((new Calendar())->generateYearCalendar(), true));die();
        $this->assertEquals(require __DIR__ . '/2015.php', (new Calendar())->generateYearCalendar());
    }

}
