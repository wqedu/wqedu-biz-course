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
            throw $this->createNotFoundException("Chapter#{$chapterId} Not Found");
        }

        $fields = ArrayToolkit::parts($fields, array('title', 'number', 'seq', 'parentId'));

        $chapter = $this->getChapterDao()->update($chapterId, $fields);

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