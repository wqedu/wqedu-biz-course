<?php
/**
 * Created by PhpStorm.
 * User: qujiyong
 * Date: 2018/5/29
 * Time: 下午12:04
 */

namespace Tests;

class CourseLessonServiceTest extends IntegrationTestCase
{
    public function testCreateLesson()
    {
        $mockCourse = $this->mockCourse();
        $getCourse = $this->getCourseService()->createCourse($mockCourse);
        if($getCourse['code']>0)
        {
            $this->expectOutputString($getCourse['message']);
            return ;
        }
        $course = $getCourse['data'];

        //one chapter
        $chapter = $this->mockChapter();
        $chapter['courseId'] = $course['id'];
        $getChapter = $this->getCourseService()->createChapter($chapter);
        if($getChapter['code']>0)
        {
            $this->expectOutputString($getChapter['message']);
            return ;
        }
        $chapter = $getChapter['data'];

        $unit = $this->mockUnit();
        $unit['courseId'] = $course['id'];
        $unit['parentId'] = $chapter['id'];
        $getUnit = $this->getCourseService()->createChapter($unit);
        if($getUnit['code']>0)
        {
            $this->expectOutputString($getUnit['message']);
            return ;
        }
        $unit = $getUnit['data'];

        $mockLesson = $this->mockLesson();
        $mockLesson['courseId'] = $course['id'];
        $mockLesson['chapterId'] = $unit['id'];
        $getLesson = $this->getCourseService()->createLesson($mockLesson);
        if($getLesson['code']>0)
        {
            $this->expectOutputString($getLesson['message']);
            return ;
        }
        $lesson = $getLesson['data'];

        $this->assertEquals($mockLesson['title'], $lesson['title']);
        $this->assertEquals($mockLesson['type'], $lesson['type']);
        $this->assertEquals($mockLesson['number'], $lesson['number']);
        $this->assertEquals($mockLesson['seq'], $lesson['seq']);
        $this->assertEquals($mockLesson['free'], $lesson['free']);
        $this->assertEquals($mockLesson['status'], $lesson['status']);
        $this->assertEquals($mockLesson['summary'], $lesson['summary']);
        $this->assertEquals($mockLesson['tags'], $lesson['tags']);
        $this->assertEquals($mockLesson['content'], $lesson['content']);
        $this->assertEquals($mockLesson['mediaId'], $lesson['mediaId']);
        $this->assertEquals($mockLesson['mediaSource'], $lesson['mediaSource']);
        $this->assertEquals($mockLesson['mediaName'], $lesson['mediaName']);
        $this->assertEquals($mockLesson['mediaUri'], $lesson['mediaUri']);
        $this->assertEquals($mockLesson['length'], $lesson['length']);
        $this->assertEquals($mockLesson['materialNum'], $lesson['materialNum']);
        $this->assertEquals($mockLesson['quizNum'], $lesson['quizNum']);
        $this->assertEquals($mockLesson['createdTime'], $lesson['createdTime']);
        $this->assertEquals($mockLesson['updatedTime'], $lesson['updatedTime']);
        $this->assertEquals($mockLesson['testMode'], $lesson['testMode']);
        $this->assertEquals($mockLesson['testStartTime'], $lesson['testStartTime']);
        $this->assertEquals($mockLesson['keypoints'], $lesson['keypoints']);

        return $lesson;
    }

    public function testGetLesson()
    {
        $mockCourse = $this->mockCourse();
        $getCourse = $this->getCourseService()->createCourse($mockCourse);
        if($getCourse['code']>0)
        {
            $this->expectOutputString($getCourse['message']);
            return ;
        }
        $course = $getCourse['data'];

        //one chapter
        $chapter = $this->mockChapter();
        $chapter['courseId'] = $course['id'];
        $getChapter = $this->getCourseService()->createChapter($chapter);
        if($getChapter['code']>0)
        {
            $this->expectOutputString($getChapter['message']);
            return ;
        }
        $chapter = $getChapter['data'];

        $unit = $this->mockUnit();
        $unit['courseId'] = $course['id'];
        $unit['parentId'] = $chapter['id'];
        $getUnit = $this->getCourseService()->createChapter($unit);
        if($getUnit['code']>0)
        {
            $this->expectOutputString($getUnit['message']);
            return ;
        }
        $unit = $getUnit['data'];

        $mockLesson = $this->mockLesson();
        $mockLesson['courseId'] = $course['id'];
        $mockLesson['chapterId'] = $unit['id'];
        $getLesson = $this->getCourseService()->createLesson($mockLesson);
        if($getLesson['code']>0)
        {
            $this->expectOutputString($getLesson['message']);
            return ;
        }
        $lesson = $getLesson['data'];

        $this->assertEquals($mockLesson['title'], $lesson['title']);
    }

