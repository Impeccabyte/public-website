<?php

/**
 * @file
 * Phase 3, page sections: every paragraph type from the build brief and the
 * sections field on landing pages.
 *
 * One paragraph type maps to one theme component of the same name. Field
 * storages are shared between types where they mean the same thing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/30-paragraphs.php
 * then export config.
 */

require_once __DIR__ . '/_helpers.php';

$p = 'paragraph';
$string = fn(string $bundle, string $name, string $label, int $weight, array $o = []) => ib_field($p, $bundle, $name, 'string', $label, ['weight' => $weight, 'storage' => ['max_length' => 255]] + $o);
$text = fn(string $bundle, string $name, string $label, int $weight, array $o = []) => ib_field($p, $bundle, $name, 'string_long', $label, ['weight' => $weight, 'widget_settings' => ['rows' => 3]] + $o);
$link = fn(string $bundle, string $name, string $label, int $weight, array $o = []) => ib_field($p, $bundle, $name, 'link', $label, ['weight' => $weight, 'settings' => ['link_type' => 17, 'title' => 2]] + $o);
$bool = fn(string $bundle, string $name, string $label, int $weight, array $o = []) => ib_field($p, $bundle, $name, 'boolean', $label, ['weight' => $weight, 'widget' => 'boolean_checkbox', 'widget_settings' => ['display_label' => TRUE]] + $o);
$items = fn(string $bundle, string $label, array $types, int $weight, int $cardinality = -1) => ib_paragraphs_field($p, $bundle, 'field_items', $label, $types, $cardinality, $weight);
$header = function (string $bundle, bool $intro = TRUE) use ($string, $text): void {
  $string($bundle, 'field_eyebrow', 'Eyebrow', 0, ['description' => 'Short label above the heading.']);
  $string($bundle, 'field_heading', 'Heading', 1);
  if ($intro) {
    $text($bundle, 'field_intro', 'Intro', 2);
  }
};

// Items used inside sections.
ib_paragraph_type('feature_card', 'Feature card', 'One card in a feature grid.');
$string('feature_card', 'field_icon', 'Icon', 0, ['description' => 'A Lucide icon name, such as store or globe.']);
$string('feature_card', 'field_title', 'Title', 1, ['required' => TRUE]);
$text('feature_card', 'field_body', 'Body', 2);
$link('feature_card', 'field_link', 'Link', 3);
$bool('feature_card', 'field_highlight', 'Highlight this card', 4);

ib_paragraph_type('setup_line', 'Setup line', 'One line of a typical setup.');
$string('setup_line', 'field_title', 'Name', 0, ['required' => TRUE]);
$string('setup_line', 'field_note', 'Note', 1, ['description' => 'Shown under the name. Keep prices out of this field.']);
$string('setup_line', 'field_price_note', 'Note with price', 2, ['description' => 'Shown in place of the note while "Show prices" is on.']);

ib_paragraph_type('chip_item', 'Industry chip', 'One industry in the industry picker, with its typical setup.');
ib_field($p, 'chip_item', 'field_industry', 'entity_reference', 'Industry', ['storage' => ['target_type' => 'taxonomy_term'], 'settings' => ib_ref_terms('industry'), 'required' => TRUE, 'widget' => 'options_select', 'weight' => 0]);
$string('chip_item', 'field_title', 'Title', 1, ['required' => TRUE]);
$text('chip_item', 'field_body', 'Body', 2);
$link('chip_item', 'field_link', 'Link', 3);
$items('chip_item', 'Typical setup', ['setup_line'], 4);

ib_paragraph_type('stat', 'Stat', 'A value and its label.');
$string('stat', 'field_value', 'Value', 0, ['required' => TRUE]);
$string('stat', 'field_label', 'Label', 1, ['required' => TRUE]);
$bool('stat', 'field_is_price', 'The value is a price', 2, ['description' => 'Hidden while "Show prices" is off.']);

ib_paragraph_type('step', 'Step', 'One numbered step.');
$string('step', 'field_title', 'Title', 0, ['required' => TRUE]);
$text('step', 'field_body', 'Body', 1);

ib_paragraph_type('table_row', 'Table row', 'One row of a comparison table.');
$string('table_row', 'field_label', 'Row label', 0, ['required' => TRUE]);
ib_field($p, 'table_row', 'field_cells', 'string_long', 'Cells', ['cardinality' => -1, 'weight' => 1, 'description' => 'One cell per column, in column order.', 'widget_settings' => ['rows' => 2]]);
$bool('table_row', 'field_is_price', 'This row holds prices', 2, ['description' => 'Cells show the pricing placeholder while "Show prices" is off.']);

