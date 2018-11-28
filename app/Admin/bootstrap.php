<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

use Encore\Admin\Admin;
use Encore\Admin\Grid\Column;

Encore\Admin\Form::forget(['map', 'editor']);
Column::extend('color', function ($value, $color) {
    return "<span style='color: $color'>$value</span>";
});
Admin::css('/common/plugins/layui2.4.3/css/layui.css');
Admin::js('/common/plugins/layui2.4.3/layui.all.js');
