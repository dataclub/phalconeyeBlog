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
#}


<style>
    h1, h2, h3, h4, h5, h6 {
        font-weight: normal;
        line-height: 1.2;
        margin-top: 0;
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
    ul {
        padding: 0px;
    }
</style>


<main id="main" class="site-main" role="main">
    {{ partial(grid.getTableBodyView(), ['grid': grid]) }}
</main>