ib_paragraph_type('pricing_card', 'Pricing card', 'One pricing program.');
$string('pricing_card', 'field_title', 'Name', 0, ['required' => TRUE]);
$string('pricing_card', 'field_badge', 'Badge', 1);
$text('pricing_card', 'field_body', 'Description', 2);
$string('pricing_card', 'field_value', 'Starting-at value', 3, ['description' => 'For example $19.95/mo. Hidden while "Show prices" is off.']);
$string('pricing_card', 'field_unit', 'Unit', 4, ['description' => 'The line under the value, such as per card sale.']);
ib_field($p, 'pricing_card', 'field_bullets', 'string', 'Bullets', ['cardinality' => -1, 'weight' => 5]);
$link('pricing_card', 'field_link', 'Link', 6);
$bool('pricing_card', 'field_featured', 'Feature this card', 7);

ib_paragraph_type('faq_item', 'Question', 'One question and its answer.');
$string('faq_item', 'field_question', 'Question', 0, ['required' => TRUE]);
ib_field($p, 'faq_item', 'field_answer', 'text_long', 'Answer', ['required' => TRUE, 'weight' => 1, 'settings' => ['allowed_formats' => ['basic_html']]]);

ib_paragraph_type('bundle_item', 'Setup item', 'One device or solution in a recommended setup.');
$string('bundle_item', 'field_label', 'Role', 0, ['description' => 'Where it goes, such as At the counter.']);
ib_field($p, 'bundle_item', 'field_reference', 'entity_reference', 'Device or solution', ['storage' => ['target_type' => 'node'], 'settings' => ib_ref_nodes(['device', 'solution']), 'weight' => 1]);
$string('bundle_item', 'field_title', 'Name', 2, ['description' => 'Used when no device or solution is chosen, or to override its title.']);
$string('bundle_item', 'field_note', 'Note', 3);
ib_field($p, 'bundle_item', 'field_quantity', 'integer', 'Quantity', ['default' => 1, 'settings' => ['min' => 1], 'weight' => 4]);
ib_field($p, 'bundle_item', 'field_monthly_price', 'decimal', 'Monthly price each', ['storage' => ['precision' => 10, 'scale' => 2], 'settings' => ['prefix' => '$'], 'weight' => 5, 'description' => 'Overrides the device lease price. Used for items that are not devices.']);

// Sections.
ib_paragraph_type('hero', 'Hero', 'The top of a page: headline with one italic accent word, lead and calls to action.');
ib_field($p, 'hero', 'field_hero_variant', 'list_string', 'Variant', ['storage' => ['allowed_values' => ['split' => 'Split with media', 'centered' => 'Centered', 'compact' => 'Compact', 'compact_search' => 'Compact with search', 'image_right' => 'Image right']], 'required' => TRUE, 'default' => 'split', 'widget' => 'options_select', 'weight' => -1]);
$string('hero', 'field_eyebrow', 'Eyebrow', 0);
$string('hero', 'field_heading', 'Headline', 1, ['required' => TRUE]);
$string('hero', 'field_accent_word', 'Accent word', 2, ['description' => 'One word from the headline to set in italic clay.']);
$text('hero', 'field_intro', 'Lead', 3);
$link('hero', 'field_link', 'Primary link', 4);
$link('hero', 'field_link_secondary', 'Secondary link', 5);
ib_field($p, 'hero', 'field_checklist', 'string', 'Checklist', ['cardinality' => -1, 'weight' => 6]);
ib_field($p, 'hero', 'field_media', 'entity_reference', 'Media', ['storage' => ['target_type' => 'media'], 'settings' => ib_ref_media(['image']), 'widget' => 'media_library_widget', 'weight' => 7]);
$bool('hero', 'field_show_breadcrumb', 'Show breadcrumb', 8);

ib_paragraph_type('logo_strip', 'Logo strip', 'A row of partner names or logos.');
$text('logo_strip', 'field_intro', 'Intro text', 0);
ib_field($p, 'logo_strip', 'field_names', 'string', 'Names', ['cardinality' => -1, 'weight' => 1, 'description' => 'Shown as text until logos are supplied with permission.']);
ib_field($p, 'logo_strip', 'field_logos', 'entity_reference', 'Logos', ['storage' => ['target_type' => 'media'], 'settings' => ib_ref_media(['image']), 'cardinality' => -1, 'widget' => 'media_library_widget', 'weight' => 2]);

ib_paragraph_type('feature_grid', 'Feature grid', 'A heading and a grid of cards.');
$header('feature_grid');
$items('feature_grid', 'Cards', ['feature_card'], 3);

