<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* cours/index.html.twig */
class __TwigTemplate_2fbd7e58250c28f93003fe701be28340 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'stylesheets' => [$this, 'block_stylesheets'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "cours/index.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "cours/index.html.twig"));

        $this->parent = $this->loadTemplate("base.html.twig", "cours/index.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        yield "Cours index";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_stylesheets(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        // line 6
        yield "    <link rel=\"stylesheet\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("assets/css/chapitre1.css"), "html", null, true);
        yield "\">
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 9
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 10
        yield "    <h1>Cours index</h1>

    ";
        // line 13
        yield "    <form action=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_cours_filter");
        yield "\" method=\"get\" class=\"mb-4\">
        <div class=\"form-group\">
            <label for=\"matiere\">Filtrer par matière :</label>
            <select name=\"matiere\" id=\"matiere\" class=\"form-control\">
                <option value=\"\">Toutes les matières</option>
                ";
        // line 18
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["matieres"]) || array_key_exists("matieres", $context) ? $context["matieres"] : (function () { throw new RuntimeError('Variable "matieres" does not exist.', 18, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["matiere"]) {
            // line 19
            yield "                    <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["matiere"], "id", [], "any", false, false, false, 19), "html", null, true);
            yield "\" ";
            yield (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 19, $this->source); })()), "request", [], "any", false, false, false, 19), "query", [], "any", false, false, false, 19), "get", ["matiere"], "method", false, false, false, 19) == CoreExtension::getAttribute($this->env, $this->source, $context["matiere"], "id", [], "any", false, false, false, 19))) ? ("selected") : (""));
            yield ">
                        ";
            // line 20
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["matiere"], "titre", [], "any", false, false, false, 20), "html", null, true);
            yield "
                    </option>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['matiere'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 23
        yield "            </select>
        </div>
        <button type=\"submit\" class=\"btn btn-primary\">Filtrer</button>
    </form>

    ";
        // line 29
        yield "    <div class=\"row\">
        ";
        // line 30
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["cours"]) || array_key_exists("cours", $context) ? $context["cours"] : (function () { throw new RuntimeError('Variable "cours" does not exist.', 30, $this->source); })()));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["cour"]) {
            // line 31
            yield "            <div class=\"col-md-4\">
                <!-- Widget: user widget style 1 -->
                <div class=\"card card-widget widget-user\">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class=\"widget-user-header text-white\" style=\"background: url('";
            // line 35
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl(("uploads/brochures/" . CoreExtension::getAttribute($this->env, $this->source, $context["cour"], "urlImage", [], "any", false, false, false, 35))), "html", null, true);
            yield "') center center;\">
                        <h3 class=\"widget-user-username text-right\">";
            // line 36
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["cour"], "matiere", [], "any", false, false, false, 36), "titre", [], "any", false, false, false, 36), "html", null, true);
            yield "</h3>
                        <h5 class=\"widget-user-desc text-right\">";
            // line 37
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["cour"], "title", [], "any", false, false, false, 37), "html", null, true);
            yield "</h5>
                    </div>
                    <div class=\"widget-user-image\">
                        <img class=\"img-circle elevation-2\" src=\"";
            // line 40
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("img/1.jpg"), "html", null, true);
            yield "\" alt=\"User Avatar\">
                    </div>
                    <div class=\"card-footer\">
                        <div class=\"row\">
                            <div class=\"col-sm-4 border-right\">
                                <div class=\"description-block\">
                                    <h5 class=\"description-header\">number of Chapters</h5>
                                    <span class=\"description-text\">";
            // line 47
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["cour"], "nbChapitres", [], "any", false, false, false, 47), "html", null, true);
            yield "</span>
                                </div>
                            </div>
                            <div class=\"col-sm-4 border-right\">
                                <div class=\"description-block\">
                                <h5 class=\"description-header\"></h5>
                                  <span class=\"description-text\">
                                        <a href=\"";
            // line 54
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_cours_show", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["cour"], "id", [], "any", false, false, false, 54)]), "html", null, true);
            yield "\" class=\"btn btn-propose2\">show</a>
                                    </span>
                                   
                                </div>
                            </div>
                            <div class=\"col-sm-4\">
                                <div class=\"description-block\">
                                     <h5 class=\"description-header\">LEARNERS</h5>
                                    <span class=\"description-text\"></span>
                                    
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ";
            $context['_iterated'] = true;
        }
        // line 75
        if (!$context['_iterated']) {
            // line 72
            yield "            <div class=\"col-12\">
                <p>Aucun cours trouvé.</p>
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['cour'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 76
        yield "    </div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "cours/index.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  249 => 76,  240 => 72,  238 => 75,  216 => 54,  206 => 47,  196 => 40,  190 => 37,  186 => 36,  182 => 35,  176 => 31,  171 => 30,  168 => 29,  161 => 23,  152 => 20,  145 => 19,  141 => 18,  132 => 13,  128 => 10,  115 => 9,  101 => 6,  88 => 5,  65 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base.html.twig' %}

{% block title %}Cours index{% endblock %}

{% block stylesheets %}
    <link rel=\"stylesheet\" href=\"{{ asset('assets/css/chapitre1.css') }}\">
{% endblock %}

{% block body %}
    <h1>Cours index</h1>

    {# Formulaire de filtrage par matière #}
    <form action=\"{{ path('app_cours_filter') }}\" method=\"get\" class=\"mb-4\">
        <div class=\"form-group\">
            <label for=\"matiere\">Filtrer par matière :</label>
            <select name=\"matiere\" id=\"matiere\" class=\"form-control\">
                <option value=\"\">Toutes les matières</option>
                {% for matiere in matieres %}
                    <option value=\"{{ matiere.id }}\" {{ app.request.query.get('matiere') == matiere.id ? 'selected' : '' }}>
                        {{ matiere.titre }}
                    </option>
                {% endfor %}
            </select>
        </div>
        <button type=\"submit\" class=\"btn btn-primary\">Filtrer</button>
    </form>

    {# Affichage des cours #}
    <div class=\"row\">
        {% for cour in cours %}
            <div class=\"col-md-4\">
                <!-- Widget: user widget style 1 -->
                <div class=\"card card-widget widget-user\">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class=\"widget-user-header text-white\" style=\"background: url('{{ asset('uploads/brochures/' ~ cour.urlImage) }}') center center;\">
                        <h3 class=\"widget-user-username text-right\">{{ cour.matiere.titre }}</h3>
                        <h5 class=\"widget-user-desc text-right\">{{ cour.title }}</h5>
                    </div>
                    <div class=\"widget-user-image\">
                        <img class=\"img-circle elevation-2\" src=\"{{ asset('img/1.jpg') }}\" alt=\"User Avatar\">
                    </div>
                    <div class=\"card-footer\">
                        <div class=\"row\">
                            <div class=\"col-sm-4 border-right\">
                                <div class=\"description-block\">
                                    <h5 class=\"description-header\">number of Chapters</h5>
                                    <span class=\"description-text\">{{ cour.nbChapitres }}</span>
                                </div>
                            </div>
                            <div class=\"col-sm-4 border-right\">
                                <div class=\"description-block\">
                                <h5 class=\"description-header\"></h5>
                                  <span class=\"description-text\">
                                        <a href=\"{{ path('app_cours_show', {'id': cour.id}) }}\" class=\"btn btn-propose2\">show</a>
                                    </span>
                                   
                                </div>
                            </div>
                            <div class=\"col-sm-4\">
                                <div class=\"description-block\">
                                     <h5 class=\"description-header\">LEARNERS</h5>
                                    <span class=\"description-text\"></span>
                                    
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {% else %}
            <div class=\"col-12\">
                <p>Aucun cours trouvé.</p>
            </div>
        {% endfor %}
    </div>
{% endblock %}", "cours/index.html.twig", "C:\\Users\\chams\\OneDrive\\Bureau\\projetPIDEV\\javaelites\\knowlity\\templates\\cours\\index.html.twig");
    }
}
