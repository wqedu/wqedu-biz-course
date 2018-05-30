<?php
/**
 * Created by PhpStorm.
 * User: qujiyong
 * Date: 2018/5/28
 * Time: 下午4:24
 */
namespace Biz\Course\Service;

use Codeages\Biz\Framework\Service\Exception\AccessDeniedException;

interface CourseService
{
    public function getCourse($id);

    public function createCourse($course);

    /*
    public function findCoursesByIds($ids);

    public function searchCourses($conditions, $sort, $start, $limit);

    public function searchCourseCount($conditions);



    public function updateCourse($id, $fields);

    public function deleteCourse($id);
    */
}
