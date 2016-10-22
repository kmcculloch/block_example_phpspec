<?php

/**
 * @file
 * Module file for block_example.
 */

/**
 * Implements hook_block_view_alter().
 */
function block_example_block_view_alter(&$data, $block) {
  // We'll search for the string 'uppercase'.
  if ((!empty($block->title) && stristr($block->title, 'uppercase')) || (!empty($data['subject']) && stristr($data['subject'], 'uppercase'))) {
    // This will uppercase the default title.
    $data['subject'] = isset($data['subject']) ? drupal_strtoupper($data['subject']) : '';
    // This will uppercase a title set in the UI.
    $block->title = isset($block->title) ? drupal_strtoupper($block->title) : '';
  }
}
