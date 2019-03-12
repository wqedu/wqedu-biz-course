<?php
/**
 * Created by PhpStorm.
 * User: wangqianjin
 * Date: 2019/3/12
 * Time: 3:19 PM
 */
namespace Biz\Course\Service;

use Codeages\Biz\Framework\Service\Exception\AccessDeniedException;

interface CourseQuestionService
{
    public function getQuestion($id);

    public function createQuestion($question);

    public function updateQuestion($id, $fields);

    public function deleteQuestion($id);

    public function searchQuestionCount($conditions);

    public function searchQuestions($conditions, $sort, $start, $limit);

}