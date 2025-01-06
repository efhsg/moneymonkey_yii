<?php /** @noinspection PhpUnused */

/** @noinspection PhpUndefinedMethodInspection */

namespace tests\acceptance;

use AcceptanceTester;
use yii\helpers\Url;

class AboutCest
{
    public function ensureThatAboutWorks(AcceptanceTester $I): void
    {
        $I->amOnPage(Url::toRoute('/site/about'));
        $I->see('About', 'h1');
    }
}