ib_paragraph_type('chip_links', 'Industry picker', 'Chips that switch between industries and their typical setups.');
$header('chip_links', FALSE);
$items('chip_links', 'Industries', ['chip_item'], 3);

ib_paragraph_type('card_grid', 'Device card grid', 'A grid of device cards, picked by hand or by a filter.');
$header('card_grid');
$link('card_grid', 'field_link', 'Link', 3);
ib_field($p, 'card_grid', 'field_source', 'list_string', 'Source', ['storage' => ['allowed_values' => ['manual' => 'Devices picked below', 'featured' => 'Featured devices', 'dual_pricing' => 'Dual pricing ready devices']], 'required' => TRUE, 'default' => 'manual', 'widget' => 'options_select', 'weight' => 4]);
ib_field($p, 'card_grid', 'field_devices', 'entity_reference', 'Devices', ['storage' => ['target_type' => 'node'], 'settings' => ib_ref_nodes(['device']), 'cardinality' => -1, 'weight' => 5]);
$string('card_grid', 'field_footnote', 'Footnote', 6, ['storage' => ['max_length' => 255]]);
$string('card_grid', 'field_price_note', 'Price footnote', 7, ['description' => 'Shown only while "Show prices" is on.']);

ib_paragraph_type('stats', 'Stat row', 'Up to four values with labels.');
$items('stats', 'Stats', ['stat'], 0, 4);

ib_paragraph_type('testimonial', 'Testimonial', 'A customer quote with before and after figures.');
$text('testimonial', 'field_quote', 'Quote', 0, ['required' => TRUE]);
$string('testimonial', 'field_name', 'Name', 1);
$string('testimonial', 'field_role', 'Role and business', 2);
$string('testimonial', 'field_label', 'Figures label', 3, ['description' => 'For example Their monthly fees.']);
$string('testimonial', 'field_before', 'Before', 4);
$string('testimonial', 'field_after', 'After', 5);
$link('testimonial', 'field_link', 'Link', 6);
$bool('testimonial', 'field_placeholder', 'This is a placeholder', 7, ['default' => 1, 'description' => 'Leave on until a real, approved quote is supplied. Placeholders are labeled on the page.']);

ib_paragraph_type('steps', 'Steps', 'Numbered steps.');
$header('steps');
$items('steps', 'Steps', ['step'], 3);

ib_paragraph_type('comparison_table', 'Comparison table', 'A table comparing a few options row by row.');
$header('comparison_table', FALSE);
ib_field($p, 'comparison_table', 'field_columns', 'string', 'Column headers', ['cardinality' => -1, 'weight' => 2]);
ib_field($p, 'comparison_table', 'field_highlight_column', 'integer', 'Highlighted column', ['settings' => ['min' => 1], 'weight' => 3, 'description' => 'The number of the column to highlight, counting from 1.']);
$items('comparison_table', 'Rows', ['table_row'], 4);

ib_paragraph_type('savings_estimator', 'Savings estimator', 'Compares monthly processing cost across the three pricing programs.');
$header('savings_estimator');
$text('savings_estimator', 'field_assumptions', 'Assumptions', 3);
$decimal = fn(string $name, string $label, int $weight, string $suffix) => ib_field($p, 'savings_estimator', $name, 'decimal', $label, ['storage' => ['precision' => 10, 'scale' => 2], 'settings' => ['suffix' => $suffix], 'weight' => $weight]);
$decimal('field_flat_percent', 'Flat rate percent', 4, '%');
$decimal('field_flat_cents', 'Flat rate cents per sale', 5, '¢');
$decimal('field_ic_percent', 'Interchange plus effective percent', 6, '%');
$decimal('field_ic_cents', 'Interchange plus cents per sale', 7, '¢');
$decimal('field_avg_ticket', 'Average ticket', 8, '');
$decimal('field_dp_fee', 'Monthly dual pricing fee', 9, '');
$decimal('field_default_volume', 'Starting monthly card sales', 10, '');

ib_paragraph_type('pricing_cards', 'Pricing cards', 'The pricing programs side by side.');
$string('pricing_cards', 'field_heading', 'Heading', 0, ['description' => 'Read by screen readers; not shown.']);
$items('pricing_cards', 'Cards', ['pricing_card'], 1);
$string('pricing_cards', 'field_footnote', 'Footnote', 2);
$string('pricing_cards', 'field_price_note', 'Price footnote', 3, ['description' => 'Shown only while "Show prices" is on.']);

