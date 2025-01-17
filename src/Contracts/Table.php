<?php

declare(strict_types=1);

namespace Ticketsprinter\TSLogViewer\Contracts;

/**
 * Interface  Table
 *
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Table
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get table header.
     *
     * @return array
     */
    public function header();

    /**
     * Get table rows.
     *
     * @return array
     */
    public function rows();

    /**
     * Get table footer.
     *
     * @return array
     */
    public function footer();
}
