<?php

declare(strict_types=1);

namespace Ticketsprinter\TSLogViewer\Tests\Entities;

use Ticketsprinter\TSLogViewer\Entities\Log;
use Ticketsprinter\TSLogViewer\Tests\TestCase;

/**
 * Class     LogTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LogTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Ticketsprinter\TSLogViewer\Entities\Log */
    private $log;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    protected function setUp(): void
    {
        parent::setUp();

        $this->log = $this->getLog('2015-01-01');
    }

    protected function tearDown(): void
    {
        unset($this->log);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        $entries = $this->log->entries();

        static::assertInstanceOf(Log::class, $this->log);
        static::assertDate($this->log->date);
        static::assertCount(8, $entries);
        static::assertSame(8, $entries->count());
        static::assertLogEntries($this->log->date, $entries);
    }

    /**
     * @test
     *
     * @dataProvider provideDates
     *
     * @param  string  $date
     */
    public function it_can_get_date($date): void
    {
        $log = $this->getLog($date);

        static::assertDate($log->date);
        static::assertSame($date, $log->date);
    }

    /**
     * @test
     *
     * @dataProvider provideDates
     *
     * @param  string  $date
     */
    public function it_can_get_path($date): void
    {
        static::assertFileExists($this->getLog($date)->getPath());
    }

    /**
     * @test
     *
     * @dataProvider provideDates
     *
     * @param  string  $date
     */
    public function it_can_get_all_entries($date): void
    {
        $entries = $this->getLog($date)->entries();

        static::assertCount(8, $entries);
        static::assertSame(8, $entries->count());
        static::assertLogEntries($date, $entries);
    }

    /**
     * @test
     *
     * @dataProvider provideDates
     *
     * @param  string  $date
     */
    public function it_can_get_all_entries_by_level($date): void
    {
        $log = $this->getLog($date);

        foreach ($this->getLogLevels() as $level) {
            static::assertCount(1, $log->entries($level));
            static::assertLogEntries($date, $log->entries());
        }
    }

    /** @test */
    public function it_can_get_log_stats(): void
    {
        foreach ($this->log->stats() as $level => $counter) {
            static::assertSame($level === 'all' ? 8 : 1, $counter);
        }
    }

    /**
     * @test
     *
     * @dataProvider provideDates
     *
     * @param  string  $date
     */
    public function it_can_get_tree($date): void
    {
        $menu = $this->getLog($date)->tree();

        static::assertCount(9, $menu);

        foreach ($menu as $level => $menuItem) {
            if ($level === 'all') {
                static::assertEquals(8, $menuItem['count']);
            }
            else {
                static::assertInLogLevels($level);
                static::assertInLogLevels($menuItem['name']);
                static::assertEquals(1, $menuItem['count']);
            }
        }
    }

    /**
     * @test
     *
     * @dataProvider provideDates
     *
     * @param  string  $date
     */
    public function it_can_get_translated_menu($date): void
    {
        foreach (self::$locales as $locale) {
            $this->app->setLocale($locale);

            $menu = $this->getLog($date)->menu();

            static::assertCount(9, $menu);

            foreach ($menu as $level => $menuItem) {
                if ($level === 'all') {
                    static::assertSame(8, $menuItem['count']);
                    static::assertTranslatedLevel($locale, $level, $menuItem['name']);
                }
                else {
                    static::assertInLogLevels($level);
                    static::assertTranslatedLevel($locale, $level, $menuItem['name']);
                    static::assertSame(1, $menuItem['count']);
                }
            }
        }
    }

    /** @test */
    public function it_can_convert_to_json(): void
    {
        static::assertJsonObject($this->log);
    }

    /* -----------------------------------------------------------------
     |  Data providers
     | -----------------------------------------------------------------
     */

    /**
     * Provide valid dates.
     *
     * @return array
     */
    public function provideDates(): array
    {
        return [
            ['2015-01-01'],
            ['2015-01-02']
        ];
    }
}
