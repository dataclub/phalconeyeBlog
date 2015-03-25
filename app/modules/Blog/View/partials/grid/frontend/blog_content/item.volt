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

<article id="post-id-{{ item['id'] }}" class="widget_wrapper">
    <header class="entry-header">
        <div class="widget_header"><h3>Header</h3></div>
        <div class="entry-meta">
            <span class="posted-on">
                        <a rel="bookmark" href="?date">
                            <time class="entry-date published">
                                {% if (item['modified_date']|length) > 2  %}
                                    {{ item['modified_date'] }}
                                {% else %}
                                    {{ item['creation_date'] }}
                                {% endif %}

                            </time>
                        </a>
                    </span>
                        |
                    <span class="byline">
                        <span class="author vcard">
                            <a href="?author" class="url fn n">{{ item['username'] }}</a>
                        </span>
                    </span>
            </div>
    </header>

    <div class="entry-content">
        {{ item['body'] }}
    </div>

    <footer class="entry-meta">
        <span class="comments-link"><a title="Comment" href="blog/comment/{{ item['id'] }}">Leave a comment ({{ item['counted_comments'] }})</a></span>
    </footer>
    <footer class="entry-meta">
        <div class="entry-meta">
        Categories:
            <span class="cat-links">
                {% for returnItem in grid.getCategoriesForFrontend(item) %}
                    <a rel="category" title="{{ returnItem['title'] }}" href="{{ returnItem['url'] }}">{{ returnItem['value'] }}</a>,
                {% endfor %}

            </span>
        </div>
        <div class="entry-meta">
        Tags:
            <span class="cat-links">
                {% for returnItem in grid.getTagsForFrontend(item) %}
                    <a rel="tag" title="{{ returnItem['title'] }}" href="{{ returnItem['url'] }}">{{ returnItem['value'] }}</a>,
                {% endfor %}

            </span>
        </div>
    </footer>
</article>