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
        $course = $this->getCourseService()->createCourse($mockCourse);

        $mockChapter = $this->mockChapter();
        $mockChapter['courseId'] = $course['id'];
        $chapter = $this->getCourseService()->createChapter($mockChapter);

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

        $unit = $this->getCourseService()->createChapter($mockUnit);

        $this->assertEquals($mockUnit['title'], $unit['title']);
        $this->assertEquals($mockUnit['type'], $unit['type']);
        $this->assertEquals($mockUnit['parentId'], $unit['parentId']);
        $this->assertEquals($mockUnit['number'], $unit['number']);
        $this->assertEquals($mockUnit['seq'], $unit['seq']);
        $this->assertEquals($mockUnit['keypoints'], $unit['keypoints']);
        $this->assertEquals($mockUnit['courseId'], $unit['courseId']);

        return $chapter;
    }

    /**
     * @depends testCreateChapter
     */

    public function testGetChapter(array $chapter)
    {
        $chapter = $this->getCourseService()->createChapter($chapter);

        $result = $this->getCourseService()->getChapter($chapter['courseId'],$chapter['id']);

        $this->assertEquals($chapter['title'], $result['title']);
    }

    /**
     * @depends testCreateChapter
     */
    public function testUpdateChapter(array $chapter)
    {
        $mockUpdateChapter = $this->mockUpdateChapter();

        $chapter = $this->getCourseService()->createChapter($chapter);

        $updateChapter = $this->getCourseService()->updateChapter($chapter['courseId'], $chapter['id'], $mockUpdateChapter);

        $this->assertEquals($mockUpdateChapter['title'], $updateChapter['title']);
        $this->assertEquals($mockUpdateChapter['type'], $updateChapter['type']);
        $this->assertEquals($mockUpdateChapter['parentId'], $updateChapter['parentId']);
        $this->assertEquals($mockUpdateChapter['number'], $updateChapter['number']);
        $this->assertEquals($mockUpdateChapter['seq'], $updateChapter['seq']);
        $this->assertEquals($mockUpdateChapter['keypoints'], $updateChapter['keypoints']);
    }

    /**
     * @depends testCreateChapter
     */
    public function testDeleteChapter(array $chapter)
    {
        $chapter = $this->getCourseService()->createChapter($chapter);

        $this->getCourseService()->deleteChapter($chapter['courseId'], $chapter['id']);

        $deletedChapter = $this->getCourseService()->getChapter($chapter['courseId'],$chapter['id']);

        $this->assertEmpty($deletedChapter);
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
            'number'            =>  0,
            'seq'               =>  0,
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
            'number'            =>  0,
            'seq'               =>  0,
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