    public function testUpdateLesson()
    {
        $mockCourse = $this->mockCourse();
        $getCourse = $this->getCourseService()->createCourse($mockCourse);
        if($getCourse['code']>0)
        {
            $this->expectOutputString($getCourse['message']);
            return ;
        }
        $course = $getCourse['data'];

        //one chapter
        $chapter = $this->mockChapter();
        $chapter['courseId'] = $course['id'];
        $getChapter = $this->getCourseService()->createChapter($chapter);
        if($getChapter['code']>0)
        {
            $this->expectOutputString($getChapter['message']);
            return ;
        }
        $chapter = $getChapter['data'];

        $unit = $this->mockUnit();
        $unit['courseId'] = $course['id'];
        $unit['parentId'] = $chapter['id'];
        $getUnit = $this->getCourseService()->createChapter($unit);
        if($getUnit['code']>0)
        {
            $this->expectOutputString($getUnit['message']);
            return ;
        }
        $unit = $getUnit['data'];

        $mockLesson = $this->mockLesson();
        $mockLesson['courseId'] = $course['id'];
        $mockLesson['chapterId'] = $unit['id'];
        $getLesson = $this->getCourseService()->createLesson($mockLesson);
        if($getLesson['code']>0)
        {
            $this->expectOutputString($getLesson['message']);
            return ;
        }
        $lesson = $getLesson['data'];

        $mockUpdateLesson = $this->mockUpdateLesson();

        $getUpdateLesson = $this->getCourseService()->updateLesson($lesson['courseId'], $lesson['id'], $mockUpdateLesson);
        if($getUpdateLesson['code']>0)
        {
            $this->expectOutputString($getUpdateLesson['message']);
            return ;
        }
        $updateLesson = $getUpdateLesson['data'];

        $this->assertEquals($mockUpdateLesson['title'], $updateLesson['title']);
        $this->assertEquals($mockUpdateLesson['type'], $updateLesson['type']);
        $this->assertEquals($mockUpdateLesson['number'], $updateLesson['number']);
        $this->assertEquals($mockUpdateLesson['seq'], $updateLesson['seq']);
        $this->assertEquals($mockUpdateLesson['free'], $updateLesson['free']);
        $this->assertEquals($mockUpdateLesson['status'], $updateLesson['status']);
        $this->assertEquals($mockUpdateLesson['summary'], $updateLesson['summary']);
        $this->assertEquals($mockUpdateLesson['tags'], $updateLesson['tags']);
        $this->assertEquals($mockUpdateLesson['content'], $updateLesson['content']);
        $this->assertEquals($mockUpdateLesson['mediaId'], $updateLesson['mediaId']);
        $this->assertEquals($mockUpdateLesson['mediaSource'], $updateLesson['mediaSource']);
        $this->assertEquals($mockUpdateLesson['mediaName'], $updateLesson['mediaName']);
        $this->assertEquals($mockUpdateLesson['mediaUri'], $updateLesson['mediaUri']);
        $this->assertEquals($mockUpdateLesson['length'], $updateLesson['length']);
        $this->assertEquals($mockUpdateLesson['materialNum'], $updateLesson['materialNum']);
        $this->assertEquals($mockUpdateLesson['quizNum'], $updateLesson['quizNum']);
        $this->assertEquals($mockUpdateLesson['createdTime'], $updateLesson['createdTime']);
        $this->assertEquals($mockUpdateLesson['updatedTime'], $updateLesson['updatedTime']);
        $this->assertEquals($mockUpdateLesson['testMode'], $updateLesson['testMode']);
        $this->assertEquals($mockUpdateLesson['testStartTime'], $updateLesson['testStartTime']);
        $this->assertEquals($mockUpdateLesson['keypoints'], $updateLesson['keypoints']);
    }

    public function testDeleteChapter()
    {
        $mockCourse = $this->mockCourse();
        $getCourse = $this->getCourseService()->createCourse($mockCourse);
        if($getCourse['code']>0)
        {
            $this->expectOutputString($getCourse['message']);
            return ;
        }
        $course = $getCourse['data'];

        //one chapter
        $chapter = $this->mockChapter();
        $chapter['courseId'] = $course['id'];
        $getChapter = $this->getCourseService()->createChapter($chapter);
        if($getChapter['code']>0)
        {
            $this->expectOutputString($getChapter['message']);
            return ;
        }
        $chapter = $getChapter['data'];

        $unit = $this->mockUnit();
        $unit['courseId'] = $course['id'];
        $unit['parentId'] = $chapter['id'];
        $getUnit = $this->getCourseService()->createChapter($unit);
        if($getUnit['code']>0)
        {
            $this->expectOutputString($getUnit['message']);
            return ;
        }
        $unit = $getUnit['data'];

        $mockLesson = $this->mockLesson();
        $mockLesson['courseId'] = $course['id'];
        $mockLesson['chapterId'] = $unit['id'];
        $getLesson = $this->getCourseService()->createLesson($mockLesson);
        if($getLesson['code']>0)
        {
            $this->expectOutputString($getLesson['message']);
            return ;
        }
        $lesson = $getLesson['data'];

        $this->getCourseService()->deleteLesson($lesson['courseId'], $lesson['id']);

        $getDeletedLesson = $this->getCourseService()->getLesson($lesson['courseId'],$lesson['id']);
        if($getDeletedLesson['code']==0)
            $deletedLesson = $getDeletedLesson['data'];
        else
            $deletedLesson = array();

        $this->assertEmpty($deletedLesson);

    }

