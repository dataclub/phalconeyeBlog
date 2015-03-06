{#
  +------------------------------------------------------------------------+
  | PhalconEye CMS                                                         |
  +------------------------------------------------------------------------+
  | Copyright (c) 2013-2014 PhalconEye Team (http://phalconeye.com/)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file LICENSE.txt.                             |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconeye.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Author: Ivan Vorontsov <ivan.vorontsov@phalconeye.com>                 |
  +------------------------------------------------------------------------+
#}

<table id="{{ grid.getId() }}" class="table grid-table" data-widget="grid">
    <thead>
    <tr>
        {% for name, column in grid.getColumns() %}
            <th>
                {% if column[constant('\Engine\Grid\AbstractGrid::COLUMN_PARAM_SORTABLE')] is defined and column[constant('\Engine\Grid\AbstractGrid::COLUMN_PARAM_SORTABLE')] %}
                    <a href="javascript:;" class="grid-sortable" data-sort="{{ name }}" data-direction="">
                        {{ column[constant('\Engine\Grid\AbstractGrid::COLUMN_PARAM_LABEL')] |i18n }}
                    </a>
                {% else %}
                    {{ column[constant('\Engine\Grid\AbstractGrid::COLUMN_PARAM_LABEL')] |i18n }}
                {% endif %}
            </th>
        {% endfor %}
        {% if grid.hasActions() %}
            <th class="actions">{{ 'Actions' |i18n }}</th>
        {% endif %}
    </tr>

    </thead>
    {{ partial(grid.getTableBodyView(), ['grid': grid]) }}
</table>