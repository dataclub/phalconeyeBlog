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

<article id="post-id-{{ item['id'] }}" class="post type-post status-publish format-standard hentry widget_wrapper category-uncategorized">
    <header class="entry-header">
        <h1 class="entry-title">{{ item['title'] }}</h1>
        <div class="entry-meta">
            <span class="posted-on">
                <a rel="bookmark" href="?date">
                    <time class="entry-date published">
                        {% if (item['modified_date']|length) > 1  %}
                            {{ item['modified_date'] }}
                        {% else %}
                            {{ item['creation_date'] }}
                        {% endif %}
                    </time>
                </a>
            </span>
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
        <span class="comments-link"><a title="Comment" href="blog/comment/{{ item['id'] }}">Leave a comment</a></span>
    </footer>
</article>