<div id="about">
    <p> This is the about page <br>
    {# comment #}
    {{   data }} <br>
    <br>
    {{ do_something('test something') }}
    <br>
    {% set variableName = "test" %} <br>
    {{ do_something($variableName) }}
    <br>
  
    {{ arraykey.thekey }} <br>
    {{ object>member }} <br>
    <br>
    </p>
    <ul>
    {% for item in array  %}
    	<li>{{item}}</li>
    {% endfor %}
	</ul>
	<br>
	<ul>
    {% for key,item in array  %}
    	<li>{{key}} {{item}}</li>
    {% endfor %}
	</ul>
    
</div>
