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
    public function testCreateCourse()
    {
        $mockCourse = $this->mockCourse();

        $course = $this->getCourseService()->createCourse($mockCourse);
        $this->assertEquals($mockCourse['title'], $course['title']);
        $this->assertEquals('published', $course['status']);
    }

    public function testGetCourse()
    {
        $mockCourse = $this->mockCourse();
        $course = $this->getCourseService()->createCourse($mockCourse);

        $result = $this->getCourseService()->getCourse($course['id']);

        $this->assertEquals($mockCourse['title'], $result['title']);
        $this->assertEquals('published', $result['status']);
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
            'largePicture'        =>  'http://wwww.wqketang.com/big.png',
            'about'             =>  '这是课程的介绍',
            'goals'             =>  array('目标1掌握','目标2数量掌握'),
            'audiences'         =>  array('大专院校','在校学生'),
            'parrentId'         =>  0,
            'createdTime'       =>  time(),
            'updatedTime'       =>  time()
        );
    }


    protected function getCourseService()
    {
        return $this->biz->service('Course:CourseService');
    }
}