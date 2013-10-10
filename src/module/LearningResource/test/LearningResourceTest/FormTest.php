<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace LearningResourceTest;

use LearningResource\Form\ArticleForm;
use LearningResource\Form\GroupedTextExerciseForm;
use LearningResource\Form\TextExerciseForm;
use LearningResource\Form\TextExerciseGroupForm;
use LearningResource\Form\TextSolutionForm;

abstract class FormTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {}

    public function testArticleForm()
    {
        new ArticleForm();
    }

    public function testGroupedTextExerciseForm()
    {
        new GroupedTextExerciseForm();
    }

    public function testTextExerciseForm()
    {
        new TextExerciseForm();
    }

    public function testTextExerciseGroupForm()
    {
        new TextExerciseGroupForm();
    }

    public function testTextSolutionForm()
    {
        new TextSolutionForm();
    }
}