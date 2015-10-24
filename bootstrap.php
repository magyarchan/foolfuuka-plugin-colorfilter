<?php

use Foolz\FoolFrame\Model\Autoloader;
use Foolz\FoolFrame\Model\Context;
use Foolz\Plugin\Event;

class HHVM_ColorFilter
{
    public function run()
    {
        Event::forge('Foolz\Plugin\Plugin::execute#foolz/foolfuuka-plugin-colorfilter')
            ->setCall(function($result) {

                /* @var Context $context */
                $context = $result->getParam('context');
                /** @var Autoloader $autoloader */
                $autoloader = $context->getService('autoloader');

                $autoloader->addClass('Foolz\FoolFuuka\Plugins\ColorFilter\Model\Filter', __DIR__.'/classes/model/filter.php');

                Event::forge('Foolz\FoolFuuka\Model\Comment::processComment#var.processedComment')
                    ->setCall('Foolz\FoolFuuka\Plugins\ColorFilter\Model\Filter::outfilter')
                    ->setPriority(4);

                Event::forge('Foolz\FoolFuuka\Model\CommentInsert::insert#obj.afterInputCheck')
                    ->setCall('Foolz\FoolFuuka\Plugins\ColorFilter\Model\Filter::infilter')
                    ->setPriority(4);


                Event::forge('Foolz\FoolFuuka\Model\RadixCollection::structure#var.structure')
                    ->setCall(function($result) {
                        $structure = $result->getParam('structure');
                        $structure['plugin_colorfilter_enable'] = [
                            'database' => true,
                            'boards_preferences' => true,
                            'type' => 'checkbox',
                            'help' => _i('KÖCSÖG KÖCSÖG KÖCSÖG?')
                        ];
//                        $structure['plugin_colorfilter_filtertext'] = [
//                            'database' => true,
//                            'boards_preferences' => true,
//                            'type' => 'input',
//                            'class' => 'span3',
//                            'label' => 'Words to filter',
//                            'help' => _i(''),
//                            'default_value' => false
//                        ];    
                        $result->setParam('structure', $structure)->set($structure);
                    })->setPriority(4);
            });
    }
}

(new HHVM_ColorFilter())->run();
