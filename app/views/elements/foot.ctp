<?php
/**
 * Tatoeba Project, free collaborative creation of multilingual corpuses project
 * Copyright (C) 2009  HO Ngoc Phuong Trang <tranglich@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Tatoeba
 * @author   HO Ngoc Phuong Trang <tranglich@gmail.com>
 * @license  Affero General Public License
 * @link     http://tatoeba.org
 */

$onlineVisitors = ClassRegistry::init('Visitor')->numberOfOnlineVisitors();
if (isset($this->params['lang'])) {
    Configure::write('Config.language', $this->params['lang']);
}
?>
<div id="footer">
<ul>
    <li>
        <?php echo __('Online visitor(s) : ') . $onlineVisitors; ?>
    </li>
    <li>
        <?php
        echo $html->link(
            __('Terms of use', true),
            array(
                "controller" => 'pages',
                "action" => 'terms_of_use'
            )
        );
        ?>
    </li>
    <li>
        <?php
        echo $html->link(
            __('Contact us', true),
            array(
                "controller" => 'pages',
                "action" => 'contact'
            )
        );
        ?>
    </li>
    <li>
        <?php
        echo $html->link(
            __('Downloads', true),
            array(
                "controller" => 'pages',
                "action" => 'download_tatoeba_example_sentences'
            )
        );
        ?>
    </li>
    <li>
        <?php
        echo $html->link(
            __('Tools', true),
            array(
                "controller" => 'tools',
                "action"=>'index'
            )
        );
        ?>
    </li>
    <li>
        <?php
        echo $html->link(
            __('Team & Credits', true),
            array(
                "controller" => 'pages',
                "action" => 'tatoeba_team_and_credits'
            )
        );
        ?>
    </li>
    <li>
        <?php
        echo $html->link(
            __('FAQ', true),
            array(
                "controller" => 'pages',
                "action" => 'faq'
            )
        );
        ?>
    </li>
    <li>
        <?php
        echo $html->link(
            __('Help', true),
            array(
                "controller" => 'pages',
                "action" => 'help'
            )
        );
        ?>
    </li>
</ul>
</div>
