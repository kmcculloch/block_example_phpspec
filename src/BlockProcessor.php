<?php

namespace Drupal\block_example_phpspec;

/**
 * @class BlockProcessor
 */
class BlockProcessor
{
    /**
     * @var $drupalServiceWrapper
     *   Our local Drupal dependency.
     */
    protected $drupalServiceWrapper;

    /**
     * Class constructor.
     *
     * @param object $drupalServiceWrapper
     *   An injected service wrapper to let us talk to Drupal.
     */
    public function __construct($drupalServiceWrapper)
    {
        $this->drupalServiceWrapper = $drupalServiceWrapper;
    }

    /**
     * Examine the input from hook_block_view_alter for 'uppercase' requests.
     *
     * @param array|null $data
     * @param object     $block
     *
     * @return bool
     */
    public function findUppercase($data, $block)
    {
        if (!empty($block->title)) {
            if ($this->uppercaseInString($block->title)) {
                return true;
            }
        } elseif (!empty($data['subject'])) {
            if ($this->uppercaseInString($data['subject'])) {
                return true;
            }
        }

        // No 'uppercase' found.
        return false;
    }

    /**
     * Do uppercase processing on the $data array.
     *
     * @param array $data
     *
     * @return array
     */
    public function setDataToUppercase($data)
    {
        if (isset($data['subject'])) {
            $data['subject'] = $this->drupalServiceWrapper->call(
                'drupal_strtoupper',
                array($data['subject'])
            );
        } else {
            $data['subject'] = '';
        }

        return $data;
    }

    /**
     * Do uppercase processing on the $block object.
     *
     * @param object $block
     *
     * @return object
     */
    public function setBlockToUppercase($block)
    {
        if (isset($block->title)) {
            $block->title = $this->drupalServiceWrapper->call(
                'drupal_strtoupper',
                array($block->title)
            );
        } else {
            $block->title = '';
        }

        return $block;
    }

    /**
     * A helper function to find strings that want to be uppercase.
     *
     * Since this is not a public function, and hence not one of our object's
     * behaviors, we can't access it through PHPSpec and we won't write any
     * tests for it. This is the key difference between object-level behavior-
     * driven development and more classic unit testing.
     */
    protected function uppercaseInString($str)
    {
        return stristr($str, 'uppercase');
    }
}
// vim: set ft=php.symfony :