ib_paragraph_type('callout', 'Callout', 'A boxed note.');
ib_field($p, 'callout', 'field_callout_variant', 'list_string', 'Variant', ['storage' => ['allowed_values' => ['info' => 'Info', 'success' => 'Success', 'warning' => 'Warning', 'danger' => 'Danger', 'brand' => 'Brand']], 'required' => TRUE, 'default' => 'info', 'widget' => 'options_select', 'weight' => 0]);
$string('callout', 'field_title', 'Title', 1);
ib_field($p, 'callout', 'field_text', 'text_long', 'Body', ['required' => TRUE, 'weight' => 2, 'settings' => ['allowed_formats' => ['basic_html']]]);

ib_paragraph_type('faq', 'FAQ accordion', 'Questions and answers. Outputs FAQPage structured data.');
$string('faq', 'field_eyebrow', 'Eyebrow', 0);
$string('faq', 'field_heading', 'Heading', 1);
ib_field($p, 'faq', 'field_intro_html', 'text_long', 'Intro', ['weight' => 2, 'settings' => ['allowed_formats' => ['basic_html']]]);
ib_field($p, 'faq', 'field_layout', 'list_string', 'Layout', ['storage' => ['allowed_values' => ['split' => 'Heading beside the questions', 'centered' => 'Centered, narrow']], 'required' => TRUE, 'default' => 'split', 'widget' => 'options_select', 'weight' => 3]);
$items('faq', 'Questions', ['faq_item'], 4);

ib_paragraph_type('bundle', 'Recommended setup', 'Devices and solutions that work together, with a monthly estimate.');
$header('bundle');
$items('bundle', 'Items', ['bundle_item'], 3);
ib_field($p, 'bundle', 'field_addons', 'string', 'Add-ons', ['cardinality' => -1, 'weight' => 4]);
$string('bundle', 'field_note', 'Estimate note', 5);
$link('bundle', 'field_link', 'Link', 6);

ib_paragraph_type('case_study', 'Case study', 'A short customer story with up to three metrics.');
$header('case_study', FALSE);
$text('case_study', 'field_body', 'Body', 2);
ib_field($p, 'case_study', 'field_media', 'entity_reference', 'Image', ['storage' => ['target_type' => 'media'], 'settings' => ib_ref_media(['image']), 'widget' => 'media_library_widget', 'weight' => 3]);
$items('case_study', 'Metrics', ['stat'], 4, 3);
$link('case_study', 'field_link', 'Link', 5);
$bool('case_study', 'field_placeholder', 'This is a placeholder', 6, ['default' => 1, 'description' => 'Leave on until a real, approved story is supplied. Placeholders are labeled on the page.']);

ib_paragraph_type('related_resources', 'Related resources', 'Up to three resources.');
$string('related_resources', 'field_heading', 'Heading', 0);
ib_field($p, 'related_resources', 'field_resources', 'entity_reference', 'Resources', ['storage' => ['target_type' => 'node'], 'settings' => ib_ref_nodes(['resource']), 'cardinality' => 3, 'weight' => 1]);

ib_paragraph_type('cta_band', 'CTA band', 'A clove band with a headline and up to two links.');
$string('cta_band', 'field_heading', 'Headline', 0, ['required' => TRUE]);
$string('cta_band', 'field_accent_word', 'Accent word', 1);
$text('cta_band', 'field_body', 'Body', 2);
$link('cta_band', 'field_link', 'Primary link', 3);
$link('cta_band', 'field_link_secondary', 'Secondary link', 4);

ib_paragraph_type('webform_embed', 'Embedded form', 'A form with a heading and steps beside it.');
$header('webform_embed');
$items('webform_embed', 'Steps', ['step'], 3);
$string('webform_embed', 'field_note', 'Reassurance line', 4);
ib_field($p, 'webform_embed', 'field_webform', 'webform', 'Form', ['required' => TRUE, 'widget' => 'webform_entity_reference_select', 'weight' => 5]);
$string('webform_embed', 'field_anchor', 'Anchor', 6, ['description' => 'Lets links jump to this section, for example statement.']);

// The sections a page can be built from.
$sections = ['hero', 'logo_strip', 'feature_grid', 'chip_links', 'card_grid', 'stats', 'testimonial', 'steps', 'comparison_table', 'savings_estimator', 'pricing_cards', 'callout', 'faq', 'bundle', 'case_study', 'related_resources', 'cta_band', 'webform_embed'];
foreach (['landing_page', 'solution', 'industry_page'] as $type) {
  ib_paragraphs_field('node', $type, 'field_sections', 'Sections', $sections, -1, 10, 'The page is built from these sections, top to bottom. Start with a hero.');
}

print "Page sections are in place.\n";
