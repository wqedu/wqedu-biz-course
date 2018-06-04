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

    public function updateCourse($id, $fields);

    public function deleteCourse($id);

    /*
    public function findCoursesByIds($ids);

    public function searchCourses($conditions, $sort, $start, $limit);

    public function searchCourseCount($conditions);

    */

    /*
     * 章节接口
     */
    public function getChapter($courseId, $chapterId);

    public function getCourseChapters($courseId);

    public function createChapter($chapter);

    public function updateChapter($courseId, $chapterId, $fields);

    public function deleteChapter($courseId, $chapterId);

    /*
     * 课时接口
     */

    public function getLesson($id);

    public function createLesson($lesson);

    public function updateLesson($courseId, $lessonId, $fields);

    public function deleteLesson($courseId, $lessonId);

    /*
     * 课程所有元素接口
     */
    public function getCourseItems($courseId);
}
