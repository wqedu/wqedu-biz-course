<?php
/**
 * Created by PhpStorm.
 * User: qujiyong
 * Date: 2018/5/29
 * Time: 下午12:04
 */

namespace Tests;

class InvoiceServiceTest extends IntegrationTestCase
{
    public function testGetCourse()
    {
        $mockCourse = $this->mockCourse();
        $course = $this->getCourseService()->createCourse($mockCourse);

        $result = $this->getCourseService()->getCourse($course['id']);

        $this->assertEquals($mockCourse['title'], $result['title']);
        $this->assertEquals('published', $result['status']);
    }

    public function testCreateCourse()
    {
        $mockCourse = $this->mockCourse();

        $course = $this->getCourseService()->createCourse($mockCourse);
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
    }

    public function testUpdateCourse()
    {
        $mockCourse = $this->mockCourse();

        $mockUpdateCourse = $this->mockUpdateCourse();

        $course = $this->getCourseService()->createCourse($mockCourse);

        $updateCourse = $this->getCourseService()->updateCourse($course['id'], $mockUpdateCourse);

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


    protected function getCourseService()
    {
        return $this->biz->service('Course:CourseService');
    }
}