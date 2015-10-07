<?php namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Instruction Type Constants
 * These are the instruction-type strings used by TMS to categorize
 * each type of class. The five main ones are below.
 * Last modified: July 16 2015
 */
define('LECTURE', 'Lecture');
define('LAB', 'Lab');
define('RECITATION', 'Recitation/Discussion');
define('LECTURE_AND_LAB', 'Lecture & Lab');
define('LECTURE_AND_REC', 'Lecture & Recitation');


class DrexelClass extends Model {

	 /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'classes';

    /**
     * Search for course title or subject name or instructor name
     * @param $query
     * @param $searchTerm Course Title or Subject Name i.e. "ECEC 355" or
     *                    "Digital Logic"
     * @return mixed
     */
    public function scopeSearch($query, $searchTerm) {
        return $query->where(function($query) use ($searchTerm) {
            $query
                ->where('course_title', 'like', '%' . $searchTerm . '%')
                ->orWhere(
                    DB::raw("subject_code || ' ' ||  course_no || ' ' || course_title || ' '"),
                    'like',
                    '%' . $searchTerm . '%'
                )
                ->orWhere('instructor', 'like', '%' . $searchTerm . '%')
                ->orWhere('crn', 'like', '%' . $searchTerm . '%')
                ;
        });
    }

    /**
     * Search for course title with instruction type. This is primarily
     * used in the class generation algorithm.
     * @param $query
     * @param $searchTerm Full course title with instruction type
     * @return mixed      The course day, time, and CRN
     */
    public function scopeSearchWithType($query, $searchTerm) {
        return $query->where(
            DB::raw("subject_code || ' ' ||  course_no || ' ' || course_title || ' ' || instr_type"),
            'like',
            '%' . $searchTerm
        )->select(
            'day',
            'time',
            'crn',
            'campus',
            'enroll',
            'max_enroll',
            'building',
            DB::raw('subject_code || " " || course_no || " " || instr_type' . ' as short_name')
        );
    }

    /**
     * Get all course codes and titles
     * Used in the autocomplete search
     * @param $query
     * @return mixed
     */
    public function scopeAllCourseNo($query) {
        return $query
            ->orderBy('course_no')
            ->groupBy(
                DB::raw("subject_code || ' ' ||  course_no")
            )
            ->get()
            ;
    }

    /**
     * Search for lab sections
     * @param $query
     * @return mixed All of a classes' lab section
     */
    public function scopeLabs($query) {
        return $query
            ->where('instr_type', 'like', LAB)
            ;
    }

    /**
     * Search for lecture sections
     * @param $query
     * @return mixed All of a classes' lecture section
     */
    public function scopeLectures($query) {
        return $query
            ->where('instr_type', 'like', LECTURE)
            ;
    }

    /**
     * Search for recitation sections
     * @param $query
     * @return mixed All of a classes' recitations section
     */
    public function scopeRecitations($query) {
        return $query
            ->where('instr_type', 'like', RECITATION)
            ;
    }

    /**
     * Search for lab and lecture sections
     * @param $query
     * @return mixed All of a classes' lecture and lab section
     */
    public function scopeLectureAndLab($query) {
        return $query
            ->where('instr_type', 'like', LECTURE_AND_LAB)
            ;
    }

    /**
     * Search for lab and recitation sections
     * @param $query
     * @return mixed All of a classes' lecture and recitation section
     */
    public function scopeLectureAndRec($query) {
        return $query
            ->where('instr_type', 'like', LECTURE_AND_REC)
            ;
    }

    public function lastUpdated($query) {

    }
}
