<?php
/**
 * Created by PhpStorm.
 * User: qujiyong
 * Date: 2018/5/28
 * Time: 下午4:27
 */

namespace Biz\Course\Service\Impl;

use Codeages\Biz\Framework\Service\BaseService;
use Biz\Course\Service\CourseService;
use Wqedu\Common\ArrayToolkit;

class CourseServiceImpl extends BaseService implements CourseService
{

    public function getCourse($id)
    {
        return $this->getCourseDao()->get($id);
    }

    public function createCourse($course)
    {
        if (!ArrayToolkit::requireds($course, array('title'))) {
            throw $this->createServiceException($this->getKernel()->trans('缺少必要字段，创建课程失败！'));
        }

        $course                = ArrayToolkit::parts($course, array('title', 'subtitle', 'type', 'lessonNum', 'category', 'tags', 'keypoints', 'smallPicture', 'middlePicture', 'largePicture', 'about', 'goals', 'audiences', 'parentId'));
        $course['status']      = 'published';
        $course['about']       = !empty($course['about']) ? $course['about'] : '';
        //$course['about']       = !empty($course['about']) ? $this->purifyHtml($course['about']) : '';//todo, add htmlhelper
        $course['createdTime'] = time();
        $course['updatedTime'] = time();
        $course                = $this->getCourseDao()->create(CourseSerialize::serialize($course));

        $course = $this->getCourse($course['id']);

        //$this->dispatchEvent("course.create", $course);
        //$this->getLogService()->info('course', 'create', "创建课程《{$course['title']}》(#{$course['id']})");

        return $course;
    }

    /*
    public function findCoursesByIds($ids)
    {
        $courses = $this->getCourseDao()->findCoursesByIds($ids);

        return ArrayToolkit::index($courses, 'id');
    }

    public function searchCourses($conditions, $sort, $start, $limit)
    {
        $conditions = $this->_prepareCourseConditions($conditions);
        $orderBy = $this->_prepareCourseOrderBy($sort);

        return $this->getCourseDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function searchCourseCount($conditions)
    {
        $conditions = $this->_prepareCourseConditions($conditions);

        return $this->getCourseDao()->count($conditions);
    }



    public function updateCourse($id, $fields)
    {
        $user = $this->getCurrentUser();

        $argument = $fields;

        $tagIds   = empty($fields['tagIds']) ? array() : $fields['tagIds'];

        $course   = $this->getCourseDao()->getCourse($id);

        if (empty($course)) {
            throw $this->createServiceException($this->getKernel()->trans('课程不存在，更新失败！'));
        }
        $fields = $this->_filterCourseFields($fields);

        //非法提交直接报错,service应该有反馈
        if (!empty($fields['expiryMode']) &&
            $course['status'] == 'published' &&
            $fields['expiryMode'] != $course['expiryMode']) {
            throw $this->createServiceException('已发布的课程不允许修改学员有效期');
        }

        $this->getLogService()->info('course', 'update', "更新课程《{$course['title']}》(#{$course['id']})的信息", $fields);

        $fields        = $this->fillOrgId($fields);
        $fields        = CourseSerialize::serialize($fields);

        $updatedCourse = $this->getCourseDao()->updateCourse($id, $fields);

        $this->dispatchEvent("course.update", array('argument' => $argument, 'course' => $updatedCourse, 'sourceCourse' => $course, 'tagIds' => $tagIds, 'userId' => $user['id']));

        return CourseSerialize::unserialize($updatedCourse);
    }

    public function deleteCourse($id)
    {
        $course  = $this->tryAdminCourse($id, 'admin_course_delete');
        $lessons = $this->getCourseLessons($course['id']);

        // Delete course related data
        $this->getMemberDao()->deleteMembersByCourseId($id);
        $this->getLessonDao()->deleteLessonsByCourseId($id);
        $this->getLessonExtendDao()->deleteLessonsByCourseId($id);
        $this->deleteCrontabs($lessons);
        $this->getChapterDao()->deleteChaptersByCourseId($id);

        $this->getCourseDao()->deleteCourse($id);

        if ($course["type"] == "live") {
            $this->getCourseLessonReplayDao()->deleteLessonReplayByCourseId($id);
        }

        $this->getLogService()->info('course', 'delete', "删除课程《{$course['title']}》(#{$course['id']})");

        $this->dispatchEvent("course.delete", $course);

        return true;
    }

    protected function _prepareCourseConditions($conditions)
    {
        $conditions = array_filter(
            $conditions,
            function ($value) {
                if (0 == $value) {
                    return true;
                }

                return !empty($value);
            }
        );

        if (isset($conditions['date'])) {
            $dates = array(
                'yesterday' => array(
                    strtotime('yesterday'),
                    strtotime('today'),
                ),
                'today' => array(
                    strtotime('today'),
                    strtotime('tomorrow'),
                ),
                'this_week' => array(
                    strtotime('Monday this week'),
                    strtotime('Monday next week'),
                ),
                'last_week' => array(
                    strtotime('Monday last week'),
                    strtotime('Monday this week'),
                ),
                'next_week' => array(
                    strtotime('Monday next week'),
                    strtotime('Monday next week', strtotime('Monday next week')),
                ),
                'this_month' => array(
                    strtotime('first day of this month midnight'),
                    strtotime('first day of next month midnight'),
                ),
                'last_month' => array(
                    strtotime('first day of last month midnight'),
                    strtotime('first day of this month midnight'),
                ),
                'next_month' => array(
                    strtotime('first day of next month midnight'),
                    strtotime('first day of next month midnight', strtotime('first day of next month midnight')),
                ),
            );

            if (array_key_exists($conditions['date'], $dates)) {
                $conditions['startTimeGreaterThan'] = $dates[$conditions['date']][0];
                $conditions['startTimeLessThan'] = $dates[$conditions['date']][1];
                unset($conditions['date']);
            }
        }

        if (isset($conditions['creator']) && !empty($conditions['creator'])) {
            $user = $this->getUserService()->getUserByNickname($conditions['creator']);
            $conditions['userId'] = $user ? $user['id'] : -1;
            unset($conditions['creator']);
        }

        if (isset($conditions['categoryId'])) {
            $conditions['categoryIds'] = array();

            if (!empty($conditions['categoryId'])) {
                $childrenIds = $this->getCategoryService()->findCategoryChildrenIds($conditions['categoryId']);
                $conditions['categoryIds'] = array_merge(array($conditions['categoryId']), $childrenIds);
            }

            unset($conditions['categoryId']);
        }

        if (isset($conditions['nickname'])) {
            $user = $this->getUserService()->getUserByNickname($conditions['nickname']);
            $conditions['userId'] = $user ? $user['id'] : -1;
            unset($conditions['nickname']);
        }

        return $conditions;
    }

    protected function _prepareCourseOrderBy($sort)
    {
        if (is_array($sort)) {
            $orderBy = $sort;
        } elseif ('popular' == $sort || 'hitNum' == $sort) {
            $orderBy = array('hitNum' => 'DESC');
        } elseif ('recommended' == $sort) {
            $orderBy = array('recommendedTime' => 'DESC');
        } elseif ('rating' == $sort) {
            $orderBy = array('rating' => 'DESC');
        } elseif ('studentNum' == $sort) {
            $orderBy = array('studentNum' => 'DESC');
        } elseif ('recommendedSeq' == $sort) {
            $orderBy = array('recommendedSeq' => 'ASC', 'recommendedTime' => 'DESC');
        } elseif ('createdTimeByAsc' == $sort) {
            $orderBy = array('createdTime' => 'ASC');
        } else {
            $orderBy = array('createdTime' => 'DESC');
        }

        return $orderBy;
    }
    */

