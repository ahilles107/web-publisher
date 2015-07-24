Templates
===============

Gimme and SWP custom Twig tags
------------------------------

Gimme allows you fetch nedded Meta object in any place of your template file. It supports single Meta objects (with :code:`gimme` ) and collections of Meta objects (with :code:`gimme_list`).

gimme
`````

Tag :code:`gimme` have one required parameter and one optional:

 * (required) Meta object type (and name of variable available inside block) ex.: *article*
 * (optional) Parameters for Meta Loader ex.: :code:`{ param: "value" }`

.. code-block:: twig

    {% gimme article %}
        {# article Meta will be available under "article" variable inside block #}
        {{ dump(article)}}
        {{ article.title }}
    {% endgimme %}

Meta Loaders sometimes requires some special parameters - like article number, language, user id etc.. 

.. code-block:: twig

    {% gimme article { articleNumber: 1 } %}
        {# Meta Loader will use provided parameters to load article Meta #}
        {{ dump(article)}}
        {{ article.title }}
    {% endgimme %}

gimmelist
`````````

Tag :code:`gimmelist` have two required parameter and four optional:

 * (required) Name of variable available inside block: :code:`article`
 * (required) Keword :code:`from` and type of requested Meta's in collection: :code:`from articles`
 * (optional) Keword :code:`with` and parameters for Meta Loader ex.: :code:`with {foo: 'bar', param1: 'value1'}`
 * (optional) Keword :code:`if` and expresion used for results filtering
 * (optional) Number of first item used in pagination of list: :code:`start=0`
 * (optional) Collection limit used in pagination of list: :code:`limit=10`

required parameters:

.. code-block:: twig

    {% gimmelist article from articles %}
        {{ dump(article)}}
        {{ article.title }}
    {% endgimmelist %}

all parameters:

.. code-block:: twig

    {% gimmelist article from articles with {foo: 'bar', param1: 'value1'} if article.is_public start=0 limit=10 %}
        {{ dump(article)}}
        {{ article.title }}
    {% endgimmelist %}

How to work with Meta objects
-----------------------------

On template level every variable in Context and fetched by :code:`gimme` and :code:`gimme_list` is representation of Meta objects.


**dump**

.. code-block:: twig

    {{ dump(article) }}

**print**

.. code-block:: twig

    {{ article }} - it meta configuration have filled to_string property then value of this property will be printed, json representation otherwise

**access property**

.. code-block:: twig

    {{ article.title }}
    {{ article['title']}}


Caching
--------

For now we support just themplate blocks caching with :code:`cache` block.

:code:`Cache` block is simple, and accepts only two parameters: cache key and strategy object (with strategy key and value).

.. note::

    Cache blocks can be nested:

    .. code-block:: twig

        {% cache 'v1' 900 %}
            {% for item in items %}
                {% cache 'v1' item %}
                    {# ... #}
                {% endcache %}
            {% endfor %}
        {% endcache %}

    The annotation can also be an expression:

    .. code-block:: twig

        {% set version = 42 %}
        {% cache 'hello_v' ~ version 900 %}
            Hello {{ name }}!
        {% endcache %}

There is no need to invalidate keys - system will clear not used cache entries automaticaly. 

Strategies
``````````

There are two available cache strategies: :code:`lifetime` and :code:`generational`.

With :code:`lifetime` as a strategy key you need provide :code:`time` with value in seconds.

.. code-block:: twig

    {# delegate to lifetime strategy #}
    {% cache 'v1/summary' {time: 300} %}
        {# heavy lifting template stuff here, include/render other partials etc #}
    {% endcache %}

With :code:`generational` as a strategy key you need provide :code:`gen` with object or array as value.

.. code-block:: twig

    {# delegate to generational strategy #}
    {% cache 'v1/summary' {gen: item} %}
        {# heavy lifting template stuff here, include/render other partials etc #}
    {% endcache %}
