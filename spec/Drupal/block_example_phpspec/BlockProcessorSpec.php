<?php

namespace spec\Drupal\block_example_phpspec;

use PhpSpec\ObjectBehavior;

class BlockProcessorSpec extends ObjectBehavior
{
    function let($drupal)
    {
        $drupal->beADoubleOf('\Drupal\block_example_phpspec\DrupalServiceWrapper');
        $drupal->call('drupal_strtoupper', array('make me uppercase'))
            ->willReturn('MAKE ME UPPERCASE');
        $drupal->call('drupal_strtoupper', array(''))
            ->willReturn('');

        $this->beConstructedWith($drupal);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Drupal\block_example_phpspec\BlockProcessor');
    }

    function it_finds_uppercase_in_blocks()
    {
        $data = array();
        $block = new \stdClass();
        $block->title = 'make me UPPERCASE';

        $this->findUppercase($data, $block)->shouldReturn(true);
    }

    function it_finds_uppercase_in_data()
    {
        $data = array(
            'subject' => 'make me uppERcase',
        );
        $block = new \stdClass();

        $this->findUppercase($data, $block)->shouldReturn(true);
    }

    function there_is_no_uppercase_to_find()
    {
        $data = array();
        $block = new \stdClass();

        $this->findUppercase($data, $block)->shouldReturn(false);
    }

    function it_sets_data_to_uppercase()
    {
        $data = array(
            'subject' => 'make me uppercase',
        );

        $this->setDataToUppercase($data)->shouldReturn(array(
            'subject' => 'MAKE ME UPPERCASE',
        ));
    }

    function it_handles_no_data_subject()
    {
        $data = array();

        $this->setDataToUppercase($data)->shouldReturn(array(
            'subject' => '',
        ));
    }

    function it_handles_empty_data_subject()
    {
        $data = array(
            'subject' => '',
        );

        $this->setDataToUppercase($data)->shouldReturn(array(
            'subject' => '',
        ));
    }
}