    /**
     * @return CourseDao
     */
    protected function getCourseDao()
    {
        return $this->biz->dao('Course:CourseDao');
    }

    /**
     * @return CourseChapterDao
     */
    protected function getChapterDao()
    {
        return $this->biz->dao('Course:CourseChapterDao');
    }

    protected function getLessonDao()
    {
        return $this->biz->dao('Course.LessonDao');
    }
}

class CourseSerialize
{
    public static function serialize(array &$course)
    {
        if (isset($course['keypoints'])) {
            if (is_array($course['keypoints']) && !empty($course['keypoints'])) {
                $course['keypoints'] = serialize($course['keypoints']);
            } else {
                $course['keypoints'] = serialize(array());
            }
        }

        if (isset($course['goals'])) {
            if (is_array($course['goals']) && !empty($course['goals'])) {
                $course['goals'] = '|'.implode('|', $course['goals']).'|';
            } else {
                $course['goals'] = '';
            }
        }

        if (isset($course['audiences'])) {
            if (is_array($course['audiences']) && !empty($course['audiences'])) {
                $course['audiences'] = '|'.implode('|', $course['audiences']).'|';
            } else {
                $course['audiences'] = '';
            }
        }

        return $course;
    }

    public static function unserialize(array $course = null)
    {
        if (empty($course)) {
            return $course;
        }

        if (empty($course['keypoints'])) {
            $course['goals'] = array();
        } else {
            $course['goals'] = unserialize($course['keypoints']);
        }

        if (empty($course['goals'])) {
            $course['goals'] = array();
        } else {
            $course['goals'] = explode('|', trim($course['goals'], '|'));
        }

        if (empty($course['audiences'])) {
            $course['audiences'] = array();
        } else {
            $course['audiences'] = explode('|', trim($course['audiences'], '|'));
        }

        return $course;
    }

    public static function unserializes(array $courses)
    {
        return array_map(function ($course) {
            return CourseSerialize::unserialize($course);
        }, $courses);
    }
}