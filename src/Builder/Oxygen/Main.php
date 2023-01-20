<?php

/*
 * This file is part of the Yabe package.
 *
 * (c) Joshua <joshua@rosua.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Yabe\Webfont\Builder\Oxygen;

use ECF_Plugin;
use Yabe\Webfont\Builder\BuilderInterface;
use Yabe\Webfont\Core\Runtime;
use Yabe\Webfont\Plugin;

/**
 * @author Joshua <joshua@rosua.org>
 */
class Main implements BuilderInterface
{
    public function get_name(): string
    {
        return 'oxygen';
    }

    public function __construct()
    {
        add_action('wp_enqueue_scripts', fn () => $this->enqueue_editor_style(), 1000001);
        // remove_action('ct_builder_ng_init', 'ct_init_elegant_custom_fonts');
        add_action('ct_builder_ng_init', fn () => $this->elegant_custom_fonts(), 1000001);
    }

    public function elegant_custom_fonts()
    {
        $output = json_encode(Runtime::get_fonts_family());
        $output = htmlspecialchars($output, ENT_QUOTES);
        echo sprintf('elegantCustomFonts=%s;', $output);
    }

    public function enqueue_editor_style()
    {
        if (!defined('SHOW_CT_BUILDER')) {
            return;
        }

        wp_enqueue_style('yabe-webfont-for-oxygen-editor', plugin_dir_url(__FILE__) . '/assets/style/editor.css', [], Plugin::VERSION);
    }
}
