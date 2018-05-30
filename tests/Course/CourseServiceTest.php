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

        $course = $this->getInvoiceService()->applyInvoice($mockCourse);
        $this->assertEquals($mockCourse['title'], $course['title']);
        $this->assertEquals('unchecked', $course['status']);

        $default = $this->getInvoiceTemplateService()->getDefaultTemplate($course['user_id']);
        $this->assertNotNull($default);
    }

    public function testGetCourse()
    {
        $mockCourse = $this->mockCourse();
        $course = $this->getCourseService()->createCourse($mockCourse);

        $result = $this->getCourseService()->getCourse($course['id']);

        $this->assertEquals($mockCourse['title'], $result['title']);
        $this->assertEquals('unchecked', $result['status']);
    }

    protected function mockCourse()
    {
        return array(
            'title' => '购买商品',
            'callback' => array('url' => 'http://try6.edusoho.cn/'),
            'source' => 'custom',
            'price_type' => 'coin',
            'user_id' => $this->biz['user']['id'],
            'created_reason' => '购买',
            'create_extra' => array(
                'xxx' => 'xxx',
            ),
            'device' => 'wap',
            'expired_refund_days' => 5,
        );
    }


    protected function getCourseService()
    {
        return $this->biz->service('Course:CourseService');
    }
}