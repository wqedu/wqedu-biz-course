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
        try{
            $course  = $this->getCourseDao()->get($id);

            if(empty($course))
                throw new \Exception('Course Resource Not Found', 20204);

            return array(
                'code'  =>  0,
                'data'  =>  KeypointsSerialize::unserialize($course)
            );
        } catch (\Exception $e) {
            return $this->_filterSystemException($e->getCode(), $e->getMessage());
        }
    }

    public function createCourse($course)
    {
        try{
            if (!ArrayToolkit::requireds($course, array('title')))
                throw new \Exception('Missing Necessary Fields', 20201);

            $course                = $this->_filterCourseFields($course);
            $course['status']      = 'published';
            $course['about']       = !empty($course['about']) ? $course['about'] : '';
            //$course['about']       = !empty($course['about']) ? $this->purifyHtml($course['about']) : '';//todo, add htmlhelper
            $course['createdTime'] = time();
            $course['updatedTime'] = time();
            $course                = $this->getCourseDao()->create(KeypointsSerialize::serialize($course));

            //$this->getLogService()->info('course', 'create', "创建课程《{$course['title']}》(#{$course['id']})");

            return array(
                'code'  =>  0,
                'data'  =>  KeypointsSerialize::unserialize($course)
            );
        } catch (\Exception $e){
            return $this->_filterSystemException($e->getCode(), $e->getMessage());
        }
    }

    public function updateCourse($id, $fields)
    {
        try{
            $course   = $this->getCourseDao()->get($id);
            if(empty($course))
                throw new \Exception('Course Resource Not Found', 20204);

            $fields = $this->_filterCourseFields($fields);
            $fields        = KeypointsSerialize::serialize($fields);
            $updatedCourse = $this->getCourseDao()->update($id, $fields);
            //$this->getLogService()->info('course', 'update', "更新课程《{$course['title']}》(#{$course['id']})的信息", $fields);

            return array(
                'code'  =>  0,
                'data'  =>  KeypointsSerialize::unserialize($updatedCourse)
            );
        } catch (\Exception $e) {
            return $this->_filterSystemException($e->getCode(), $e->getMessage());
        }
    }

    public function deleteCourse($id)
    {
        try {
            $course   = $this->getCourseDao()->get($id);
            if(empty($course))
                throw new \Exception('Course Resource Not Found', 20204);

            $this->getLessonDao()->deleteLessonsByCourseId($id);
            $this->getChapterDao()->deleteChaptersByCourseId($id);
            $this->getCourseDao()->delete($id);

            //$this->getLogService()->info('course', 'delete', "删除课程《{$course['title']}》(#{$course['id']})");
            return array(
                'code'  =>  0,
                'data'  =>  true
            );

        } catch (\Exception $e) {
            return $this->_filterSystemException($e->getCode(), $e->getMessage());
        }
    }

    public function searchCourseCount($conditions)
    {
        $conditions = $this->_prepareCourseConditions($conditions);

        return $this->getCourseDao()->count($conditions);
    }

    public function searchCourses($conditions, $sort, $start, $limit)
    {
        $conditions = $this->_prepareCourseConditions($conditions);
        $orderBy = $this->_prepareCourseOrderBy($sort);

        return $this->getCourseDao()->search($conditions, $orderBy, $start, $limit);
    }

    /*
     * chapter api
     */
    public function getChapter($courseId, $chapterId)
    {
        try {
            $chapter = $this->getChapterDao()->get($chapterId);

            if (empty($chapter) || $chapter['courseId'] != $courseId)
                throw new \Exception('Chapter Resource Not Found', 20214);

            return array(
                'code' => 0,
                'data' => KeypointsSerialize::unserialize($chapter)
            );

        } catch (\Exception $e) {
            return $this->_filterSystemException($e->getCode(), $e->getMessage());
        }
    }

    public function getCourseChapters($courseId)
    {
        try {
            $chapters = $this->getChapterDao()->findChaptersByCourseId($courseId);

            if (empty($chapters))
                throw new \Exception('Chapter Resource Not Found', 20214);

            return array(
                'code' => 0,
                'data' => KeypointsSerialize::unserializes( $chapters )
            );
        } catch (\Exception $e) {
            return $this->_filterSystemException($e->getCode(), $e->getMessage());
        }
    }

    public function createChapter($chapter)
    {
        try {
            if (!in_array($chapter['type'], array('chapter', 'unit', 'lesson')))
                throw new \Exception('Invalid Chapter Type', 20212);

            $chapter = $this->_filterCourseChapterFields($chapter);
            $chapter = $this->getChapterDao()->create( KeypointsSerialize::serialize($chapter) );

            return array(
                'code' => 0,
                'data' => KeypointsSerialize::unserialize($chapter)
            );

        } catch (\Exception $e) {
            return $this->_filterSystemException($e->getCode(), $e->getMessage());
        }

    }

    public function updateChapter($courseId, $chapterId, $fields)
    {
        try {
            $chapter = $this->getChapterDao()->get($chapterId);

            if (empty($chapter) || $chapter['courseId'] != $courseId)
                throw new \Exception('Chapter Resource Not Found', 20214);

            $fields = $this->_filterCourseChapterFields($fields);
            $fields = KeypointsSerialize::serialize($fields);

            $chapter = $this->getChapterDao()->update($chapterId, $fields);

            return array(
                'code' => 0,
                'data' => KeypointsSerialize::unserialize($chapter)
            );

        } catch (\Exception $e) {
            return $this->_filterSystemException($e->getCode(), $e->getMessage());
        }
    }

    public function deleteChapter($courseId, $chapterId)
    {
        try {
            $chapter = $this->getChapterDao()->get($chapterId);

            if (empty($chapter) || $chapter['courseId'] != $courseId)
                throw new \Exception('Chapter Resource Not Found', 20214);

            $this->getChapterDao()->delete($chapter['id']);

            //$this->getLogService()->info('course', 'delete_chapter', "删除章节(#{$chapterId})", $deletedChapter);
            return array(
                'code'  =>  0,
                'data'  =>  true
            );
        } catch (\Exception $e) {
            return $this->_filterSystemException($e->getCode(), $e->getMessage());
        }
    }

    /*
     * 课时接口
     */

    public function createLesson($lesson)
    {
        try {
            $lesson   = $this->_filterCourseLessonFields($lesson);

            $lesson   = $this->_filterCourseLessonFields($lesson);

            if (!ArrayToolkit::requireds($lesson, array('courseId', 'title', 'type')))
                throw new \Exception('Lesson Missing Necessary Fields', 20221);

            $course = $this->getCourse($lesson['courseId']);
            if (empty($course))
                throw new \Exception('Course Resource Not Found', 20204);

            if (!in_array($lesson['type'], array('text', 'audio', 'video', 'testpaper', 'ppt', 'document', 'flash')))
                throw new \Exception('Invalid Lesson Type', 20222);

            // 课程处于发布状态时，新增课时，课时默认的状态为“未发布"
            $lesson['status']      = empty($lesson['status']) ? 'published' : $lesson['status'];
            $lesson['free']        = empty($lesson['free']) ? 0 : 1;

            $lesson = $this->getLessonDao()->create(
                KeypointsSerialize::serialize($lesson)
            );

            //$this->getLogService()->info('course', 'add_lesson', "添加课时《{$lesson['title']}》({$lesson['id']})", $lesson);
            return array(
                'code' => 0,
                'data' => KeypointsSerialize::unserialize($lesson)
            );
        } catch (\Exception $e) {
            return $this->_filterSystemException($e->getCode(), $e->getMessage());
        }

    }

    public function getLesson($id)
    {
        try {
            $lesson  = $this->getLessonDao()->get($id);

            if(empty($lesson))
                throw new \Exception('Lesson Resource Not Found', 20224);

            return array(
                'code' => 0,
                'data' => KeypointsSerialize::unserialize($lesson)
            );
        } catch (\Exception $e) {
            return $this->_filterSystemException($e->getCode(), $e->getMessage());
        }

    }

    public function updateLesson($courseId, $lessonId, $fields)
    {
        try {
            $argument = $fields;
            $course   = $this->getCourseDao()->get($courseId);
            if(empty($course))
                throw new \Exception('Course Resource Not Found', 20204);

            $lesson = $this->getLessonDao()->get($lessonId);
            if (empty($lesson) || $lesson['courseId'] != $courseId)
                throw new \Exception('Lesson Resource Not Found', 20224);

            $fields   = $this->_filterCourseLessonFields($fields);
            if (isset($fields['title'])) {
                //$fields['title'] = $this->purifyHtml($fields['title']);
            }

            $updatedLesson = KeypointsSerialize::unserialize(
                $this->getLessonDao()->update($lessonId, KeypointsSerialize::serialize($fields))
            );

            //todo, log
            //$this->getLogService()->info('course', 'update_lesson', "更新课时《{$updatedLesson['title']}》({$updatedLesson['id']})", $updatedLesson);

            return array(
                'code' => 0,
                'data' => $updatedLesson
            );

        } catch (\Exception $e) {
            return $this->_filterSystemException($e->getCode(), $e->getMessage());
        }
    }

    public function deleteLesson($courseId, $lessonId)
    {
        try {
            $course = $this->getCourse($courseId);
            if(empty($course))
                throw new \Exception('Course Resource Not Found', 20204);

            $lesson = $this->getLesson($lessonId);
            if (empty($lesson) || $lesson['courseId'] != $courseId)
                throw new \Exception('Lesson Resource Not Found', 20224);

            $this->getLessonDao()->delete($lessonId);

            //todo,log
            //$this->getLogService()->info('course', 'delete_lesson', "删除课程《{$course['title']}》(#{$course['id']})的课时 {$lesson['title']}");

            return array(
                'code' => 0,
                'data' => true
            );

        } catch (\Exception $e) {
            return $this->_filterSystemException($e->getCode(), $e->getMessage());
        }
    }

    /*
     * 课程元素接口
     */
    public function getCourseItems($courseId)
    {
        try {
            $course = $this->getCourse($courseId);
            if(empty($course))
                throw new \Exception('Course Resource Not Found', 20204);

            $lessons = KeypointsSerialize::unserializes(
                $this->getLessonDao()->findLessonsByCourseId($courseId)
            );

            $chapters = KeypointsSerialize::unserializes(
                $this->getChapterDao()->findChaptersByCourseId($courseId)
            );

            $items = array();

            foreach ($lessons as $lesson) {
                $lesson['itemType']              = 'lesson';
                $items["lesson-{$lesson['id']}"] = $lesson;
            }
            foreach ($chapters as $chapter) {
                $chapter['itemType']               = 'chapter';
                $items["chapter-{$chapter['id']}"] = $chapter;
            }

            uasort(
                $items,
                function ($item1, $item2) {
                    return $item1['seq'] > $item2['seq'];
                }
            );

            return array(
                'code' => 0,
                'data' => $items
            );

        } catch (\Exception $e) {
            return $this->_filterSystemException($e->getCode(), $e->getMessage());
        }
    }

    protected function _filterSystemException($code, $message)
    {
        if($code<100)
            $code = 10200 + $code;
        elseif($code>20000)
            ;
        else
            $code = 102 . $code;
        return array('code'=>$code, 'message'=>$message);
    }

    /*
     * course
     */

    protected function _filterCourseFields($fields)
    {
        $fields = ArrayToolkit::filter($fields, array(
            'title'             =>  '',
            'subtitle'          =>  '',
            'type'              =>  'normal',
            'price'             =>  0.00,
            'serializeMode'     =>  'none',
            'lessonNum'         =>  0,
            'category'          =>  '',
            'tags'              =>  '',
            'keypoints'         =>  array(),
            'smallPicture'      =>  '',
            'middlePicture'     =>  '',
            'largePicture'      =>  '',
            'about'             =>  '',
            'goals'             =>  array(),
            'audiences'         =>  array(),
            'parentId'          =>  0,
            'status'            =>  'published',
            'createdTime'       =>  time(),
            'updatedTime'       =>  time(),

        ));

        return $fields;
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

        return $conditions;
    }

    protected function _prepareCourseOrderBy($sort)
    {
        if (is_array($sort)) {
            $orderBy = $sort;
        } elseif ('createdTimeByAsc' == $sort) {
            $orderBy = array('createdTime' => 'ASC');
        } else {
            $orderBy = array('createdTime' => 'DESC');
        }

        return $orderBy;
    }

    /*
     * chapter
     */

    protected function _filterCourseChapterFields($fields)
    {
        $fields = ArrayToolkit::filter($fields, array(
            'title'             =>  '',
            'courseId'          =>  '',
            'type'              =>  'chapter',
            'keypoints'         =>  array(),
            'parentId'          =>  0,
            'number'            =>  0,
            'seq'               =>  0,
            'createdTime'       =>  time()
        ));

        return $fields;
    }

    protected function _filterCourseLessonFields($fields)
    {
        $fields = ArrayToolkit::filter($fields, array(
            'courseId'      => 0,
            'chapterId'     => 0,
            'number'        => 0,
            'seq'           => 0,
            'free'          => 0,
            'title'         => '',
            'summary'       => '',
            'type'          => 'text',
            'content'       => '',
            'media'         => array(),
            'mediaId'       => 0,
            'length'        => 0,
            'testMode'      => 'normal',
            'testStartTime' => 0,
            'tags'          =>  '',
            'mediaId'       =>  0,
            'mediaSource'   =>  'izhixue',
            'mediaName'     =>  '',
            'mediaUri'      =>  '',
            'materialNum'   =>  0,
            'quizNum'       =>  0,
            'status'        =>  'published',
            'keypoints'      => array(),
        ));

        return $fields;
    }

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
        return $this->biz->dao('Course:CourseLessonDao');
    }
}


class KeypointsSerialize
{
    public static function serialize(array &$data)
    {
        if (isset($data['keypoints'])) {
            if (is_array($data['keypoints']) && !empty($data['keypoints'])) {
                $keypoints = '';
                foreach($data['keypoints'] as $key=>$points)
                {
                    $keypoints .= '|' . $key . ':' . $points;
                }
                $data['keypoints'] = $keypoints . '|';
            } else {
                $data['keypoints'] = '';
            }
        }

        return $data;
    }

    public static function unserialize(array $data = null)
    {
        if (empty($data)) {
            return $data;
        }

        if (empty($data['keypoints'])) {
            $data['keypoints'] = array();
        } else {
            $keypoints = explode('|', trim($data['keypoints'], '|'));
            $newKeypoints = array();
            foreach($keypoints as $keypoint)
            {
                list($key, $point) = explode(':', $keypoint);
                $newKeypoints[$key] = $point;
            }

            $data['keypoints'] = $newKeypoints;
        }

        return $data;
    }

    public static function unserializes(array $datas)
    {
        return array_map(function ($data) {
            return KeypointsSerialize::unserialize($data);
        }, $datas);
    }
}