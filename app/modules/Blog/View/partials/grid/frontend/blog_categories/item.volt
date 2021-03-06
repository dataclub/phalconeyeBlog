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
{% if grid.hasActions() %}
<div class="actions">

    {% for key, action in grid.getItemActions(item) %}
                <a href="{% if action['href'] is defined %}{{ url(action['href']) }}{% else %}javascript:;{% endif %}"
                {% if action['attr'] is defined %}
                    {% for attrName, attrValue in action['attr'] %}
                        {{ attrName }}="{{ attrValue }}"
                    {% endfor %}
                {% endif %}
                >
                {{ item['name'] |i18n}} ({{ grid.getCategoriesCount(item) + grid.getSubCategoriesCount(item)}})
                </a>
            {% endfor %}
</div>
{% endif %}

