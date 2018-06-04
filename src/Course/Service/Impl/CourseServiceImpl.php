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
        $course  = $this->getCourseDao()->get($id);

        $course = KeypointsSerialize::unserialize($course);
        return ( $course );
    }

    public function createCourse($course)
    {
        if (!ArrayToolkit::requireds($course, array('title'))) {
            throw $this->createServiceException('缺少必要字段，创建课程失败！');
        }

        $course                = ArrayToolkit::parts($course, array('title', 'subtitle', 'type', 'lessonNum', 'category', 'tags', 'keypoints', 'smallPicture', 'middlePicture', 'largePicture', 'about', 'goals', 'audiences', 'parentId'));
        $course['status']      = 'published';
        $course['about']       = !empty($course['about']) ? $course['about'] : '';
        //$course['about']       = !empty($course['about']) ? $this->purifyHtml($course['about']) : '';//todo, add htmlhelper
        $course['createdTime'] = time();
        $course['updatedTime'] = time();
        $course                = $this->getCourseDao()->create(KeypointsSerialize::serialize($course));

        $course = $this->getCourse($course['id']);

        //$this->getLogService()->info('course', 'create', "创建课程《{$course['title']}》(#{$course['id']})");

        return $course;
    }

    public function updateCourse($id, $fields)
    {
        $course   = $this->getCourseDao()->get($id);

        if (empty($course)) {
            throw $this->createServiceException('课程不存在，更新失败！');
        }
        $fields = $this->_filterCourseFields($fields);

        //$this->getLogService()->info('course', 'update', "更新课程《{$course['title']}》(#{$course['id']})的信息", $fields);

        $fields        = KeypointsSerialize::serialize($fields);

        $updatedCourse = $this->getCourseDao()->update($id, $fields);

        return KeypointsSerialize::unserialize($updatedCourse);
    }

    public function deleteCourse($id)
    {
        //todo, delete lessons
        //$lessons = $this->getCourseLessons($course['id']);

        $this->getChapterDao()->deleteChaptersByCourseId($id);

        $this->getCourseDao()->delete($id);

        //$this->getLogService()->info('course', 'delete', "删除课程《{$course['title']}》(#{$course['id']})");

        return true;
    }



    /*
     * chapter api
     */
    public function getChapter($courseId, $chapterId)
    {
        $chapter = $this->getChapterDao()->get($chapterId);

        if (empty($chapter) || $chapter['courseId'] != $courseId) {
            return null;
        }

        $chapter = KeypointsSerialize::unserialize($chapter);
        return $chapter;
    }

    public function getCourseChapters($courseId)
    {
        $chapters = $this->getChapterDao()->findChaptersByCourseId($courseId);

        return KeypointsSerialize::unserializes( $chapters );
    }

    public function createChapter($chapter)
    {

        if (!in_array($chapter['type'], array('chapter', 'unit', 'lesson'))) {
            throw $this->createInvalidArgumentException('Invalid Chapter Type');
        }
        $chapter = $this->_filterCourseChapterFields($chapter);

        $chapter = $this->getChapterDao()->create( KeypointsSerialize::serialize($chapter) );
        $chapter = KeypointsSerialize::unserialize($chapter);
        return $chapter;
    }

    public function updateChapter($courseId, $chapterId, $fields)
    {
        $chapter = $this->getChapterDao()->get($chapterId);

        if (empty($chapter) || $chapter['courseId'] != $courseId) {
            return false;
        }

        $fields = $this->_filterCourseChapterFields($fields);

        $fields = KeypointsSerialize::serialize($fields);

        $chapter = $this->getChapterDao()->update($chapterId, $fields);

        $chapter = KeypointsSerialize::unserialize($chapter);

        return $chapter;
    }

    public function deleteChapter($courseId, $chapterId)
    {
        $deletedChapter = $this->getChapterDao()->get($chapterId);

        if (empty($deletedChapter) || $deletedChapter['courseId'] != $courseId) {
            throw $this->createNotFoundException("Chapter#{$chapterId} Not Found");
        }

        $this->getChapterDao()->delete($deletedChapter['id']);

        //$this->getLogService()->info('course', 'delete_chapter', "删除章节(#{$chapterId})", $deletedChapter);
        return true;
    }

    /*
     * 课时接口
     */

    public function createLesson($lesson)
    {
        $lesson   = $this->_filterCourseLessonFields($lesson);

        if (!ArrayToolkit::requireds($lesson, array('courseId', 'title', 'type'))) {
            //todo, log
            return false;
        }

        if (empty($lesson['courseId'])) {
            //todo,log
            return false;
        }

        $course = $this->getCourse($lesson['courseId']);

        if (empty($course)) {
            //todo,log
            return false;
        }

        if (!in_array($lesson['type'], array('text', 'audio', 'video', 'testpaper', 'ppt', 'document', 'flash'))) {
            //todo,log
            return false;
        }

        // 课程处于发布状态时，新增课时，课时默认的状态为“未发布"
        $lesson['status']      = $course['status'] == 'published' ? 'published' : 'unpublished';
        $lesson['free']        = empty($lesson['free']) ? 0 : 1;

        $lesson = $this->getLessonDao()->create(
            KeypointsSerialize::serialize($lesson)
        );

        //$this->getLogService()->info('course', 'add_lesson', "添加课时《{$lesson['title']}》({$lesson['id']})", $lesson);

        $lesson = KeypointsSerialize::unserialize($lesson);
        return $lesson;
    }

    public function getLesson($id)
    {
        $lesson  = $this->getLessonDao()->get($id);

        $lesson = KeypointsSerialize::unserialize($lesson);
        return ( $lesson );
    }

    public function updateLesson($courseId, $lessonId, $fields)
    {
        $argument = $fields;
        $course   = $this->getCourse($courseId);

        $lesson = $this->getLesson($lessonId);

        if (empty($lesson) || $lesson['courseId'] != $courseId) {
            return false;
        }

        $fields   = $this->_filterCourseLessonFields($fields);

        if (isset($fields['title'])) {
            //$fields['title'] = $this->purifyHtml($fields['title']);
        }

        $updatedLesson = KeypointsSerialize::unserialize(
            $this->getLessonDao()->update($lessonId, KeypointsSerialize::serialize($fields))
        );

        //todo, log
        //$this->getLogService()->info('course', 'update_lesson', "更新课时《{$updatedLesson['title']}》({$updatedLesson['id']})", $updatedLesson);

        return $updatedLesson;
    }

    public function deleteLesson($courseId, $lessonId)
    {
        $course = $this->getCourse($courseId);

        if (empty($course)) {
            return false;
        }

        $lesson = $this->getLesson($lessonId);

        if (empty($lesson)) {
            return false;
        }

        $this->getLessonDao()->delete($lessonId);

        //$this->getLogService()->info('course', 'delete_lesson', "删除课程《{$course['title']}》(#{$course['id']})的课时 {$lesson['title']}");

        return true;
    }

    /*
     * 课程元素接口
     */
    public function getCourseItems($courseId)
    {
        $lessons = KeypointsSerialize::unserializes(
            $this->getLessonDao()->findLessonsByCourseId($courseId)
        );

        $chapters = $this->getChapterDao()->findChaptersByCourseId($courseId);

        $items = array();

        foreach ($lessons as $lesson) {
            $lesson['itemType']              = 'lesson';
            $items["lesson-{$lesson['id']}"] = $lesson;
        }

        foreach ($chapters as $chapter) {
            $chapter['itemType']               = 'chapter';
            $items["chapter-{$chapter['id']}"] = $chapter;
        }

        uasort($items, function ($item1, $item2) {
            return $item1['seq'] > $item2['seq'];
        }

        );
        return $items;
    }


    protected function _filterCourseFields($fields)
    {
        $fields = ArrayToolkit::filter($fields, array(
            'title'             =>  '',
            'subtitle'          =>  '',
            'status'            =>  'published',
            'type'              =>  'normal',
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
            'createdTime'       =>  time(),
            'updatedTime'       =>  time(),
        ));

        return $fields;
    }

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