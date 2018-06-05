<?php
/**
 * Created by PhpStorm.
 * User: qujiyong
 * Date: 2018/5/29
 * Time: 下午12:04
 */

namespace Tests;

class CourseServiceTest extends IntegrationTestCase
{
    public function testCreateCourse()
    {
        $mockCourse = $this->mockCourse();

        $getCourse = $this->getCourseService()->createCourse($mockCourse);
        if($getCourse['code']==0)
        {
            $course = $getCourse['data'];
            $this->assertEquals($mockCourse['title'], $course['title']);
            $this->assertEquals($mockCourse['subtitle'], $course['subtitle']);
            $this->assertEquals($mockCourse['status'], $course['status']);
            $this->assertEquals($mockCourse['type'], $course['type']);
            $this->assertEquals($mockCourse['lessonNum'], $course['lessonNum']);
            $this->assertEquals($mockCourse['category'], $course['category']);
            $this->assertEquals($mockCourse['tags'], $course['tags']);
            $this->assertEquals($mockCourse['keypoints'], $course['keypoints']);
            $this->assertEquals($mockCourse['smallPicture'], $course['smallPicture']);
            $this->assertEquals($mockCourse['middlePicture'], $course['middlePicture']);
            $this->assertEquals($mockCourse['largePicture'], $course['largePicture']);
            $this->assertEquals($mockCourse['about'], $course['about']);
            $this->assertEquals($mockCourse['goals'], $course['goals']);
            $this->assertEquals($mockCourse['audiences'], $course['audiences']);
            $this->assertEquals($mockCourse['parentId'], $course['parentId']);
            $this->assertEquals($mockCourse['createdTime'], $course['createdTime']);
            $this->assertEquals($mockCourse['updatedTime'], $course['updatedTime']);

            return $course;
        }
        else
        {
            $this->expectOutputString($getCourse['message']);
            $this->assertEquals(true, $getCourse['code']>0);
            return array();
        }
    }

    public function testGetCourse()
    {
        $mockCourse = $this->mockCourse();
        $getCourse = $this->getCourseService()->createCourse($mockCourse);

        if($getCourse['code']==0)
        {
            $course = $getCourse['data'];
            $this->assertEquals($mockCourse['title'], $course['title']);
            $this->assertEquals('published', $course['status']);
        }
        else
        {
            $this->expectOutputString($getCourse['message']);
            $this->assertEquals(true, $getCourse['code']>0);
            return array();
        }
    }


    public function testUpdateCourse()
    {
        $mockCourse = $this->mockCourse();
        $mockUpdateCourse = $this->mockUpdateCourse();

        $getCourse = $this->getCourseService()->createCourse($mockCourse);
        if($getCourse['code']==0)
        {
            $course = $getCourse['data'];
            $getUpdateCourse = $this->getCourseService()->updateCourse($course['id'], $mockUpdateCourse);
            if($getUpdateCourse['code']==0)
            {
                $updateCourse = $getUpdateCourse['data'];
                $this->assertEquals($mockUpdateCourse['title'], $updateCourse['title']);
                $this->assertEquals($mockUpdateCourse['subtitle'], $updateCourse['subtitle']);
                $this->assertEquals($mockUpdateCourse['status'], $updateCourse['status']);
                $this->assertEquals($mockUpdateCourse['type'], $updateCourse['type']);
                $this->assertEquals($mockUpdateCourse['lessonNum'], $updateCourse['lessonNum']);
                $this->assertEquals($mockUpdateCourse['category'], $updateCourse['category']);
                $this->assertEquals($mockUpdateCourse['tags'], $updateCourse['tags']);
                $this->assertEquals($mockUpdateCourse['keypoints'], $updateCourse['keypoints']);
                $this->assertEquals($mockUpdateCourse['smallPicture'], $updateCourse['smallPicture']);
                $this->assertEquals($mockUpdateCourse['middlePicture'], $updateCourse['middlePicture']);
                $this->assertEquals($mockUpdateCourse['largePicture'], $updateCourse['largePicture']);
                $this->assertEquals($mockUpdateCourse['about'], $updateCourse['about']);
                $this->assertEquals($mockUpdateCourse['goals'], $updateCourse['goals']);
                $this->assertEquals($mockUpdateCourse['audiences'], $updateCourse['audiences']);
                $this->assertEquals($mockUpdateCourse['parentId'], $updateCourse['parentId']);
            }
            else
            {
                $this->expectOutputString($getUpdateCourse['message']);
                $this->assertEquals(true, $getUpdateCourse['code']>0);
                return array();
            }

        }
        else
        {
            $this->expectOutputString($getCourse['message']);
            $this->assertEquals(true, $getCourse['code']>0);
            return array();
        }
    }

    public function testDeleteCourse()
    {
        $mockCourse = $this->mockCourse();
        $getCourse = $this->getCourseService()->createCourse($mockCourse);
        if($getCourse['code']>0)
            return ;
        $course = $getCourse['data'];
        $this->getCourseService()->deleteCourse($course['id']);

        $getDeletedCourse = $this->getCourseService()->getCourse($course['id']);
        if($getDeletedCourse['code']==0)
            $deletedCourse = $getDeletedCourse['data'];
        else
            $deletedCourse = array();

        $this->assertEmpty($deletedCourse);
    }

    public function testGetItems()
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

        $lesson = $this->mockLesson();
        $lesson['courseId'] = $course['id'];
        $lesson['chapterId'] = $unit['id'];
        $this->getCourseService()->createLesson($lesson);
        $this->getCourseService()->createLesson($lesson);

        //to chapter
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

        $lesson = $this->mockLesson();
        $lesson['courseId'] = $course['id'];
        $lesson['chapterId'] = $unit['id'];
        $this->getCourseService()->createLesson($lesson);
        $this->getCourseService()->createLesson($lesson);

        $getItems = $this->getCourseService()->getCourseItems($course['id']);
        if($getItems['code']==0)
        {
            $items = $getItems['data'];
            $this->assertEquals(8, count($items));
        }
        else
        {
            $this->expectOutputString($getItems['message']);
        }

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

    protected function mockUpdateCourse()
    {
        return array(
            'title'             =>  '测试的课程更新',
            'subtitle'          =>  '课程副标题更新',
            'status'            =>  'draft',
            'type'              =>  'live',
            'lessonNum'         =>  '11',
            'category'          =>  '经济管理,管理学,会计学',
            'tags'              =>  '经济学,管理,会计',
            'keypoints'         =>  array('100001'=>'经济学','100002'=>'管理学','10003'=>'会计学'),
            'smallPicture'      =>  'http://wwww.wqketang.com/small1.png',
            'middlePicture'     =>  'http://wwww.wqketang.com/middle1.png',
            'largePicture'      =>  'http://wwww.wqketang.com/large1.png',
            'about'             =>  '这是课程的介绍更新',
            'goals'             =>  array('目标1掌握','目标2数量掌握','掌握要点'),
            'audiences'         =>  array('大专院校','在校学生','大众'),
            'parentId'          =>  1,
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


    protected function getCourseService()
    {
        return $this->biz->service('Course:CourseService');
    }
}