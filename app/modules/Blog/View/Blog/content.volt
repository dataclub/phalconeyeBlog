<style>
    p{
        padding-bottom: 1em;
    }
    .blogpost article {
        background: none repeat scroll 0 0 #fff;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        padding: 15px;
    }

    .bloglist {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .blogpost, .blogpost ul > .blogpost, .bloglist > .blogpost {
        font-size: 14px;
        list-style: outside none none;
        margin: 0 0 20px;
        position: relative;
    }
    li {
        line-height: 20px;
    }
</style>


{% for post in page.items %}
{% if loop.first %}
<ul>
{% endif %}
    <li class="blogpost">
          <article>

              <h3>{{ post.title}}</h3>
                  <p> by {{ post.username}} | {{ post.creation_date}} | Kategorie </p>
                  <p>
                      <asdasdad></asdasdad>
                  </p>
          </article>
       </li>
{% if loop.last %}

                <div class="btn-group">
                    {{ link_to("blog/index", '<i class="icon-fast-backward"></i> First', "class": "btn btn-default") }}
                    {{ link_to("blog/index?page=" ~ page.before, '<i class="icon-step-backward"></i> Previous', "class": "btn btn-default") }}
                    {{ link_to("blog/index?page=" ~ page.next, '<i class="icon-step-forward"></i> Next', "class": "btn btn-default") }}
                    {{ link_to("blog/index?page=" ~ page.last, '<i class="icon-fast-forward"></i> Last', "class": "btn btn-default") }}
                    <span class="help-inline">{{ page.current }}/{{ page.total_pages }}</span>
                </div>
</ul>
{% endif %}
{% else %}
    No companies are recorded
{% endfor %}



