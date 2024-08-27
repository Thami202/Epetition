url = "/search_results"
layout = "default"
title = "Search Results"
==
<?php

==
{% if petitions is empty %}
    No results found
{% else %}
    <ul>
        {% for petition in petitions %}
            <li>{{ petition.title }}</li>
        {% endfor %}
    </ul>
{% endif %}
