<?php
/**
 * Created by PhpStorm.
 * User: qujiyong
 * Date: 2018/5/29
 * Time: 下午12:04
 */

namespace Tests;

class CourseChapterServiceTest extends IntegrationTestCase
{
    public function testCreateChapter()
    {
        $mockCourse = $this->mockCourse();
        $getCourse = $this->getCourseService()->createCourse($mockCourse);
        if($getCourse['code']>0)
        {
            $this->expectOutputString($getCourse['message']);
            return ;
        }
        $course = $getCourse['data'];

        $mockChapter = $this->mockChapter();
        $mockChapter['courseId'] = $course['id'];
        $getChapter = $this->getCourseService()->createChapter($mockChapter);
        if($getChapter['code']>0)
        {
            $this->expectOutputString($getChapter['message']);
            return ;
        }
        $chapter = $getChapter['data'];


        $this->assertEquals($mockChapter['title'], $chapter['title']);
        $this->assertEquals($mockChapter['type'], $chapter['type']);
        $this->assertEquals($mockChapter['parentId'], $chapter['parentId']);
        $this->assertEquals($mockChapter['number'], $chapter['number']);
        $this->assertEquals($mockChapter['seq'], $chapter['seq']);
        $this->assertEquals($mockChapter['keypoints'], $chapter['keypoints']);
        $this->assertEquals($mockChapter['courseId'], $chapter['courseId']);

        $mockUnit = $this->mockUnit();
        $mockUnit['courseId'] = $chapter['courseId'];
        $mockUnit['parentId'] = $chapter['id'];
        $getUnit = $this->getCourseService()->createChapter($mockUnit);
        if($getUnit['code']>0)
        {
            $this->expectOutputString($getUnit['message']);
            return ;
        }
        $unit = $getUnit['data'];

        $this->assertEquals($mockUnit['title'], $unit['title']);
        $this->assertEquals($mockUnit['type'], $unit['type']);
        $this->assertEquals($mockUnit['parentId'], $unit['parentId']);
        $this->assertEquals($mockUnit['number'], $unit['number']);
        $this->assertEquals($mockUnit['seq'], $unit['seq']);
        $this->assertEquals($mockUnit['keypoints'], $unit['keypoints']);
        $this->assertEquals($mockUnit['courseId'], $unit['courseId']);

        return $chapter;
    }

    public function testGetChapter()
    {
        $mockCourse = $this->mockCourse();
        $getCourse = $this->getCourseService()->createCourse($mockCourse);
        if($getCourse['code']>0)
        {
            $this->expectOutputString($getCourse['message']);
            return ;
        }
        $course = $getCourse['data'];

        $mockChapter = $this->mockChapter();
        $mockChapter['courseId'] = $course['id'];
        $getChapter = $this->getCourseService()->createChapter($mockChapter);
        if($getChapter['code']>0)
        {
            $this->expectOutputString($getChapter['message']);
            return ;
        }
        $chapter = $getChapter['data'];

        $this->assertEquals($mockChapter['title'], $chapter['title']);
    }


    public function testUpdateChapter()
    {
        $mockUpdateChapter = $this->mockUpdateChapter();

        $mockCourse = $this->mockCourse();
        $getCourse = $this->getCourseService()->createCourse($mockCourse);
        if($getCourse['code']>0)
        {
            $this->expectOutputString($getCourse['message']);
            return ;
        }
        $course = $getCourse['data'];

        $mockChapter = $this->mockChapter();
        $mockChapter['courseId'] = $course['id'];
        $getChapter = $this->getCourseService()->createChapter($mockChapter);
        if($getChapter['code']>0)
        {
            $this->expectOutputString($getChapter['message']);
            return ;
        }
        $chapter = $getChapter['data'];

        $getUpdateChapter = $this->getCourseService()->updateChapter($chapter['courseId'], $chapter['id'], $mockUpdateChapter);
        if($getUpdateChapter['code']>0)
        {
            $this->expectOutputString($getUpdateChapter['message']);
            return ;
        }
        $updateChapter = $getUpdateChapter['data'];

        $this->assertEquals($mockUpdateChapter['title'], $updateChapter['title']);
        $this->assertEquals($mockUpdateChapter['type'], $updateChapter['type']);
        $this->assertEquals($mockUpdateChapter['parentId'], $updateChapter['parentId']);
        $this->assertEquals($mockUpdateChapter['number'], $updateChapter['number']);
        $this->assertEquals($mockUpdateChapter['seq'], $updateChapter['seq']);
        $this->assertEquals($mockUpdateChapter['keypoints'], $updateChapter['keypoints']);
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

        $mockChapter = $this->mockChapter();
        $mockChapter['courseId'] = $course['id'];
        $getChapter = $this->getCourseService()->createChapter($mockChapter);
        if($getChapter['code']>0)
        {
            $this->expectOutputString($getChapter['message']);
            return ;
        }
        $chapter = $getChapter['data'];

        $this->getCourseService()->deleteChapter($chapter['courseId'], $chapter['id']);

        $getDeletedChapter = $this->getCourseService()->getChapter($chapter['courseId'],$chapter['id']);
        if($getDeletedChapter['code']==0)
            $deletedChapter = $getDeletedChapter['data'];
        else
            $deletedChapter = array();

        $this->assertEmpty($deletedChapter);
    }

    public function testGetCourseChapters()
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

        $getChapters = $this->getCourseService()->getCourseChapters($course['id']);
        if($getChapters['code']==0)
        {
            $chapters = $getChapters['data'];
            $this->assertEquals($chapters[0], $chapter);
            $this->assertEquals($chapters[1], $unit);
        }
        else
        {
            $this->expectOutputString($getChapters['message']);
            return ;
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

    protected function mockUpdateChapter()
    {
        return array(
            'title'             =>  '第一章更新',
            'type'              =>  'unit',
            'parentId'          =>  1,
            'number'            =>  2,
            'seq'               =>  3,
            'keypoints'         =>  array('100001'=>'经济学1','100002'=>'管理学2')
        );
    }

    protected function getCourseService()
    {
        return $this->biz->service('Course:CourseService');
    }

}