    protected function mockCourse()
    {
        return array(
            'title'             =>  '测试的课程',
            'subtitle'          =>  '课程副标题',
            'status'            =>  'published',
            'type'              =>  'normal',
            'lessonNum'         =>  '10',
            'category'          =>  '经济管理,管理学',
            'tags'              =>  '经济学,管理',
            'keypoints'         =>  array('100001'=>'经济学','100002'=>'管理学'),
            'smallPicture'      =>  'http://wwww.wqketang.com/small.png',
            'middlePicture'     =>  'http://wwww.wqketang.com/middle.png',
            'largePicture'      =>  'http://wwww.wqketang.com/large.png',
            'about'             =>  '这是课程的介绍',
            'goals'             =>  array('目标1掌握','目标2数量掌握'),
            'audiences'         =>  array('大专院校','在校学生'),
            'parentId'          =>  0,
            'createdTime'       =>  time(),
            'updatedTime'       =>  time()
        );
    }

    protected function mockChapter()
    {
        return array(
            'title'             =>  '第一章',
            'type'              =>  'chapter',
            'parentId'          =>  0,
            'number'            =>  1,
            'seq'               =>  1,
            'keypoints'         =>  array('100001'=>'经济学','100002'=>'管理学'),
            'createdTime'       =>  time(),
        );
    }

    protected function mockUnit()
    {
        return array(
            'title'             =>  '第一章',
            'type'              =>  'unit',
            'parentId'          =>  0,
            'number'            =>  1,
            'seq'               =>  2,
            'keypoints'         =>  array('100001'=>'经济学','100002'=>'管理学'),
            'createdTime'       =>  time(),
        );
    }

    protected function mockLesson()
    {
        return array(
            'title'             =>  '第一个课时的名字',
            'type'              =>  'text',
            'number'            =>  2,
            'seq'               =>  3,
            'free'              =>  1,
            'status'            =>  'published',
            'summary'           =>  '这是第一个课时的概况',
            'tags'              =>  '数字,技术',
            'content'           =>  '<p>这一讲的内容第一段</p><p>第二段</p>',
            'mediaId'           =>  1111,
            'mediaSource'       =>  'izhixue',
            'mediaName'         =>  '王老师讲课Node',
            'mediaUri'          =>  'https://www.qiniu.com/aa.mpv',
            'length'            =>  '1111',
            'materialNum'       =>  10,
            'quizNum'           =>  20,
            'createdTime'       =>  time(),
            'updatedTime'       =>  time(),
            'testMode'          =>  'normal',//normal/realTime,
            'testStartTime'     =>  time(),
            'keypoints'         =>  array('100001'=>'经济学1','100002'=>'管理学2')
        );
    }

    protected function mockUpdateLesson()
    {
        return array(
            'title'             =>  '第一个课时的名字更新',
            'type'              =>  'ppt',
            'number'            =>  1,
            'seq'               =>  2,
            'free'              =>  0,
            'status'            =>  'unpublished',
            'summary'           =>  '这是第一个课时的概况更新',
            'tags'              =>  '数字,技术,更新',
            'content'           =>  '<p>这一讲的内容第一段更新</p><p>第二段</p>',
            'mediaId'           =>  11112,
            'mediaSource'       =>  'izhixue',
            'mediaName'         =>  '王老师讲课Node更新',
            'mediaUri'          =>  'https://www.qiniu.com/1aa.mpv',
            'length'            =>  '11112',
            'materialNum'       =>  20,
            'quizNum'           =>  10,
            'createdTime'       =>  time(),
            'updatedTime'       =>  time(),
            'testMode'          =>  'normal',//normal/realTime,
            'testStartTime'     =>  time(),
            'keypoints'         =>  array('100001'=>'经济学1','100002'=>'管理学2','10003'=>'更新')
        );
    }

    protected function getCourseService()
    {
        return $this->biz->service('Course:CourseService');
    }

}