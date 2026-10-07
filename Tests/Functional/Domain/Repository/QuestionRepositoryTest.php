<?php
declare(strict_types=1);

namespace Cpsit\T3faq\Tests\Functional\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2021 Dirk Wenzel <wenzel@cps-it.de>
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Cpsit\T3faq\Domain\Model\Dto\QuestionDemand;
use Cpsit\T3faq\Domain\Repository\QuestionRepository;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class QuestionRepositoryTest extends FunctionalTestCase
{
    protected QuestionRepository $subject;

    protected array $testExtensionsToLoad = [
        'cpsit/cps-utility',
        'cpsit/t3faq',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->get(QuestionRepository::class);
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/Database/tx_t3faq_domain_model_question.csv');
    }

    public function testFindAll(): void
    {
        $result = $this->subject->findAll();
        self::assertEmpty($result);
    }

    public function testFindDemandedFindsQuestionsBySinglePageId(): void
    {
        $pages = [1];
        $demand = new QuestionDemand();
        $demand->setPageIds($pages);
        $result = $this->subject->findDemanded($demand);
        self::assertCount(
            1,
            $result->toArray()
        );
    }

    public function testFindDemandedFindsQuestionsByPageIds(): void
    {
        $pages = [1, 3];
        $demand = new QuestionDemand();
        $demand->setPageIds($pages);
        $result = $this->subject->findDemanded($demand);
        self::assertCount(
            2,
            $result
        );
    }

}
