<?php
namespace App\Services\Schedulizer;

/**
 * This class represents a class object which contains a name, day, time, and
 * CRN
 * Class Course
 * @package App\Services\Schedulizer
 */
class Course {

    public $name;
    public $days;
    public $times;
    public $crn;
    public $campus;
    public $enrollment;
    public $short_name;
    public $color;
    public $instructor;
    public $max_enroll;
    public $building;
    public $instr_method;

    public function __construct($name,
                                $days,
                                $times,
                                $crn,
                                $campus,
                                $full,
                                $shortName,
                                $instructor,
                                $max_enroll,
                                $building,
                                $instr_method
    ) {
        $this->name = $name;
        $this->days = $days;
        $this->times = $times;
        $this->crn = $crn;
        $this->campus = $campus;
        $this->enrollment = $full;
        $this->short_name = $shortName;
        $this->instructor = $instructor;
        $this->max_enroll = $max_enroll;
        $this->building = $building;
        $this->instr_method = $instr_method;
    }

    public function getShortName(){
        return $this->short_name;
    }

    public function setColor($color) {
        $this->color = $color;
    }
}