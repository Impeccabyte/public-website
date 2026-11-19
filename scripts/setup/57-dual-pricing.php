<?php

/**
 * @file
 * Section changes for the revised Dual pricing page: the comparison table
 * gains an intro under its heading and a footnote under the table.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/57-dual-pricing.php
 * then export config.
 */

require_once __DIR__ . '/_helpers.php';

$p = 'paragraph';
ib_field($p, 'comparison_table', 'field_intro', 'string_long', 'Intro', ['weight' => 1, 'description' => 'Shown under the heading.', 'widget_settings' => ['rows' => 2]]);
ib_field($p, 'comparison_table', 'field_footnote', 'string', 'Footnote', ['weight' => 5, 'description' => 'Small print under the table.', 'storage' => ['max_length' => 255]]);

print "Dual pricing sections are in place.\n";
